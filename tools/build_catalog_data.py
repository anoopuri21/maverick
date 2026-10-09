#!/usr/bin/env python3
"""
Build database/seeders/data/doctorate_programs.php from the approved
markdown in output/programs/doctorate/.

Single source of truth = the approved content files. Never hand-edit the
generated PHP; re-run this script instead.

    python3 tools/build_catalog_data.py
"""
import re
import glob
import os
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
# One entry per generated data file. Each build reads
# output/programs/<src>/*/*.md and writes database/seeders/data/<out>.
BUILDS = [
    {
        "src": "doctorate",
        "out": "doctorate_programs.php",
        "categories": [("doctorate", "Doctorate", 200)],
        "universities": [
            ("rushford-business-school", "Rushford Business School", "Switzerland", "CH", 2),
            ("girne-american-university", "Girne American University", "North Cyprus", "CY", 3),
        ],
    },
    {
        "src": "diploma",
        "out": "diploma_programs.php",
        "categories": [("diploma", "Diploma", 300)],
        "universities": [
            ("gatehouse-awards", "Gatehouse Awards", "United Kingdom", "GB", 4),
            ("qualifi", "Qualifi", "United Kingdom", "GB", 5),
        ],
    },
]


def ph(s):
    """Quote a PHP single-quoted string."""
    return "'" + str(s).replace("\\", "\\\\").replace("'", "\\'") + "'"


def fm_get(fm, key):
    m = re.search(rf"^\s*{key}:\s*(.*?)\s*(?:#.*)?$", fm, re.M)
    if not m:
        return None
    return m.group(1).strip().strip('"')


def scalar(body, field):
    """Read a simple field written either as `x` → `value` or as a fenced block."""
    m = re.search(rf"`{field}`\s*→\s*`(.+?)`", body)
    if m:
        return m.group(1).strip()
    m = re.search(rf"`{field}`[^\n]*\n+```\n(.*?)\n```", body, re.S)
    if m:
        return m.group(1).strip()
    raise ValueError(f"cannot read field '{field}'")


def section(body, start, end=r"\n## "):
    m = re.search(re.escape(start) + r"(.*?)(?=" + end + "|\\Z)", body, re.S)
    return m.group(1) if m else ""


def table_rows(txt, ncols):
    """Parse a markdown table, skipping header + separator."""
    out = []
    for ln in txt.strip().split("\n"):
        ln = ln.strip()
        if not ln.startswith("|") or set(ln) <= set("|- "):
            continue
        cells = [c.strip().strip("`") for c in ln.strip("|").split("|")]
        if len(cells) != ncols:
            continue
        if [c.lower() for c in cells] in (
            ["label", "value"], ["icon", "title", "desc"], ["icon", "title", "text"]
        ):
            continue
        out.append(cells)
    return out


def numbered(txt):
    return [m.group(1).strip() for m in re.finditer(r"^\d+\.\s+(.+)$", txt, re.M)]


def items(txt):
    """A simple list written either as `1. x` lines or one `a · b · c` line."""
    nums = numbered(txt)
    if nums:
        return nums
    for l in txt.strip().split("\n"):
        l = l.strip()
        if "·" in l and not l.startswith(("*", "|", "**")):
            return [x.strip() for x in l.split("·") if x.strip()]
    return []


def parse_structure(txt):
    """Handles both the bullet form (`- Module (30 ECTS) — detail`) and the
    compact form (`Module (30) · Module (30) · ...`)."""
    stages = []
    # Heading may be "Stage 1 — ...", "Semester 1", "Semesters 4 to 7", "Year 2"
    # etc. GAU publishes semester plans, so the label is not always "Stage".
    pat = (r"\*\*((?:Stage|Semester|Semesters|Year|Years|Part)\b[^*\n]*?)\*\*"
           r"\s*·\s*\*(.*?)\*\s*\n(.+?)(?=\n\s*\n\*\*(?:Stage|Semester|Year|Part)"
           r"|\n\s*\n(?!-)|\Z)")
    for m in re.finditer(pat, txt, re.S):
        title = m.group(1).strip()
        subtitle = re.sub(r"^subtitle:\s*", "", m.group(2).strip())
        blk = m.group(3).strip()
        bullets = [re.sub(r"^-\s*", "", l).strip()
                   for l in blk.split("\n") if l.strip().startswith("-")]
        if bullets:
            modules = bullets
        else:
            modules = [x.strip() for x in blk.replace("\n", " ").split("·") if x.strip()]
        stages.append((title, subtitle, modules))
    return stages


def seo_cell(body, field):
    """Read an SEO table cell. Markdown escapes the pipe inside the value as
    `\\|`, so split on unescaped pipes only, then unescape."""
    row = re.search(rf"^\|\s*`{field}`\s*\|(.*)$", body, re.M)
    if not row:
        raise ValueError(f"no SEO row for '{field}'")
    cells = re.split(r"(?<!\\)\|", row.group(1))
    return cells[0].strip().replace("\\|", "|")


def parse_faqs(txt):
    """One heading line between ** **, then a <p> answer.

    Headings may end with ? or a full stop. The question must stay on a
    single line so a statement heading cannot swallow the next FAQ.
    """
    out = []
    for i, m in enumerate(re.finditer(
        r"^\*\*([^\n]+?)\*\*[ \t]*\n(<p>.*?</p>)",
        txt,
        re.M | re.S,
    ), 1):
        question = m.group(1).strip()
        if not question or "<" in question:
            continue
        out.append((question, m.group(2).strip(), i))
    return out


def build(path):
    raw = open(path, encoding="utf-8").read()
    fm, body = raw.split("\n---\n", 1)[0], raw.split("\n---\n", 1)[1]

    p = {
        "slug": fm_get(fm, "slug"),
        "title": fm_get(fm, "program_name"),
        "category_slug": fm_get(fm, "category_slug"),
        "university_slug": fm_get(fm, "university_slug"),
        "sort_order": int(fm_get(fm, "sort_order")),
        "is_active": fm_get(fm, "is_active") == "true",
    }
    p["duration"] = scalar(body, "duration")
    p["level"] = scalar(body, "level")
    p["hero"] = re.search(r"`short_description`[^\n]*\n+```\n(.*?)\n```", body, re.S).group(1).strip()
    html = re.search(r"`description`[^\n]*\n+```html\n(.*?)\n```", body, re.S).group(1)
    p["overview"] = re.findall(r"<p>(.*?)</p>", html, re.S)

    p["highlights"] = table_rows(section(body, "## 3.1 `highlights`"), 2)
    p["snapshot"] = table_rows(section(body, "## 3.2 `snapshot`"), 2)
    p["benefits"] = [[t, d, i] for i, t, d in table_rows(section(body, "## 3.3 `benefits`"), 3)]
    p["learning"] = numbered(section(body, "## 3.4 `learning`"))
    p["careers"] = numbered(section(body, "## 3.5 `careers`"))
    p["structure"] = parse_structure(section(body, "## 3.6 `structure`"))
    p["support"] = items(section(body, "## 3.8 `support`"))

    gcc = section(body, "## 3.9 `gcc_heading`", r"\n## 3\.11")
    p["gcc_heading"] = re.search(r"\*\*Heading:\*\*\s*`(.+?)`", gcc).group(1)
    p["gcc"] = [{"icon": i, "title": t, "text": x} for i, t, x in table_rows(gcc, 3)]

    p["fees"] = items(section(body, "## 3.11 `fees`", r"\n---"))
    p["faqs"] = parse_faqs(section(body, "## 4. `faqs`", r"\n## 5\. SEO"))
    p["meta_title"] = seo_cell(body, "meta_title")
    p["meta_description"] = seo_cell(body, "meta_description")
    return p


def emit(p, ind="        "):
    i2, i3, i4 = ind + "    ", ind + "        ", ind + "            "
    L = [ind + "["]
    for k in ("slug", "title", "level", "category_slug", "university_slug", "duration"):
        L.append(f"{i2}{ph(k)} => {ph(p[k])},")
    L.append(f"{i2}'sort_order' => {p['sort_order']},")
    L.append(f"{i2}'is_active' => " + ("true" if p["is_active"] else "false") + ",")
    L.append(f"{i2}'hero' => {ph(p['hero'])},")
    L.append(f"{i2}'overview' => [")
    L += [f"{i3}{ph(x)}," for x in p["overview"]]
    L.append(f"{i2}],")
    for key in ("highlights", "snapshot"):
        L.append(f"{i2}'{key}' => [")
        L += [f"{i3}[{ph(a)}, {ph(b)}]," for a, b in p[key]]
        L.append(f"{i2}],")
    L.append(f"{i2}'benefits' => [")
    L += [f"{i3}[{ph(t)}, {ph(d)}, {ph(ic)}]," for t, d, ic in p["benefits"]]
    L.append(f"{i2}],")
    for key in ("learning", "careers", "support", "fees"):
        L.append(f"{i2}'{key}' => [")
        L += [f"{i3}{ph(x)}," for x in p[key]]
        L.append(f"{i2}],")
    L.append(f"{i2}'structure' => [")
    for t, s, mods in p["structure"]:
        L.append(f"{i3}[")
        L.append(f"{i4}'title' => {ph(t)},")
        L.append(f"{i4}'subtitle' => {ph(s)},")
        L.append(f"{i4}'modules' => [")
        L += [f"{i4}    {ph(m)}," for m in mods]
        L.append(f"{i4}],")
        L.append(f"{i3}],")
    L.append(f"{i2}],")
    L.append(f"{i2}'gcc_heading' => {ph(p['gcc_heading'])},")
    L.append(f"{i2}'gcc' => [")
    for g in p["gcc"]:
        L.append(f"{i3}['icon' => {ph(g['icon'])}, 'title' => {ph(g['title'])}, 'text' => {ph(g['text'])}],")
    L.append(f"{i2}],")
    L.append(f"{i2}'faqs' => [")
    L += [f"{i3}[{ph(q)}, {ph(a)}, {n}]," for q, a, n in p["faqs"]]
    L.append(f"{i2}],")
    L.append(f"{i2}'meta_title' => {ph(p['meta_title'])},")
    L.append(f"{i2}'meta_description' => {ph(p['meta_description'])},")
    L.append(ind + "],")
    return "\n".join(L)


def build_one(cfg):
    src = os.path.join(ROOT, "output/programs", cfg["src"])
    out_path = os.path.join(ROOT, "database/seeders/data", cfg["out"])

    files = sorted(glob.glob(os.path.join(src, "*/*.md")))
    if not files:
        sys.exit("No approved content found in " + src)
    progs = [build(f) for f in files]
    progs.sort(key=lambda x: x["sort_order"])

    out = ["<?php", "", "// GENERATED FILE \u2014 do not edit by hand.",
           f"// Source: output/programs/{cfg['src']}/**.md",
           "// Rebuild: python3 tools/build_catalog_data.py", "", "return [",
           "    'categories' => ["]
    for s_, n, so in cfg["categories"]:
        out.append(f"        ['slug' => {ph(s_)}, 'name' => {ph(n)}, 'sort_order' => {so}],")
    out += ["    ],", "    'universities' => ["]
    for s_, n, c, cc, so in cfg["universities"]:
        out.append(f"        ['slug' => {ph(s_)}, 'name' => {ph(n)}, 'country' => {ph(c)}, "
                   f"'country_code' => {ph(cc)}, 'sort_order' => {so}],")
    out += ["    ],", "    'programs' => ["]
    out += [emit(p) for p in progs]
    out += ["    ],", "];", ""]

    os.makedirs(os.path.dirname(out_path), exist_ok=True)
    open(out_path, "w", encoding="utf-8").write("\n".join(out))
    return out_path, progs


def main():
    for cfg in BUILDS:
        out_path, progs = build_one(cfg)
        print(f"Wrote {out_path}")
        for p in progs:
            print(f"  {p['sort_order']}  {p['slug']:58} "
                  f"hl={len(p['highlights'])} sn={len(p['snapshot'])} bn={len(p['benefits'])} "
                  f"lr={len(p['learning'])} ca={len(p['careers'])} st={len(p['structure'])} "
                  f"su={len(p['support'])} gc={len(p['gcc'])} fe={len(p['fees'])} fq={len(p['faqs'])}")


if __name__ == "__main__":
    main()

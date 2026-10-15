#!/usr/bin/env python3
"""
Validate the generated catalogue data files WITHOUT PHP.

Parses the PHP array literally enough to check the contract the seeder
relies on, plus the template's hard limits. Also checks that every Bachelor
seed slug/title matches a Completed tracker row; incomplete research cannot
be promoted just by adding a markdown file. Run before every seed.

    python3 tools/validate_catalog_data.py

Exit code 0 = structural checks passed (server DB prerequisites still required).
Exit code 1 = do not seed.
"""
import csv
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA_DIR = os.path.join(ROOT, "database/seeders/data")
TRACKER = os.path.join(ROOT, "docs/program-tracker.csv")
# Match the three files loaded by CatalogProgramsSeeder. A missing deployment
# must fail, rather than silently validating only the files that arrived.
EXPECTED = ("doctorate_programs.php", "diploma_programs.php", "bachelors_programs.php")
FILES = [os.path.join(DATA_DIR, name) for name in EXPECTED]

REQUIRED = ["slug", "title", "level", "category_slug", "university_slug", "duration",
            "sort_order", "hero", "overview", "highlights", "snapshot", "benefits",
            "learning", "careers", "structure", "support", "gcc_heading", "gcc",
            "fees", "meta_title", "meta_description"]

# field -> (min, max) counts, from docs/program-content-template.md
COUNTS = {"highlights": (4, 8), "snapshot": (6, 8), "benefits": (4, 8),
          "learning": (6, 12), "support": (4, 8), "fees": (3, 6),
          "gcc": (0, 8), "careers": (1, 20), "overview": (2, 3)}

LIMITS = {"hero": (0, 300), "meta_title": (45, 78), "meta_description": (120, 161)}

def php_strings(blob):
    """All single-quoted PHP strings in a blob, unescaped."""
    return [m.group(1).replace("\\'", "'").replace("\\\\", "\\")
            for m in re.finditer(r"'((?:[^'\\]|\\.)*)'", blob)]


def split_programs(text):
    """Yield the raw text of each top-level entry in 'programs' => [ ... ]."""
    start = text.index("'programs' => [")
    i = text.index("[", start)
    depth, buf, out, inprog = 0, [], [], False
    for ch in text[i:]:
        if ch == "[":
            depth += 1
            if depth == 2:
                inprog, buf = True, []
                continue
        elif ch == "]":
            depth -= 1
            if depth == 1 and inprog:
                out.append("".join(buf))
                inprog = False
                continue
            if depth == 0:
                break
        if inprog:
            buf.append(ch)
    return out


def field_blob(prog, key):
    m = re.search(rf"'{key}' => (\[.*?\n            \]|'(?:[^'\\]|\\.)*'|\d+|true|false)",
                  prog, re.S)
    return m.group(1) if m else None


def count_entries(blob):
    """Count top-level elements of a PHP array blob."""
    depth, n = 0, 0
    for idx, ch in enumerate(blob):
        if ch == "[":
            depth += 1
            if depth == 2:
                n += 1
        elif ch == "]":
            depth -= 1
    if n:
        return n
    # flat list of scalars
    return len(re.findall(r"^\s*'(?:[^'\\]|\\.)*',\s*$", blob, re.M))


def bachelor_tracker_approval():
    """Return approved Bachelor slug -> listing title, incomplete row IDs and errors.

    A Research Incomplete listing is not permission to seed a programme, even
    if a similarly named university page exists. The approved output filename
    is the slug used by the catalogue generator; require an actual page too.
    """
    approved, incomplete, errors = {}, [], []
    required = {"S.No", "Program Name (as in listing)", "Program Type",
                "Research Status", "Content Status", "Output File"}
    try:
        with open(TRACKER, encoding="utf-8-sig", newline="") as source:
            reader = csv.DictReader(source)
            if not reader.fieldnames or not required.issubset(reader.fieldnames):
                return approved, incomplete, ["Bachelor tracker: missing required CSV columns"]
            for row in reader:
                if any(row.get(column) is None for column in required):
                    errors.append("Bachelor tracker: malformed CSV row")
                    continue
                if row["Program Type"].strip() != "Bachelors":
                    continue
                number = row["S.No"].strip()
                output = row["Output File"].strip()
                content_status = row["Content Status"].strip()
                research_status = row["Research Status"].strip()
                if research_status.startswith("Incomplete"):
                    incomplete.append(number)
                    if content_status == "Completed" or output:
                        errors.append(f"Bachelor tracker {number}: incomplete research cannot have approved content")
                    continue
                if content_status != "Completed":
                    if output:
                        errors.append(f"Bachelor tracker {number}: non-approved row has an Output File")
                    continue
                if not research_status.startswith("Complete"):
                    errors.append(f"Bachelor tracker {number}: content Completed without complete research")
                    continue
                if not re.fullmatch(r"output/programs/bachelors/[a-z0-9-]+/[a-z0-9-]+\.md", output):
                    errors.append(f"Bachelor tracker {number}: invalid or missing approved Output File")
                    continue
                if not os.path.isfile(os.path.join(ROOT, output)):
                    errors.append(f"Bachelor tracker {number}: approved page is missing: {output}")
                    continue
                slug = os.path.basename(output)[:-3]
                if slug in approved:
                    errors.append(f"Bachelor tracker {number}: duplicate approved slug {slug}")
                approved[slug] = row["Program Name (as in listing)"].strip()
    except (OSError, UnicodeError, csv.Error) as exc:
        errors.append(f"Bachelor tracker: cannot read {TRACKER}: {exc}")
    return approved, incomplete, errors


def main():
    missing = [path for path in FILES if not os.path.isfile(path)]
    if missing:
        print("ERROR: missing required catalogue data file(s):")
        for path in missing:
            print("   ", path)
        return 1

    errors, warnings = [], []
    all_slugs, bachelor_seed_rows, total = {}, {}, 0

    for path in FILES:
        name = os.path.basename(path)
        with open(path, encoding="utf-8") as source:
            text = source.read()
        print(f"\n=== {name} ===")

        cat_slugs = set(re.findall(r"'slug' => '([^']+)'",
                                   text[text.index("'categories'"):text.index("'universities'")]))
        uni_slugs = set(re.findall(r"'slug' => '([^']+)'",
                                   text[text.index("'universities'"):text.index("'programs'")]))
        print(f"  categories declared : {sorted(cat_slugs)}")
        print(f"  universities declared: {sorted(uni_slugs)}")

        programs = split_programs(text)
        if not programs:
            errors.append(f"{name}: no programme rows — DO NOT SEED")
        for prog in programs:
            total += 1
            slug = (re.search(r"'slug' => '([^']+)'", prog) or [None, "?"])[1]
            if field_blob(prog, "is_active") != "false":
                errors.append(f"{slug}: new catalogue rows must start inactive")

            for key in REQUIRED:
                if f"'{key}' =>" not in prog:
                    errors.append(f"{slug}: missing required key '{key}'")

            cs = re.search(r"'category_slug' => '([^']+)'", prog)
            us = re.search(r"'university_slug' => '([^']+)'", prog)
            if cs and cs.group(1) not in cat_slugs:
                errors.append(f"{slug}: category_slug '{cs.group(1)}' not declared "
                              f"-> seeder would skip this row")
            if us and us.group(1) not in uni_slugs:
                errors.append(f"{slug}: university_slug '{us.group(1)}' not declared "
                              f"-> seeder would skip this row")

            # The single safe command can insert only researched Bachelor rows.
            # Track every row in the Bachelor file, including a miscategorised
            # one; it must not evade the tracker by changing category_slug.
            if name == "bachelors_programs.php":
                if not cs or cs.group(1) != "bachelors":
                    errors.append(f"{slug}: Bachelor seed has wrong category_slug")
                title = re.search(r"'title' => '((?:[^'\\]|\\.)*)'", prog)
                bachelor_seed_rows[slug] = (
                    title.group(1).replace("\\'", "'").replace("\\\\", "\\") if title else ""
                )
            elif cs and cs.group(1) == "bachelors":
                errors.append(f"{slug}: Bachelor row must be in bachelors_programs.php")

            if slug in all_slugs:
                errors.append(f"{slug}: DUPLICATE slug (also in {all_slugs[slug]})")
            all_slugs[slug] = name

            if not re.fullmatch(r"[a-z0-9]+(-[a-z0-9]+)*", slug):
                errors.append(f"{slug}: slug must be lowercase-hyphen")

            counts = {}
            for key, (lo, hi) in COUNTS.items():
                blob = field_blob(prog, key)
                if blob is None:
                    continue
                n = count_entries(blob)
                counts[key] = n
                if not (lo <= n <= hi):
                    warnings.append(f"{slug}: {key} has {n} (expected {lo}-{hi})")

            for key, (lo, hi) in LIMITS.items():
                m = re.search(rf"'{key}' => '((?:[^'\\]|\\.)*)'", prog)
                if not m:
                    continue
                val = m.group(1).replace("\\'", "'")
                if not (lo <= len(val) <= hi):
                    errors.append(f"{slug}: {key} is {len(val)} chars (expected {lo}-{hi})")

            # structure must have stages, each with modules
            st = field_blob(prog, "structure")
            if st and "'modules' =>" not in st:
                errors.append(f"{slug}: structure has no modules")

            # faqs optional but must be well-formed triples when present.
            # questions are VARCHAR(255); a merged heading shows up as HTML.
            fq = field_blob(prog, "faqs")
            nf = count_entries(fq) if fq else 0
            for question in php_strings(fq or "")[0::2]:
                if len(question) > 255 or "<" in question or "\n" in question:
                    errors.append(
                        f"{slug}: FAQ question is {len(question)} chars "
                        f"or contains HTML (column is VARCHAR 255)"
                    )

            print(f"  {slug:58} " + " ".join(f"{k}={v}" for k, v in counts.items()) + f" faqs={nf}")

    approved, incomplete, tracker_errors = bachelor_tracker_approval()
    errors.extend(tracker_errors)
    for slug in sorted(bachelor_seed_rows.keys() - approved.keys()):
        errors.append(f"{slug}: unapproved Bachelor seed slug (no Completed tracker row)")
    for slug in sorted(approved.keys() - bachelor_seed_rows.keys()):
        errors.append(f"{slug}: approved Bachelor missing from seed data")
    for slug in sorted(approved.keys() & bachelor_seed_rows.keys()):
        if bachelor_seed_rows[slug] != approved[slug]:
            errors.append(f"{slug}: Bachelor seed title differs from approved tracker title")

    print(f"\n--- {total} programme(s) across {len(FILES)} file(s) ---")
    print(f"Bachelor source gate: {len(approved)} approved, {len(incomplete)} incomplete, "
          f"{len(bachelor_seed_rows)} seeded (incomplete rows intentionally excluded).")
    if warnings:
        print(f"\nWARNING: {len(warnings)} declared content gap(s) (DB prerequisites still required):")
        for w in warnings:
            print("   ", w)
    if errors:
        print(f"\nERROR: {len(errors)} error(s) -- DO NOT SEED:")
        for e in errors:
            print("   ", e)
        return 1
    print("\nVALIDATION PASSED -- data checks passed; confirm DB prerequisites before seeding.")
    return 0


if __name__ == "__main__":
    sys.exit(main())

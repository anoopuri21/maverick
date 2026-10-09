#!/usr/bin/env python3
"""
Validate the generated catalogue data files WITHOUT PHP.

Parses the PHP array literally enough to check the contract the seeder
relies on, plus the template's hard limits. Run before every seed.

    python3 tools/validate_catalog_data.py

Exit code 0 = safe to seed. 1 = do not seed.
"""
import re
import os
import sys
import glob

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
FILES = sorted(glob.glob(os.path.join(ROOT, "database/seeders/data/*_programs.php")))
SKIP = {"master_programs.php"}

REQUIRED = ["slug", "title", "level", "category_slug", "university_slug", "duration",
            "sort_order", "hero", "overview", "highlights", "snapshot", "benefits",
            "learning", "careers", "structure", "support", "gcc_heading", "gcc",
            "fees", "meta_title", "meta_description"]

# field -> (min, max) counts, from docs/program-content-template.md
COUNTS = {"highlights": (4, 8), "snapshot": (6, 8), "benefits": (4, 8),
          "learning": (6, 12), "support": (4, 8), "fees": (3, 6),
          "gcc": (0, 8), "careers": (1, 20), "overview": (2, 3)}

LIMITS = {"hero": (0, 300), "meta_title": (45, 78), "meta_description": (120, 161)}

errors, warnings = [], []


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


def main():
    files = [f for f in FILES if os.path.basename(f) not in SKIP]
    if not files:
        print("No catalogue data files found.")
        return 0

    all_slugs, total = {}, 0

    for path in files:
        name = os.path.basename(path)
        text = open(path, encoding="utf-8").read()
        print(f"\n=== {name} ===")

        cats = set(php_strings(text[text.index("'categories'"):text.index("'universities'")])[0::1])
        cat_slugs = set(re.findall(r"'slug' => '([^']+)'",
                                   text[text.index("'categories'"):text.index("'universities'")]))
        uni_slugs = set(re.findall(r"'slug' => '([^']+)'",
                                   text[text.index("'universities'"):text.index("'programs'")]))
        print(f"  categories declared : {sorted(cat_slugs)}")
        print(f"  universities declared: {sorted(uni_slugs)}")

        for prog in split_programs(text):
            total += 1
            slug = (re.search(r"'slug' => '([^']+)'", prog) or [None, "?"])[1]

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

    print(f"\n--- {total} programme(s) across {len(files)} file(s) ---")
    if warnings:
        print(f"\nWARNING: {len(warnings)} warning(s) (declared gaps, seeding still safe):")
        for w in warnings:
            print("   ", w)
    if errors:
        print(f"\nERROR: {len(errors)} error(s) -- DO NOT SEED:")
        for e in errors:
            print("   ", e)
        return 1
    print("\nVALIDATION PASSED -- safe to seed.")
    return 0


if __name__ == "__main__":
    sys.exit(main())

#!/usr/bin/env python3
"""Cross-page duplicate-phrase scanner for output/programs content.

Extracts PROSE fields only (short_description, description, benefits desc column,
gcc_reasons text column, the whole faqs block), normalises them, and compares
8-word shingles pairwise across every page.

A shingle is classified FACTUAL (unavoidable boilerplate that must stay identical)
if it contains any of the FACTUAL_MARKERS below. Everything else is STYLISTIC and
should be rewritten.

Usage:  python3 tools/dedupe_scan.py [--list] [--n 8]
"""
import os
import re
import sys
from collections import defaultdict
from itertools import combinations

SRC = "output/programs"

FACTUAL_MARKERS = [
    # Rushford factual boilerplate
    "eight years", "five years", "180 ects", "20 000", "25 000", "45 000",
    "50 000", "5 000", "ielts", "toefl", "toeic", "pte", "duolingo",
    "recognition of prior experience", "medium of instruction", "dba", "phd",
    "bachelor s", "master s", "36", "native speakers", "english",
    # GAU factual boilerplate
    "yok", "yodak", "ecbe", "ssci", "sci expanded", "ahci", "ales",
    "21 credits", "7 courses", "seven courses", "30 ects", "20 ects", "10 ects",
    "3 00", "4 00", "55", "qualifying", "qualification", "institute of graduate",
    "girne american university", "scholarship", "semesters", "thesis",
]


def prose_blocks(text):
    """Yield the prose-only parts of a programme markdown file."""
    out = []

    m = re.search(r"## 2\.1 `short_description`\s*```(.*?)```", text, re.S)
    if m:
        out.append(m.group(1))

    m = re.search(r"## 2\.2 `description`\s*```html(.*?)```", text, re.S)
    if m:
        out.append(m.group(1))

    # benefits: 4th pipe-column (desc)
    m = re.search(r"## 3\.3 `benefits`(.*?)(?=\n## )", text, re.S)
    if m:
        for line in m.group(1).splitlines():
            cells = [c.strip() for c in line.split("|")]
            if len(cells) >= 5 and "---" not in line and cells[1] != "icon":
                out.append(cells[4])

    # gcc_reasons: 4th pipe-column (text)
    m = re.search(r"## 3\.9 `gcc_heading`(.*?)(?=\n## |\n---)", text, re.S)
    if m:
        for line in m.group(1).splitlines():
            cells = [c.strip() for c in line.split("|")]
            if len(cells) >= 5 and "---" not in line and cells[1] != "icon":
                out.append(cells[4])

    m = re.search(r"## 4\. `faqs`(.*?)(?=\n## 5\.)", text, re.S)
    if m:
        out.append(m.group(1))

    return out


def norm(s):
    s = re.sub(r"<[^>]+>", " ", s)
    s = s.lower()
    s = re.sub(r"[^a-z0-9]+", " ", s)
    return re.sub(r"\s+", " ", s).strip()


def shingles(words, n):
    return {" ".join(words[i:i + n]) for i in range(len(words) - n + 1)}


def main():
    n = 8
    if "--n" in sys.argv:
        n = int(sys.argv[sys.argv.index("--n") + 1])
    show = "--list" in sys.argv

    pages = {}
    for root, _dirs, files in os.walk(SRC):
        if os.path.relpath(root, SRC).count(os.sep) != 1:
            continue  # programme pages live at {category}/{university}/*.md
        for f in sorted(files):
            if not f.endswith(".md"):
                continue
            p = os.path.join(root, f)
            text = open(p, encoding="utf-8").read()
            words = norm(" ".join(prose_blocks(text))).split()
            pages[os.path.relpath(p, SRC)] = shingles(words, n)

    print(f"Scanned {len(pages)} pages, {n}-word shingles\n")

    owners = defaultdict(set)
    for name, sh in pages.items():
        for s in sh:
            owners[s].add(name)

    dupes = {s: v for s, v in owners.items() if len(v) > 1}
    factual, stylistic = [], []
    for s in dupes:
        (factual if any(mk in s for mk in FACTUAL_MARKERS) else stylistic).append(s)

    print(f"  duplicated shingles total : {len(dupes)}")
    print(f"  factual (acceptable)      : {len(factual)}")
    print(f"  STYLISTIC (fix these)     : {len(stylistic)}\n")

    if stylistic:
        print("STYLISTIC DUPLICATES")
        for s in sorted(stylistic):
            print(f"  - {s}")
            for pg in sorted(dupes[s]):
                print(f"      {pg}")
        print()

    if show:
        pairs = defaultdict(int)
        for a, b in combinations(sorted(pages), 2):
            k = len(pages[a] & pages[b])
            if k:
                pairs[(a, b)] = k
        print("TOP OVERLAPPING PAIRS")
        for (a, b), k in sorted(pairs.items(), key=lambda x: -x[1])[:15]:
            print(f"  {k:4}  {a}\n        {b}")

    return 1 if stylistic else 0


if __name__ == "__main__":
    sys.exit(main())

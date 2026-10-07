#!/usr/bin/env python3
"""Per-page QA gate for output/programs content.

Checks, per page:
  * 0 AI-tell phrases (after stripping official module/course names)
  * 0 Americanisms (after stripping official module/course names and quoted
    university text, which must be reproduced verbatim)
  * short_description  33-55 words AND <= 300 chars
  * description        110-195 words
  * meta_title         45-78 chars
  * meta_description   120-161 chars

SEO cells are read through build_catalog_data.seo_cell, never a naive regex --
a bare \\|(.*?)\\| truncates at escaped pipes inside the cell.

Usage:  python3 tools/qa_gate.py
Exit 0 = all pages pass.
"""
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from build_catalog_data import seo_cell  # noqa: E402

SRC = "output/programs"

AI_TELLS = [
    "in today's world", "in today s world", "in conclusion", "moreover",
    "furthermore", "it is important to note", "delve into", "navigate the",
    "in the realm of", "ever-evolving", "ever evolving", "landscape",
    "tapestry", "testament to", "unlock your potential", "embark on",
    "a game changer", "cutting-edge solutions", "seamlessly", "robust suite",
    "world-class", "state-of-the-art", "leverage", "harness the power",
]

AMERICANISMS = [
    "organization", "organizational", "analyze", "analyzing", "installment",
    "recognize", "center", "defense", "behavior", "specialize",
    # "program" but NOT programme / programming / programmer -- "programming"
    # is the correct British spelling and must not be flagged.
    r"program(?!m)",
    "practicing", "enrol" + "lment",
]

# Official names, codes and verbatim university quotations that MUST be
# reproduced exactly and therefore cannot be re-spelled into British English.
WHITELIST = [
    # Rushford official module / track names
    "The Landscape of Literature Review", "Specialization Track",
    "Cutting Edge Leadership", "Business Management Track", "Research Track",
    # GAU official course names (US spelling is the university's own)
    "Organizational Behavior", "Theory of Consumer Behavior",
    "Advanced Topics in Artificial Intelligence", "Advanced Programming",
    "Advanced Report Writing in Social Sciences",
    "Strategic Management and Business Analysis",
    "Advanced Human Resource Management", "Advanced Marketing Theories",
    "Advanced Marketing Theory", "Advanced Research Methods",
    "Advanced Management", "Integrated Marketing Communications",
    "Current Issues in Marketing", "Seminar in Marketing",
    "Seminar in Management", "Seminar in MIS",
    "Seminar in Tourism and Hospitality", "Advanced Topics in Software Systems",
    "Distributed Computing", "Advanced Statistics",
    "Project Evaluation and Management", "Theories of Final Decision Making",
    "Financial Markets and Instruments", "Monetary Theory and Policies",
    "Global Financial Management", "Departmental Elective",
    "Research Methods in Education", "Comparative Educational Administration",
    "Comparative Educational Supervision", "Analysis of Education Policies",
    "Supervision of Instruction",
    "New Trends and Approaches in Educational Supervision",
    "Qualitative Research Design and Analysis", "Free Elective Course",
    "Thesis Seminar", "Qualification Exam", "Leadership in Hotel Management",
    "Yield Management for Tourism Industry", "Service Operations Management",
    "Competitive Strategy for the Hospitality Industry",
    "Organizational Behaviour", "Advanced Report Writing and Social Science",
    # GAU official programme / body names
    "Educational Administration and Supervision PhD",
    "Public Health Management", "Public System Management",
    "Institute of Social & Applied Sciences", "Institute of Social Sciences",
    "Institute of Graduate Studies and Research",
    # Verbatim GAU quotations reproduced in the content
    "students are prepared for careers both in academics and the industry",
    "Students are prepared for careers both in academics and the industry",
    "programme listing", "Programme", "programme",
]

# AI-tell words that are legitimate when they are part of an official name.
AI_WHITELIST_CONTEXT = ["The Landscape of Literature Review"]

RANGES = {
    "short_description_words": (33, 55),
    "short_description_chars": (0, 300),
    "description_words": (110, 195),
    "meta_title": (45, 78),
    "meta_description": (120, 161),
}


def body_only(text):
    """Drop YAML front-matter and the internal audit-trail sections.

    Front-matter keys (program_name, program_type) and the Humanizer /
    QA changelogs are not published prose -- the changelogs deliberately
    QUOTE banned words in order to record that they were removed.
    """
    text = re.sub(r"\A---\n.*?\n---\n", "\n", text, flags=re.S)
    text = re.split(r"\n## 6\. QA ", text)[0]
    return text


def strip_whitelist(text):
    for w in sorted(WHITELIST + AI_WHITELIST_CONTEXT, key=len, reverse=True):
        text = text.replace(w, " ")
        text = text.replace(w.lower(), " ")
    return text


def main():
    rows, failures = [], 0

    paths = []
    for root, _d, files in os.walk(SRC):
        if os.path.relpath(root, SRC).count(os.sep) != 1:
            continue  # programme pages live at {category}/{university}/*.md
        paths += [os.path.join(root, f) for f in sorted(files) if f.endswith(".md")]

    for p in sorted(paths):
        t = body_only(open(p, encoding="utf-8").read())
        name = os.path.basename(p)
        errs = []

        clean = strip_whitelist(t)
        low = clean.lower()

        tells = [a for a in AI_TELLS if a in low]
        if tells:
            errs.append(f"AI-tells {tells}")

        ams = [a for a in AMERICANISMS if re.search(rf"\b{a}", low)]
        if ams:
            errs.append(f"Americanisms {ams}")

        m = re.search(r"## 2\.1 `short_description`.*?\n```(.*?)```", t, re.S)
        sd = m.group(1).strip() if m else ""
        sw, sc = len(sd.split()), len(sd)
        lo, hi = RANGES["short_description_words"]
        if not (lo <= sw <= hi):
            errs.append(f"short_desc words {sw} not in {lo}-{hi}")
        if sc > RANGES["short_description_chars"][1]:
            errs.append(f"short_desc chars {sc} > 300")

        m = re.search(r"## 2\.2 `description`.*?\n```html(.*?)```", t, re.S)
        d = re.sub(r"<[^>]+>", " ", m.group(1)) if m else ""
        dw = len(d.split())
        lo, hi = RANGES["description_words"]
        if not (lo <= dw <= hi):
            errs.append(f"description words {dw} not in {lo}-{hi}")

        mt = seo_cell(t, "meta_title") or ""
        md = seo_cell(t, "meta_description") or ""
        for key, val in (("meta_title", mt), ("meta_description", md)):
            lo, hi = RANGES[key]
            if not (lo <= len(val) <= hi):
                errs.append(f"{key} {len(val)} not in {lo}-{hi}")

        rows.append((name, sw, sc, dw, len(mt), len(md), errs))
        if errs:
            failures += 1

    print(f"{'page':66} {'sdW':>4}{'sdC':>5}{'dW':>5}{'mT':>4}{'mD':>5}  verdict")
    print("-" * 104)
    for name, sw, sc, dw, mt, md, errs in rows:
        verdict = "PASS" if not errs else "FAIL"
        print(f"{name[:66]:66} {sw:4}{sc:5}{dw:5}{mt:4}{md:5}  {verdict}")
        for e in errs:
            print(f"{'':66} {'':18}  -> {e}")

    print("-" * 104)
    if failures:
        print(f"QA GATE: FAIL  ({failures} of {len(rows)} pages)")
        return 1
    print(f"QA GATE: PASS  ({len(rows)}/{len(rows)} pages)")
    return 0


if __name__ == "__main__":
    sys.exit(main())

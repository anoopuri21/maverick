#!/usr/bin/env python3
"""
Regenerate output/programs/INDEX.md from docs/program-tracker.csv.

The tracker is the bookkeeping source of truth; output/ holds the content.
Run this after any tracker status change so the index never drifts.

    python3 tools/build_index.py
"""
import csv
import io
import os
import re
import datetime

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TRACKER = os.path.join(ROOT, "docs/program-tracker.csv")
OUT = os.path.join(ROOT, "output/programs/INDEX.md")


def read_tracker():
    raw = open(TRACKER, encoding="utf-8-sig", newline="").read()
    rows = list(csv.reader(io.StringIO(raw)))
    head = rows[0]
    return [dict(zip(head, r)) for r in rows[1:] if r and r[0].strip()]


def main():
    recs = read_tracker()

    approved = [r for r in recs if r["Content Status"] == "Completed"]
    approved.sort(key=lambda r: int(r["S.No"]))
    incomplete = [r for r in recs if r["Research Status"].startswith("Incomplete")]
    incomplete.sort(key=lambda r: int(r["S.No"]))
    pending = [r for r in recs if r["Content Status"] == "Pending"]

    L = [
        "# Approved Programme Content — Index",
        "",
        f"_Last updated: {datetime.date.today().isoformat()}_",
        "",
        "Generated from `docs/program-tracker.csv` by `tools/build_index.py`. `output/` is the",
        "single source of truth for page content; the PHP files under",
        "`database/seeders/data/` are generated from it by `tools/build_catalog_data.py`",
        "and must never be hand-edited.",
        "",
        "## Approved and ready to seed",
        "",
        "| S.No | Programme | Type | University | Slug | QA | File |",
        "|---|---|---|---|---|---|---|",
    ]
    for r in approved:
        path = r["Output File"]
        rel = path.replace("output/programs/", "")
        slug = os.path.splitext(os.path.basename(path))[0]
        L.append(
            f"| {r['S.No']} | {r['Program Name (as in listing)']} | {r['Program Type']} | "
            f"{r['University Name (as in listing)']} | `{slug}` | {r['QA Status']} | "
            f"[`{os.path.basename(path)}`]({rel}) |"
        )

    by_type = {}
    for r in approved:
        by_type[r["Program Type"]] = by_type.get(r["Program Type"], 0) + 1
    mix = ", ".join(f"{v} {k}" for k, v in sorted(by_type.items()))

    L += [
        "",
        f"**{len(approved)} programmes approved** ({mix}). All carry `is_active: false` —",
        "flip to true in the admin panel after visual QA.",
        "",
        "## Recorded as Incomplete — deliberately not drafted",
        "",
        "| S.No | Programme | University | Reason |",
        "|---|---|---|---|",
    ]
    for r in incomplete:
        reason = re.sub(r"^Incomplete\s*[–-]\s*", "", r["Research Status"]).strip()
        L.append(
            f"| {r['S.No']} | {r['Program Name (as in listing)']} | "
            f"{r['University Name (as in listing)']} | {reason} |"
        )

    L += ["", "## Pending", ""]
    if pending:
        nos = sorted(int(r["S.No"]) for r in pending)
        bodies = sorted({r["University Name (as in listing)"] for r in pending})
        L.append(
            f"**{len(pending)} programmes** still awaiting content "
            f"(S.No {nos[0]}–{nos[-1]}), awarded by: {', '.join(bodies)}."
        )
    else:
        L.append("None — every tracker row has been resolved.")
    L.append("")

    open(OUT, "w", encoding="utf-8").write("\n".join(L))
    print(f"Wrote {OUT}")
    print(f"  approved={len(approved)} incomplete={len(incomplete)} pending={len(pending)}")


if __name__ == "__main__":
    main()

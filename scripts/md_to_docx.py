#!/usr/bin/env python3
"""
Tiny markdown-to-DOCX renderer for internal Maverick deliverables.
Editable output: plain Word styles, charts/tables as real Word tables.

Supports: # title, ## h1, ### h2, - bullets, | tables, ``` code fences,
paragraphs, **bold**, *italic*.

Usage:
    python3 scripts/md_to_docx.py <input.md> <output.docx>
"""
import re
import sys

from docx import Document
from docx.shared import Pt, RGBColor, Cm
from docx.oxml.ns import qn

NAVY = RGBColor(0x07, 0x14, 0x44)
RED = RGBColor(0xB2, 0x02, 0x02)
INK = RGBColor(0x1C, 0x1E, 0x26)
GREY = RGBColor(0x5A, 0x60, 0x70)


def set_base(doc):
    n = doc.styles["Normal"]
    n.font.name = "Calibri"
    n.font.size = Pt(10.5)
    n.font.color.rgb = INK
    n.paragraph_format.space_after = Pt(6)
    n.paragraph_format.line_spacing = 1.25
    for name, size, before in (("Heading 1", 14, 14), ("Heading 2", 12, 10), ("Heading 3", 11, 8)):
        h = doc.styles[name]
        h.font.name = "Calibri"
        h.font.size = Pt(size)
        h.font.bold = True
        h.font.color.rgb = NAVY
        h.paragraph_format.space_before = Pt(before)
        h.paragraph_format.space_after = Pt(5)


def add_runs(p, text, base_size=None, base_color=None):
    """Split markdown inline (**bold**, *italic*) into runs."""
    parts = re.split(r"(\*\*[^*]+\*\*|\*[^*]+\*)", text)
    for part in parts:
        if not part:
            continue
        r = p.add_run()
        if part.startswith("**"):
            r.text = part[2:-2]
            r.font.bold = True
            r.font.color.rgb = NAVY
        elif part.startswith("*"):
            r.text = part[1:-1]
            r.font.italic = True
        else:
            r.text = part
        if base_size:
            r.font.size = Pt(base_size)
        if base_color and not (part.startswith("**")):
            r.font.color.rgb = base_color


def add_table(doc, rows):
    ncols = max(len(r) for r in rows)
    t = doc.add_table(rows=len(rows), cols=ncols)
    t.style = "Table Grid"
    for i, row in enumerate(rows):
        for j in range(ncols):
            cell = t.rows[i].cells[j]
            cell.text = ""
            p = cell.paragraphs[0]
            p.paragraph_format.space_after = Pt(2)
            add_runs(p, row[j] if j < len(row) else "",
                     base_size=9, base_color=(NAVY if i == 0 else INK))
            if i == 0:
                for r in p.runs:
                    r.font.bold = True


def parse_table(lines):
    rows = []
    for ln in lines:
        cells = [c.strip() for c in ln.strip().strip("|").split("|")]
        if all(re.fullmatch(r":?-{2,}:?", c) for c in cells if c):
            continue
        rows.append(cells)
    return rows


def build(md_path, out_path):
    doc = Document()
    for s in doc.sections:
        s.top_margin = Cm(2.0)
        s.bottom_margin = Cm(2.0)
        s.left_margin = Cm(2.1)
        s.right_margin = Cm(2.1)
    set_base(doc)

    para_buf, table_buf, pre_buf = [], [], []
    in_pre = False

    def flush_para():
        if para_buf:
            p = doc.add_paragraph()
            add_runs(p, " ".join(para_buf))
            para_buf.clear()

    def flush_table():
        if table_buf:
            add_table(doc, parse_table(table_buf))
            doc.add_paragraph().paragraph_format.space_after = Pt(2)
            table_buf.clear()

    for ln in open(md_path, encoding="utf-8").read().splitlines():
        s = ln.rstrip()
        if s.startswith("```"):
            if in_pre:
                p = doc.add_paragraph()
                r = p.add_run("\n".join(pre_buf))
                r.font.name = "Consolas"
                r.font.size = Pt(8.5)
                pre_buf.clear()
            in_pre = not in_pre
            continue
        if in_pre:
            pre_buf.append(ln)
            continue
        if s.startswith("|") and s.strip().endswith("|"):
            flush_para()
            table_buf.append(s)
            continue
        flush_table()
        if s.startswith("# "):
            flush_para()
            p = doc.add_paragraph()
            r = p.add_run(s[2:])
            r.font.bold = True
            r.font.size = Pt(19)
            r.font.color.rgb = NAVY
        elif s.startswith("### "):
            flush_para()
            p = doc.add_paragraph()
            p.style = doc.styles["Heading 3"]
            add_runs(p, s[4:])
        elif s.startswith("## "):
            flush_para()
            p = doc.add_paragraph()
            p.style = doc.styles["Heading 1"]
            add_runs(p, s[3:])
        elif s.startswith("- "):
            flush_para()
            p = doc.add_paragraph(style="List Bullet")
            add_runs(p, s[2:])
            p.paragraph_format.space_after = Pt(4)
        elif re.match(r"^\d+\.\s", s):
            flush_para()
            num, rest = s.split(". ", 1)
            p = doc.add_paragraph()
            p.paragraph_format.left_indent = Cm(0.8)
            p.paragraph_format.space_after = Pt(2)
            r = p.add_run(num + ".  ")
            r.font.bold = True
            r.font.color.rgb = RED
            add_runs(p, rest)
        elif s.strip() == "":
            flush_para()
        else:
            para_buf.append(s)
    flush_para()
    flush_table()
    doc.save(out_path)
    print("wrote", out_path)


if __name__ == "__main__":
    build(sys.argv[1], sys.argv[2])

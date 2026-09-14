#!/usr/bin/env python3
"""
Tiny markdown-to-PDF renderer for internal Maverick deliverables.

Supports: # title, ## h1, ### h2, - bullets, | tables, ``` code fences,
paragraphs, **bold**, *italic*. Professional Helvetica styling, brand colours,
no header/footer lines.

Usage:
    python3 scripts/md_to_pdf.py <input.md> <output.pdf> [Doc Title]
"""
import re
import sys

from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.lib.colors import HexColor
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.styles import ParagraphStyle
from reportlab.platypus import (BaseDocTemplate, PageTemplate, Frame, Paragraph, Spacer,
                                Table, TableStyle, Preformatted)

NAVY = HexColor("#071444")
RED = HexColor("#b20202")
INK = HexColor("#1c1e26")
GREY = HexColor("#5a6070")
LINE = HexColor("#c8cdd8")
PAPER = HexColor("#f5f0eb")

PAGE_W, PAGE_H = A4
ML = MR = 20 * mm
MT = MB = 20 * mm


def S(name, **kw):
    base = dict(fontName="Helvetica", fontSize=10, leading=14.5, textColor=INK,
                alignment=TA_LEFT, spaceAfter=6)
    base.update(kw)
    return ParagraphStyle(name, **base)


st_title = S("title", fontName="Helvetica-Bold", fontSize=19, leading=24, textColor=NAVY, spaceAfter=4)
st_h1 = S("h1", fontName="Helvetica-Bold", fontSize=13, leading=17, textColor=NAVY, spaceBefore=14, spaceAfter=6)
st_h2 = S("h2", fontName="Helvetica-Bold", fontSize=11, leading=15, textColor=NAVY, spaceBefore=10, spaceAfter=4)
st_body = S("body")
st_bullet = S("bullet", leftIndent=12, bulletIndent=2, spaceAfter=4)
st_pre = S("pre", fontName="Courier", fontSize=8, leading=11, textColor=INK, spaceAfter=8)


def inline(t):
    t = t.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")
    t = re.sub(r"\*\*([^*]+)\*\*", r"<b>\1</b>", t)
    t = re.sub(r"(?<!\*)\*([^*]+)\*(?!\*)", r"<i>\1</i>", t)
    return t


def parse_table(lines):
    rows = []
    for ln in lines:
        cells = [c.strip() for c in ln.strip().strip("|").split("|")]
        if all(re.fullmatch(r":?-{2,}:?", c) for c in cells if c):
            continue
        rows.append(cells)
    return rows


def table_flowable(rows):
    ncols = max(len(r) for r in rows)
    width = PAGE_W - ML - MR
    data = []
    for r in rows:
        cells = r + [""] * (ncols - len(r))
        data.append([Paragraph(inline(c), S(f"c{len(data)}_{i}", fontSize=8.6, leading=12,
                       fontName="Helvetica-Bold" if len(data) == 0 else "Helvetica",
                       textColor=NAVY if len(data) == 0 else INK, spaceAfter=0))
                     for i, c in enumerate(cells)])
    t = Table(data, colWidths=[width / ncols] * ncols, hAlign="LEFT")
    t.setStyle(TableStyle([
        ("LINEBELOW", (0, 0), (-1, 0), 1.2, RED),
        ("LINEBELOW", (0, 1), (-1, -1), 0.5, LINE),
        ("ROWBACKGROUNDS", (0, 1), (-1, -1), [HexColor("#ffffff"), PAPER]),
        ("TOPPADDING", (0, 0), (-1, -1), 4),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 4),
        ("LEFTPADDING", (0, 0), (-1, -1), 5),
        ("RIGHTPADDING", (0, 0), (-1, -1), 5),
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
    ]))
    return t


def build(md_path, out_path, doc_title):
    lines = open(md_path, encoding="utf-8").read().splitlines()
    st = []
    para_buf = []
    table_buf = []
    pre_buf = []
    in_pre = False

    def flush_para():
        if para_buf:
            st.append(Paragraph(inline(" ".join(para_buf)), st_body))
            para_buf.clear()

    def flush_table():
        if table_buf:
            st.append(table_flowable(parse_table(table_buf)))
            st.append(Spacer(1, 4))
            table_buf.clear()

    for ln in lines:
        s = ln.rstrip()
        if s.startswith("```"):
            if in_pre:
                st.append(Preformatted("\n".join(pre_buf), st_pre))
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
            st.append(Paragraph(inline(s[2:]), st_title))
        elif s.startswith("### "):
            flush_para()
            st.append(Paragraph(inline(s[4:]), st_h2))
        elif s.startswith("## "):
            flush_para()
            st.append(Paragraph(inline(s[3:]), st_h1))
        elif s.startswith("- "):
            flush_para()
            st.append(Paragraph(f'<bullet><font color="#b20202">\u25aa</font></bullet>'
                                + inline(s[2:]), st_bullet))
        elif s.strip() == "":
            flush_para()
        else:
            para_buf.append(s)
    flush_para()
    flush_table()

    frame = Frame(ML, MB, PAGE_W - ML - MR, PAGE_H - MT - MB, id="main")
    doc = BaseDocTemplate(out_path, pagesize=A4, leftMargin=ML, rightMargin=MR,
                          topMargin=MT, bottomMargin=MB, title=doc_title,
                          author="Maverick Business Academy")
    doc.addPageTemplates([PageTemplate(id="plain", frames=[frame])])
    doc.build(st)
    print("wrote", out_path)


if __name__ == "__main__":
    md, out = sys.argv[1], sys.argv[2]
    title = sys.argv[3] if len(sys.argv) > 3 else "Maverick Business Academy"
    build(md, out, title)

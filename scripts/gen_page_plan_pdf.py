"""PDF renderer for the Bachelors page plan (reportlab, Helvetica, brand colours)."""
import math
import gen_page_plan_keywords as C

from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.lib.colors import HexColor
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.styles import ParagraphStyle
from reportlab.platypus import (BaseDocTemplate, PageTemplate, Frame, Paragraph, Spacer,
                                Table, TableStyle, KeepTogether)
from reportlab.graphics.shapes import Drawing, Rect, String, Line, Polygon

OUT = "landing-page/bachelors/05-page-plan-keywords.pdf"

NAVY = HexColor("#" + C.NAVY)
RED = HexColor("#" + C.RED)
INK = HexColor("#" + C.INK)
GREY = HexColor("#" + C.GREY)
PAPER = HexColor("#" + C.PAPER)
TINT = HexColor("#" + C.TINT)
LIGHT = HexColor("#c8cdd8")

PAGE_W, PAGE_H = A4
ML = MR = 21 * mm
MT = MB = 20 * mm

# ------------------------------------------------------------------ styles
def S(name, **kw):
    base = dict(fontName="Helvetica", fontSize=10.5, leading=15, textColor=INK,
                alignment=TA_LEFT, spaceAfter=7)
    base.update(kw)
    return ParagraphStyle(name, **base)

st_title = S("title", fontName="Helvetica-Bold", fontSize=21, leading=26, textColor=NAVY, spaceAfter=2)
st_title2 = S("title2", fontName="Helvetica-Bold", fontSize=14.5, leading=19, textColor=RED, spaceAfter=10)
st_org = S("org", fontSize=8.5, leading=12, textColor=GREY, spaceAfter=14)
st_sub = S("sub", fontSize=10.5, leading=15.5, textColor=INK, spaceAfter=16)
st_h1 = S("h1", fontName="Helvetica-Bold", fontSize=13.5, leading=17.5, textColor=NAVY,
          spaceBefore=16, spaceAfter=7)
st_h2 = S("h2", fontName="Helvetica-Bold", fontSize=11, leading=15, textColor=NAVY,
          spaceBefore=10, spaceAfter=4)
st_body = S("body")
st_bullet = S("bullet", leftIndent=12, bulletIndent=2, spaceAfter=5)
st_route_t = S("routet", fontName="Helvetica-Bold", fontSize=10.5, leading=15, textColor=NAVY, spaceAfter=2)
st_route_b = S("routeb", spaceAfter=9, leftIndent=0)
st_blk_name = S("blkname", fontName="Helvetica-Bold", fontSize=10.5, leading=14.5, textColor=NAVY, spaceAfter=2)
st_blk_cat = S("blkcat", fontName="Helvetica-Bold", fontSize=7.5, leading=11, textColor=GREY, spaceAfter=2)
st_blk_head = S("blkhead", fontSize=10, leading=14.5, spaceAfter=3)
st_blk_desc = S("blkdesc", fontName="Helvetica-Oblique", fontSize=9.5, leading=13.5, textColor=HexColor("#3c4150"),
                spaceAfter=3)
st_blk_scope = S("blkscope", fontSize=10, leading=14.5, spaceAfter=10)
st_chart_cap = S("chartcap", fontName="Helvetica-Bold", fontSize=9.5, leading=13, textColor=NAVY, spaceAfter=4)
st_chart_src = S("charts", fontSize=8, leading=11.5, textColor=GREY, spaceAfter=0)
st_contents = S("contents", fontSize=10.5, leading=16.5, leftIndent=14, spaceAfter=0)
st_kentry = S("kentry", fontName="Helvetica-Bold", fontSize=10.5, leading=15, textColor=NAVY, spaceAfter=2)
st_kbody = S("kbody", fontSize=10, leading=14.5, spaceAfter=8)


def esc(t):
    return t.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")


def b(text):
    return f'<font name="Helvetica-Bold">{esc(text)}</font>'


# ------------------------------------------------------------------ charts
def pie_drawing(chart, size=104):
    """Pie slices drawn as arc polygons (clockwise from 12 o'clock). Deterministic."""
    d = Drawing(470, 120)
    cx, cy, r = 66, 60, 50
    angle = 90.0
    for label, pct, col in chart["slices"]:
        sweep = 360.0 * pct / 100.0
        a0, a1 = angle, angle - sweep
        steps = max(int(sweep / 4), 8)
        pts = [cx, cy]
        for i in range(steps + 1):
            a = math.radians(a0 + (a1 - a0) * i / steps)
            pts += [cx + r * math.cos(a), cy + r * math.sin(a)]
        d.add(Polygon(pts, fillColor=HexColor("#" + col),
                      strokeColor=HexColor("#ffffff"), strokeWidth=1.2))
        angle = a1
    # legend
    y = 106
    for label, pct, col in chart["slices"]:
        d.add(Rect(140, y - 9, 10, 10, fillColor=HexColor("#" + col), strokeColor=None))
        d.add(String(156, y - 6, label, fontName="Helvetica", fontSize=9.3, fillColor=INK))
        y -= 21
    return d


def bar_drawing(chart):
    d = Drawing(470, 132)
    left, right = 178, 430
    top, row_h = 120, 28
    scale = (right - left) / 200.0
    # gridlines + scale
    for v in (0, 50, 100, 150, 200):
        x = left + v * scale
        d.add(Line(x, 8, x, top + 2, strokeColor=HexColor("#dfe3ea"), strokeWidth=0.7))
        d.add(String(x - 7, -2, f"{v}k", fontName="Helvetica", fontSize=7.5, fillColor=GREY))
    y = top
    for label, lo, hi in chart["bars"]:
        d.add(String(2, y + 1, label, fontName="Helvetica", fontSize=9, fillColor=INK))
        if lo is None:
            d.add(Rect(left, y - 4, right - left, 15, fillColor=None,
                       strokeColor=HexColor("#" + C.RED), strokeWidth=1, strokeDashArray=[3, 2]))
            d.add(String(left + 8, y, "added once your fees are confirmed",
                         fontName="Helvetica-Oblique", fontSize=8.5, fillColor=HexColor("#" + C.RED)))
        else:
            x0 = left + lo * scale
            w = max((hi - lo) * scale, 3)
            d.add(Rect(x0, y - 4, w, 15, fillColor=NAVY, strokeColor=None))
            txt = f"AED {lo:g}k\u2013{hi:g}k" if hi != lo else f"AED {lo:g}k"
            d.add(String(x0 + w + 6, y, txt, fontName="Helvetica-Bold", fontSize=8.5, fillColor=NAVY))
        y -= row_h
    return d


def chart_block(chart):
    inner = []
    inner.append(Paragraph(esc(chart["caption"]), st_chart_cap))
    inner.append(pie_drawing(chart) if chart["kind"] == "pie" else bar_drawing(chart))
    inner.append(Spacer(1, 3))
    inner.append(Paragraph(esc(chart["source"]), st_chart_src))
    t = Table([[inner]], colWidths=[PAGE_W - ML - MR - 16])
    t.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, -1), PAPER),
        ("LEFTPADDING", (0, 0), (-1, -1), 12),
        ("RIGHTPADDING", (0, 0), (-1, -1), 12),
        ("TOPPADDING", (0, 0), (-1, -1), 10),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 10),
        ("LINEBELOW", (0, 0), (-1, -1), 0, PAPER),
    ]))
    return t


# ------------------------------------------------------------------ story
def story():
    st = []
    # title block
    st.append(Paragraph(esc(C.TITLE_1), st_title))
    st.append(Paragraph(esc(C.TITLE_2), st_title2))
    rule = Table([[""]], colWidths=[PAGE_W - ML - MR], rowHeights=[2.2])
    rule.setStyle(TableStyle([("BACKGROUND", (0, 0), (-1, -1), RED)]))
    st.append(rule)
    st.append(Spacer(1, 8))
    st.append(Paragraph(esc(C.ORG_LINE), st_org))
    st.append(Paragraph(esc(C.SUBTITLE), st_sub))

    # contents
    st.append(Paragraph("What is inside", st_h1))
    for i, line in enumerate(C.CONTENTS, 1):
        st.append(Paragraph(f'<bullet><font color="#{C.RED}">\u25aa</font></bullet>{i}.&nbsp;&nbsp;{esc(line)}',
                            st_contents))
    st.append(Spacer(1, 6))

    # 1. the page in one minute
    st.append(Paragraph("1. The page in one minute", st_h1))
    st.append(Paragraph(esc(C.S1_INTRO), st_body))
    st.append(Paragraph("The page offers four study routes, all from our own catalogue.", st_body))
    for title, body in C.ROUTES:
        st.append(Paragraph(esc(title), st_route_t))
        st.append(Paragraph(esc(body), st_route_b))
    st.append(Paragraph(esc(C.S1_OUTRO), st_body))

    # 2. who it is for
    st.append(Paragraph("2. Who the page is for", st_h1))
    st.append(Paragraph(esc(C.S2_INTRO), st_body))
    for title, body in C.PERSONAS:
        st.append(Paragraph(esc(title), st_route_t))
        st.append(Paragraph(esc(body), st_route_b))

    # 3. market
    st.append(Paragraph("3. The market at a glance", st_h1))
    st.append(Paragraph(esc(C.S3_INTRO), st_body))
    for _, body in C.S3_BULLETS:
        st.append(Paragraph(f'<bullet><font color="#{C.RED}">\u25aa</font></bullet>{esc(body)}', st_bullet))

    # 4. promises
    st.append(Paragraph("4. What the page promises", st_h1))
    st.append(Paragraph(esc(C.S4_INTRO), st_body))
    for i, (lead, body) in enumerate(C.PROMISES, 1):
        st.append(Paragraph(f'<bullet><font name="Helvetica-Bold" color="#{C.RED}">{i}.</font></bullet>'
                            f'{b(lead)} {esc(body)}', st_bullet))

    # 5. fifteen blocks
    st.append(Paragraph("5. The page, section by section", st_h1))
    st.append(Paragraph(esc(C.S5_INTRO), st_body))
    for name, cat, heading, desc, scope, chart in C.BLOCKS:
        blk = [Paragraph(esc(name), st_blk_name),
               Paragraph(f"CATEGORY:&nbsp;&nbsp;{esc(cat)}", st_blk_cat),
               Paragraph(f'<font name="Helvetica-Bold" color="#{C.NAVY}">Heading:</font> '
                         f'\u201c{esc(heading)}\u201d', st_blk_head),
               Paragraph(esc(desc), st_blk_desc),
               Paragraph(esc(scope), st_blk_scope)]
        if chart:
            blk.append(chart_block(chart))
            blk.append(Spacer(1, 10))
        st.append(KeepTogether(blk))

    # 6. reach
    st.append(Paragraph("6. How the page will be found online", st_h1))
    st.append(Paragraph(esc(C.S6_INTRO), st_body))
    for title, body in C.S6_PARTS:
        st.append(Paragraph(esc(title), st_h2))
        st.append(Paragraph(esc(body), st_body))

    # 7. keywords
    st.append(Paragraph("7. The search phrases (keywords)", st_h1))
    st.append(Paragraph(esc(C.S7_INTRO), st_body))
    st.append(Paragraph("Tier 1: the page's core search phrases", st_h2))
    for code, phrase, body in C.TIER1:
        st.append(Paragraph(f'{code}&nbsp;&nbsp;{esc(phrase)}', st_kentry))
        st.append(Paragraph(esc(body), st_kbody))
    st.append(Paragraph("Tier 2: broader reach phrases (woven in, not headlined)", st_h2))
    for code, phrase, body in C.TIER2:
        st.append(Paragraph(f'{code}&nbsp;&nbsp;{esc(phrase)}', st_kentry))
        st.append(Paragraph(esc(body), st_kbody))
    st.append(Spacer(1, 4))
    st.append(Paragraph(esc(C.SUPPORTING), st_body))
    return st


def build_pdf():
    C.assert_clean()
    frame = Frame(ML, MB, PAGE_W - ML - MR, PAGE_H - MT - MB, id="main")
    doc = BaseDocTemplate(OUT, pagesize=A4, leftMargin=ML, rightMargin=MR,
                          topMargin=MT, bottomMargin=MB,
                          title="Bachelors Landing Page (UAE / GCC): Page Plan & Keywords",
                          author="Maverick Business Academy")
    doc.addPageTemplates([PageTemplate(id="plain", frames=[frame])])  # no header/footer canvases
    doc.build(story())
    print("wrote", OUT)

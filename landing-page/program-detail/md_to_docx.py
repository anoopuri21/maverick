"""Convert programme-detail .md files into clean client-facing .docx files.

Layout per document:
  1. Programme name (Heading 1, from hero title)
  2. SEO section
  3. Programme sections and details (hero, glance, overview, why, learning,
     careers, structure, support, gcc, fees)

Everything outside that content (BUILD NOTES, header metadata, status lines)
is excluded. No extra words.
"""
import re
import sys
from pathlib import Path

from docx import Document
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.shared import Pt, RGBColor, Cm

DARK = RGBColor(0x1F, 0x2A, 0x44)
ACCENT = RGBColor(0x0F, 0x62, 0x8F)
GREY = RGBColor(0x55, 0x5C, 0x66)


def parse_md(text):
    """Split the md into the SEO block and ordered content sections."""
    # strip the front matter block before "## SEO"
    body = text.split("## BUILD NOTES")[0]
    sections = []
    current = None
    for line in body.split("\n"):
        m = re.match(r"^##\s+(.*)$", line)
        if m and not line.startswith("### "):
            current = {"title": m.group(1).strip(), "lines": []}
            sections.append(current)
            continue
        if current is not None:
            if line.strip() == "---":
                continue
            current["lines"].append(line)
    seo = next((s for s in sections if s["title"] == "SEO"), None)
    content = [s for s in sections if s["title"] != "SEO"]
    return seo, content


def kv_lines(lines):
    """Parse 'Key | Value' style lines into pairs; returns (pairs, rest_lines)."""
    pairs = []
    rest = []
    for ln in lines:
        s = ln.strip()
        if not s:
            continue
        if "|" in s and not s.startswith("#"):
            k, v = [x.strip() for x in s.split("|", 1)]
            if k and v:
                pairs.append((k, v))
            else:
                rest.append(s)
        else:
            rest.append(s)
    return pairs, rest


def add_runs_with_bold(par, text):
    """Add text to a paragraph, honouring **bold** markers."""
    for i, chunk in enumerate(re.split(r"\*\*(.+?)\*\*", text)):
        if not chunk:
            continue
        run = par.add_run(chunk)
        if i % 2 == 1:
            run.bold = True


def style_doc(doc):
    st = doc.styles["Normal"]
    st.font.name = "Calibri"
    st.font.size = Pt(10.5)
    st.paragraph_format.space_after = Pt(6)
    for name, size, color in [("Heading 1", 22, DARK), ("Heading 2", 14, ACCENT), ("Heading 3", 12, DARK)]:
        h = doc.styles[name]
        h.font.name = "Calibri"
        h.font.size = Pt(size)
        h.font.color.rgb = color
        h.font.bold = True


def add_glance_table(doc, pairs):
    t = doc.add_table(rows=len(pairs), cols=2)
    t.style = "Table Grid"
    t.autofit = True
    for i, (k, v) in enumerate(pairs):
        c0 = t.cell(i, 0)
        c1 = t.cell(i, 1)
        c0.width = Cm(5.5)
        p0 = c0.paragraphs[0]
        r0 = p0.add_run(k)
        r0.bold = True
        r0.font.size = Pt(10)
        p1 = c1.paragraphs[0]
        p1.add_run(v).font.size = Pt(10)


def add_fees_table(doc, pairs):
    add_glance_table(doc, pairs)


def build_hero(doc, lines):
    fields = {}
    for ln in lines:
        m = re.match(r"^###\s+(.+)$", ln.strip())
        if m:
            fields["_cur"] = m.group(1).strip().lower()
            fields[fields["_cur"]] = ""
            continue
        cur = fields.get("_cur")
        if cur and ln.strip():
            fields[cur] = (fields.get(cur, "") + " " + ln.strip()).strip()
    # short description first
    if fields.get("short_description"):
        p = doc.add_paragraph()
        add_runs_with_bold(p, fields["short_description"])
        p.runs[0].font.size = Pt(11)
    meta = []
    if fields.get("level"):
        meta.append("Level: " + fields["level"])
    if fields.get("duration"):
        meta.append("Duration: " + fields["duration"])
    if meta:
        p = doc.add_paragraph(" · ".join(meta))
        p.runs[0].font.color.rgb = GREY
    if fields.get("highlights (quick highlights)") or fields.get("highlights"):
        key = "highlights (quick highlights)" if "highlights (quick highlights)" in fields else "highlights"
        doc.add_heading("Quick Highlights", level=3)
        for item in fields[key].split("|"):
            item = item.strip()
            if item:
                doc.add_paragraph(item, style="List Bullet")


def build_cards(doc, lines):
    """Build **NN · Title** + body cards."""
    title = None
    buf = []

    def flush():
        if title is None:
            return
        p = doc.add_paragraph()
        add_runs_with_bold(p, "**" + title + "**")
        if buf:
            doc.add_paragraph(" ".join(buf))

    for ln in lines:
        s = ln.strip()
        m = re.match(r"^\*\*(?:\d+\s*·\s*)?(.+?)\*\*$", s)
        if m:
            flush()
            title = m.group(1).strip()
            buf = []
        elif s.startswith("Closing line:"):
            flush()
            title = None
            buf = []
            p = doc.add_paragraph()
            add_runs_with_bold(p, "**Closing line:** " + s[len("Closing line:"):].strip())
        elif s:
            buf.append(s)
    flush()


def build_numbered(doc, lines):
    for ln in lines:
        s = ln.strip()
        m = re.match(r"^\d+\.?\s+(.+)$", s)
        if m:
            doc.add_paragraph(m.group(1), style="List Number")
        elif s and not s.startswith("#"):
            doc.add_paragraph(s)


def build_bullets(doc, lines, card_titles=True):
    """Handle cards (**bold title**) and plain bullets in mixed sections."""
    for ln in lines:
        s = ln.strip()
        if not s or s.startswith("#"):
            continue
        m = re.match(r"^- (.+)$", s)
        if m:
            doc.add_paragraph(m.group(1), style="List Bullet")
            continue
        m = re.match(r"^\*\*(.+?)\*\*$", s)
        if m and card_titles:
            p = doc.add_paragraph()
            add_runs_with_bold(p, "**" + m.group(1) + "**")
            continue
        doc.add_paragraph(s)


def build_structure(doc, lines):
    intro_done = False
    for ln in lines:
        s = ln.strip()
        if not s:
            continue
        if s.startswith("### intro"):
            continue
        if s.startswith("### "):
            continue
        m = re.match(r"^\*\*(.+?)\*\*$", s)
        if m:
            h = doc.add_heading(m.group(1), level=3)
            h.paragraph_format.space_before = Pt(10)
            continue
        m = re.match(r"^- (.+)$", s)
        if m:
            doc.add_paragraph(m.group(1), style="List Bullet")
            continue
        if not intro_done:
            doc.add_paragraph(s)
            intro_done = True
        else:
            doc.add_paragraph(s)


SECTION_TITLES = {
    "1. HERO": None,  # handled specially under the main heading
    "2. PROGRAMME AT A GLANCE (SNAPSHOT)": "Program at a Glance",
    "3. PROGRAMME OVERVIEW (DESCRIPTION)": "Program Overview",
    "4. WHY CHOOSE THIS PROGRAMME (BENEFITS)": "Why Choose This Program",
    "5. LEARNING OUTCOMES (LEARNING)": "What You Will Learn",
    "6. CAREER OPPORTUNITIES (CAREERS)": "Career Opportunities",
    "7. PROGRAMME STRUCTURE (STRUCTURE)": "Program Structure",
    "8. WHY STUDY THROUGH MAVERICK (SUPPORT)": "Why Study Through Maverick",
    "9. WHY GCC STUDENTS CHOOSE THIS COURSE (GCC_REASONS)": "Why GCC Students Choose This Programme",
    "9. WHY GCC PROFESSIONALS CHOOSE THIS COURSE (GCC_REASONS)": "Why GCC Professionals Choose This Programme",
    "9. WHY GCC STUDENTS AND PROFESSIONALS CHOOSE THIS BBA": "Why GCC Students Choose This Programme",
    "10. FEES & SCHOLARSHIPS (FEES)": "Fees & Scholarships",
}


def section_heading(title):
    key = title.upper()
    if key in SECTION_TITLES:
        return SECTION_TITLES[key]
    # fallback: strip leading number and parenthetical
    t = re.sub(r"^\d+\.\s+", "", title)
    t = re.sub(r"\s*\([^)]*\)\s*$", "", t)
    return t.title()


def build(md_path, out_path=None):
    md = Path(md_path)
    text = md.read_text(encoding="utf-8")
    seo, content = parse_md(text)
    if out_path is None:
        out_path = md.with_suffix(".docx")

    doc = Document()
    style_doc(doc)

    # --- programme name from hero title
    hero = next((s for s in content if s["title"].upper().startswith("1. HERO")), None)
    prog_name = "Program"
    if hero:
        for ln in hero["lines"]:
            if ln.strip().startswith("### title"):
                idx = hero["lines"].index(ln)
                for follow in hero["lines"][idx + 1:]:
                    if follow.strip() and not follow.startswith("###"):
                        prog_name = follow.strip()
                        break
                break
    h = doc.add_heading(prog_name, level=1)
    h.alignment = WD_ALIGN_PARAGRAPH.LEFT

    # --- SEO section
    doc.add_heading("SEO", level=2)
    seo_map = {}
    cur = None
    for ln in seo["lines"]:
        m = re.match(r"^###\s+(.+)$", ln.strip())
        if m:
            cur = m.group(1).strip().lower()
            seo_map[cur] = ""
            continue
        if cur and ln.strip():
            seo_map[cur] = (seo_map.get(cur, "") + " " + ln.strip()).strip()
    for label, key in [("Meta Title", "meta_title"), ("Meta Description", "meta_description"), ("URL", "url_slug")]:
        if seo_map.get(key):
            p = doc.add_paragraph()
            r = p.add_run(label + ": ")
            r.bold = True
            p.add_run(seo_map[key])

    # --- content sections
    for sec in content:
        up = sec["title"].upper()
        if up.startswith("1. HERO"):
            doc.add_heading("Hero", level=2)
            build_hero(doc, sec["lines"])
            continue
        heading = section_heading(sec["title"])
        doc.add_heading(heading, level=2)
        lines = sec["lines"]
        if up.startswith("2."):
            note_lines = []
            body_lines = []
            seen_note = False
            for ln in lines:
                if ln.strip() == "### note":
                    seen_note = True
                    continue
                if seen_note:
                    if ln.strip():
                        note_lines.append(ln.strip())
                else:
                    body_lines.append(ln)
            pairs, rest = kv_lines(body_lines)
            add_glance_table(doc, pairs)
            for r_ in rest:
                if r_.startswith("###"):
                    continue
                doc.add_paragraph(r_)
            if note_lines:
                p = doc.add_paragraph()
                r = p.add_run("Note: ")
                r.bold = True
                p.add_run(" ".join(note_lines))
        elif up.startswith("3."):
            for ln in lines:
                if ln.strip() == "### copy":
                    continue
                if ln.strip():
                    doc.add_paragraph(ln.strip())
        elif up.startswith("4."):
            build_cards(doc, lines)
        elif up.startswith("5."):
            build_numbered(doc, lines)
        elif up.startswith("6."):
            for ln in lines:
                s_ = ln.strip()
                if not s_ or s_.startswith("#"):
                    continue
                m = re.match(r"^- (.+)$", s_)
                if m:
                    doc.add_paragraph(m.group(1), style="List Bullet")
                elif len(s_) < 60 and not s_.endswith("."):
                    doc.add_paragraph(s_, style="List Bullet")
                else:
                    doc.add_paragraph(s_)
        elif up.startswith("7."):
            build_structure(doc, lines)
        elif up.startswith("8."):
            pairs, rest = kv_lines(lines)
            if pairs:
                add_glance_table(doc, pairs)
            for r_ in rest:
                doc.add_paragraph(r_)
        elif up.startswith("9."):
            # optional gcc heading line + cards + honest take
            cards = []
            title = None
            buf = []

            def flush():
                if title is not None:
                    cards.append((title, " ".join(buf)))

            for ln in lines:
                s = ln.strip()
                if s.startswith("### gcc_heading"):
                    continue
                m = re.match(r"^\*\*(?:\d+\s*·\s*)?(.+?)\*\*$", s)
                if m:
                    flush()
                    title = m.group(1).strip()
                    buf = []
                elif s.startswith("Honest take:"):
                    flush()
                    title = None
                    buf = []
                    cards.append(("Honest take", s[len("Honest take:"):].strip()))
                elif s:
                    buf.append(s)
            flush()
            for t_, b_ in cards:
                p = doc.add_paragraph()
                add_runs_with_bold(p, "**" + t_ + "**")
                if b_:
                    doc.add_paragraph(b_)
        elif up.startswith("10."):
            cut = []
            for ln in lines:
                if ln.strip() == "### request block":
                    break
                cut.append(ln)
            pairs, rest = kv_lines(cut)
            add_fees_table(doc, pairs)
            block = []
            inside = False
            for ln in lines:
                s = ln.strip()
                if s == "### request block":
                    inside = True
                    continue
                if inside and s:
                    block.append(s)
            if block:
                p = doc.add_paragraph()
                r = p.add_run("Request the fee structure: ")
                r.bold = True
                p.add_run(" ".join(block))
            for r_ in rest:
                if r_.startswith("###") or r_.startswith("Request the fee structure"):
                    continue
                doc.add_paragraph(r_)
        else:
            for ln in lines:
                if ln.strip():
                    doc.add_paragraph(ln.strip())

    doc.save(out_path)
    return str(out_path)



def parse_format_b(text):
    """Split a Format B md (GAU MSc / UK style) into title, meta, and sections."""
    body = text.split("## BUILD NOTES")[0]
    title = None
    meta_lines = []
    sections = []
    current = None
    for line in body.split("\n"):
        if title is None:
            m = re.match(r"^#\s+(.*)$", line)
            if m:
                title = m.group(1).strip()
                continue
        if current is None and line.strip().startswith("**") and ":**" in line:
            meta_lines.append(line.strip())
            continue
        m = re.match(r"^##\s+(.*)$", line)
        if m:
            current = {"title": m.group(1).strip(), "lines": []}
            sections.append(current)
            continue
        if current is not None:
            if line.strip() == "---":
                continue
            current["lines"].append(line)
    return title, meta_lines, sections


def md_table_pairs(lines):
    """Parse a markdown table (| a | b |) skipping header/separator rows."""
    pairs = []
    for ln in lines:
        s = ln.strip()
        if not s.startswith("|"):
            continue
        cells = [c.strip() for c in s.strip("|").split("|")]
        if len(cells) < 2:
            continue
        if set(cells[0]) <= set("- ") or cells[0].lower() in ("row", "key"):
            continue
        pairs.append((cells[0], cells[1]))
    return pairs


def bullets_from(lines):
    out = []
    for ln in lines:
        s = ln.strip()
        if not s:
            continue
        m = re.match(r"^- (.+)$", s)
        if m:
            out.append(m.group(1))
        elif not s.startswith("#"):
            out.append(s)
    return out


B_HEADINGS = {
    "AT A GLANCE": "Program at a Glance",
    "OVERVIEW": "Program Overview",
    "WHY THIS PROGRAMME": "Why Choose This Program",
    "WHAT YOU WILL LEARN": "What You Will Learn",
    "CAREERS": "Career Opportunities",
    "STRUCTURE": "Program Structure",
    "SUPPORT": "Why Study Through Maverick",
    "GCC MARKET CONTEXT": "GCC Market Context",
    "FEES": "Fees & Scholarships",
}


def build_format_b(md_path, out_path=None):
    md = Path(md_path)
    text = md.read_text(encoding="utf-8")
    title, meta_lines, sections = parse_format_b(text)
    if out_path is None:
        out_path = md.with_suffix(".docx")
    doc = Document()
    style_doc(doc)

    h = doc.add_heading(title or "Program", level=1)
    h.alignment = WD_ALIGN_PARAGRAPH.LEFT

    # meta lines (Duration, Format, Delivery, Family) as an intro line
    meta_bits = []
    for ml in meta_lines:
        m = re.match(r"^\*\*(.+?):\*\*\s*(.*)$", ml)
        if m:
            k = m.group(1).strip().lower()
            if k in ("duration", "format", "delivery"):
                meta_bits.append(m.group(1).strip() + ": " + m.group(2).strip())
    if meta_bits:
        p = doc.add_paragraph(" · ".join(meta_bits))
        p.runs[0].font.color.rgb = GREY

    # SEO section right after the title, per pattern
    seo = next((s for s in sections if s["title"].upper() == "SEO"), None)
    if seo:
        doc.add_heading("SEO", level=2)
        for ln in seo["lines"]:
            m = re.match(r"^\*\*(.+?):\*\*\s*(.*)$", ln.strip())
            if m:
                p = doc.add_paragraph()
                r = p.add_run(m.group(1).strip() + ": ")
                r.bold = True
                p.add_run(m.group(2).strip())

    for sec in sections:
        up = sec["title"].upper()
        if up == "SEO":
            continue
        heading = B_HEADINGS.get(up, sec["title"].title())
        doc.add_heading(heading, level=2)
        lines = sec["lines"]
        if up == "AT A GLANCE":
            pairs = md_table_pairs(lines)
            add_glance_table(doc, pairs)
            after = []
            seen_table = False
            for ln in lines:
                s = ln.strip()
                if s.startswith("|"):
                    seen_table = True
                    continue
                if seen_table and s:
                    after.append(s)
            for a_ in after:
                doc.add_paragraph(a_)
        elif up == "OVERVIEW":
            for ln in lines:
                if ln.strip():
                    doc.add_paragraph(ln.strip())
        elif up == "WHY THIS PROGRAMME":
            card_title = None
            buf = []

            def flush_b():
                if card_title is not None:
                    p = doc.add_paragraph()
                    add_runs_with_bold(p, "**" + card_title + "**")
                    if buf:
                        doc.add_paragraph(" ".join(buf))

            for ln in lines:
                s = ln.strip()
                m = re.match(r"^\*\*(.+?)\*\*$", s)
                if m:
                    flush_b()
                    card_title = m.group(1).strip()
                    buf = []
                elif s.startswith("Honest take:"):
                    flush_b()
                    card_title = None
                    buf = []
                    p = doc.add_paragraph()
                    r = p.add_run("Honest take: ")
                    r.bold = True
                    p.add_run(s[len("Honest take:"):].strip())
                elif s:
                    buf.append(s)
            flush_b()
        elif up == "WHAT YOU WILL LEARN":
            for b_ in bullets_from(lines):
                doc.add_paragraph(b_, style="List Bullet")
        elif up == "CAREERS":
            paras = [ln.strip() for ln in lines if ln.strip()]
            if paras:
                roles = [r_.strip() for r_ in paras[0].split(",") if r_.strip()]
                if len(roles) >= 4:
                    for r_ in roles:
                        doc.add_paragraph(r_, style="List Bullet")
                    for extra in paras[1:]:
                        doc.add_paragraph(extra)
                else:
                    for extra in paras:
                        doc.add_paragraph(extra)
        elif up == "STRUCTURE":
            stage_title = None
            buf = []

            def flush_s():
                if stage_title is not None:
                    doc.add_heading(stage_title, level=3)
                    for b_ in buf:
                        if b_.startswith("- "):
                            doc.add_paragraph(b_[2:], style="List Bullet")
                        else:
                            doc.add_paragraph(b_)

            for ln in lines:
                s = ln.strip()
                m = re.match(r"^\*\*(.+?)\*\*$", s)
                if m:
                    flush_s()
                    stage_title = m.group(1).strip()
                    buf = []
                elif s:
                    buf.append(s)
            flush_s()
        elif up in ("SUPPORT", "GCC MARKET CONTEXT", "FEES"):
            for ln in lines:
                s_ = ln.strip()
                if not s_:
                    continue
                m = re.match(r"^- (.+)$", s_)
                if m:
                    doc.add_paragraph(m.group(1), style="List Bullet")
                elif not s_.startswith("#"):
                    doc.add_paragraph(s_)
        else:
            for ln in lines:
                if ln.strip():
                    doc.add_paragraph(ln.strip())

    doc.save(out_path)
    return str(out_path)



def convert(md_path):
    text = Path(md_path).read_text(encoding="utf-8")
    if "## 1. HERO" in text:
        return build(md_path)
    return build_format_b(md_path)


if __name__ == "__main__":
    for f in sys.argv[1:]:
        print(convert(f))

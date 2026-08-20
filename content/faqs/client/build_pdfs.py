#!/usr/bin/env python3
"""Build client-ready PDFs for the Education FAQ project.
Outputs (content/faqs/client/):
  1. Maverick-Education-FAQ-Content-Pack.pdf      — all approved FAQs, cleaned
  2. Maverick-FAQ-Strategy-Ranking-Report.pdf     — selection & ranking report (English)
  3. Maverick-Blocker-Resolution-Report.pdf       — publish-blocker resolutions
"""
import re, os, glob
from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.lib import colors
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.platypus import (BaseDocTemplate, PageTemplate, Frame, Paragraph,
                                Spacer, PageBreak, Table, TableStyle, HRFlowable)

BASE = os.path.dirname(os.path.abspath(__file__))
APPROVED = os.path.join(BASE, '..', 'approved')
OUT = BASE

NAVY = colors.HexColor('#122A46')
GOLD = colors.HexColor('#B9924C')
GREY = colors.HexColor('#5A6572')
LIGHT = colors.HexColor('#F4F0E8')
RULE = colors.HexColor('#D8D2C4')

def S(name, **kw):
    base = dict(fontName='Helvetica', fontSize=10, leading=14.5, textColor=colors.HexColor('#222A33'))
    base.update(kw)
    return ParagraphStyle(name, **base)

st_cover_title = S('ct', fontName='Helvetica-Bold', fontSize=27, leading=33, textColor=NAVY, alignment=TA_CENTER)
st_cover_sub   = S('cs', fontSize=13, leading=19, textColor=GREY, alignment=TA_CENTER)
st_cover_meta  = S('cm', fontSize=10, leading=15, textColor=GREY, alignment=TA_CENTER)
st_provider    = S('pv', fontName='Helvetica-Bold', fontSize=19, leading=24, textColor=NAVY)
st_h2          = S('h2', fontName='Helvetica-Bold', fontSize=14, leading=18, textColor=NAVY, spaceBefore=14, spaceAfter=4)
st_h3          = S('h3', fontName='Helvetica-Bold', fontSize=12, leading=16, textColor=GOLD, spaceBefore=10, spaceAfter=3)
st_h4          = S('h4', fontName='Helvetica-Bold', fontSize=10.5, leading=14, textColor=GREY, spaceBefore=8, spaceAfter=2)
st_q           = S('q',  fontName='Helvetica-Bold', fontSize=10.5, leading=15, textColor=NAVY, spaceBefore=9, spaceAfter=2)
st_body        = S('b',  spaceAfter=5)
st_bullet      = S('bl', leftIndent=14, bulletIndent=4, spaceAfter=2.5)
st_note        = S('nt', fontName='Helvetica-Oblique', fontSize=9, leading=13, textColor=GREY, spaceAfter=6)
st_intro       = S('in', fontSize=10.5, leading=15.5, textColor=colors.HexColor('#333B44'), spaceAfter=8)
st_tbl         = S('tb', fontSize=8.4, leading=11.4)
st_tbl_b       = S('tbb', fontName='Helvetica-Bold', fontSize=8.4, leading=11.4, textColor=colors.white)

SANITIZE = {'\u2014':'-', '\u2013':'-', '\u2192':'->', '\u2248':'~', '\u2705':'', '\u26a0':'', '\ufe0f':'',
            '\u2264':'<=', '\u2265':'>=', '\U0001F947':'', '\U0001F948':'', '\U0001F949':'',
            '\U0001F534':'', '\U0001F7E0':'', '\U0001F7E1':'', '\u26aa':'', '\U0001F535':'',
            '\u2b1c':'', '\U0001F9ED':'', '\u00b7':'\u00b7'}

def clean_text(s):
    for k, v in SANITIZE.items():
        s = s.replace(k, v)
    return s.encode('cp1252', 'ignore').decode('cp1252')

def inline(md):
    md = clean_text(md)
    md = md.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')
    md = re.sub(r'\*\*(.+?)\*\*', r'<b>\1</b>', md)
    md = re.sub(r'(?<!\w)\*(?!\s)([^*]+?)\*(?!\w)', r'<i>\1</i>', md)
    return md

DISCLAIMER = ('Fee amounts, scholarships and entry requirements are confirmed individually '
              'during the admissions eligibility review and may change without notice.')

PROVIDER_ORDER = [
    ('rushford-business-school.md', 'Rushford Business School (RBS)', '4 categories + practical guide - BBA | MBA | MSc | Doctoral', 35, 41),
    ('girne-american-university.md', 'Girne American University (GAU)', '5 categories + practical guide - BSc | MBA | EMBA | MSc (Thesis) | PhD', 37, 43),
    ('university-west-scotland.md', 'University of the West of Scotland (UWS)', '1 category + practical guide - BA (Hons) Global Business', 12, 1),
    ('university-creative-arts.md', 'University for the Creative Arts (UCA)', '1 category + practical guide - Global MBA (dual award with RBS)', 12, 1),
    ('university-wolverhampton.md', 'University of Wolverhampton (UOW)', '1 category + practical guide - Master of Laws (LLM)', 12, 1),
    ('gatehouse-diplomas.md', 'Gatehouse Level 7 Diplomas', '1 category + practical guide - Level 7 Diplomas (4 tracks)', 12, 4),
    ('qualifi-diplomas.md', 'Qualifi Diplomas', '3 categories + practical guide - Level 3 | Level 5 Extended | Level 7', 22, 45),
]

def parse_faq_md(path):
    """Return flowables for one cleaned provider file (no comments, no verify tables)."""
    s = open(path, encoding='utf-8').read()
    s = re.sub(r'<!--.*?-->', '', s, flags=re.S)
    s = s.split('\n## Facts to Verify')[0]
    flows = []
    lines = s.split('\n')
    i, para, bullets, quote = 0, [], [], []

    def flush_para():
        nonlocal para
        if para:
            txt = ' '.join(p.strip() for p in para).strip()
            if txt and txt != '---':
                if re.fullmatch(r'\*[^*].*\*', txt):
                    flows.append(Paragraph(inline(txt), st_note))
                else:
                    flows.append(Paragraph(inline(txt), st_body))
            para = []

    def flush_bullets():
        nonlocal bullets
        for b in bullets:
            flows.append(Paragraph(inline(b), st_bullet, bulletText='\u2022'))
        if bullets:
            flows.append(Spacer(1, 3))
        bullets = []

    def flush_quote():
        nonlocal quote
        if quote:
            txt = inline(' '.join(quote))
            tbl = Table([[Paragraph(txt, st_intro)]], colWidths=[168*mm])
            tbl.setStyle(TableStyle([('BACKGROUND', (0,0), (-1,-1), LIGHT),
                                     ('LINEBEFORE', (0,0), (0,-1), 2.4, GOLD),
                                     ('LEFTPADDING', (0,0), (-1,-1), 10),
                                     ('RIGHTPADDING', (0,0), (-1,-1), 10),
                                     ('TOPPADDING', (0,0), (-1,-1), 7),
                                     ('BOTTOMPADDING', (0,0), (-1,-1), 7)]))
            flows.append(tbl); flows.append(Spacer(1, 6))
            quote = []

    while i < len(lines):
        ln = lines[i].rstrip()
        stripped = ln.strip()
        if stripped.startswith('|'):
            flush_para(); flush_bullets(); flush_quote()
            tbl_lines = []
            while i < len(lines) and lines[i].strip().startswith('|'):
                tbl_lines.append(lines[i].strip()); i += 1
            i -= 1
            rows = []
            for tl in tbl_lines:
                cells = [c.strip() for c in tl.strip('|').split('|')]
                if all(re.fullmatch(r':?-{2,}:?', c) for c in cells if c):
                    continue
                rows.append(cells)
            if rows:
                ncol = max(len(r) for r in rows)
                data = []
                for ri, r in enumerate(rows):
                    r = r + [''] * (ncol - len(r))
                    sty = st_tbl_b if ri == 0 else st_tbl
                    data.append([Paragraph(inline(c) if ri else ('<b>'+inline(c)+'</b>'), sty) for c in r])
                cw = [(168/ncol)*mm]*ncol
                t = Table(data, colWidths=cw, repeatRows=1)
                t.setStyle(TableStyle([('BACKGROUND', (0,0), (-1,0), NAVY),
                                       ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, LIGHT]),
                                       ('GRID', (0,0), (-1,-1), 0.4, RULE),
                                       ('VALIGN', (0,0), (-1,-1), 'TOP'),
                                       ('TOPPADDING', (0,0), (-1,-1), 3.5), ('BOTTOMPADDING', (0,0), (-1,-1), 3.5)]))
                flows.append(Spacer(1, 3)); flows.append(t); flows.append(Spacer(1, 5))
        elif stripped.startswith('# ') and not stripped.startswith('## '):
            pass  # provider title handled by section header
        elif stripped.startswith('> '):
            flush_para(); flush_bullets(); quote.append(stripped[2:])
        elif stripped.startswith('#### '):
            flush_para(); flush_bullets(); flush_quote()
            flows.append(Paragraph(inline(stripped[5:]).upper(), st_h4))
        elif stripped.startswith('### '):
            flush_para(); flush_bullets(); flush_quote()
            flows.append(Paragraph(inline(stripped[4:]), st_h3))
        elif stripped.startswith('## '):
            flush_para(); flush_bullets(); flush_quote()
            flows.append(Spacer(1, 6))
            flows.append(HRFlowable(width='100%', thickness=0.8, color=RULE, spaceAfter=4))
            flows.append(Paragraph(inline(stripped[3:]), st_h2))
        elif stripped.startswith('**Q.'):
            flush_para(); flush_bullets(); flush_quote()
            q = re.sub(r'^\*\*(.+?)\*\*$', r'\1', stripped)
            flows.append(Paragraph(inline('<b>' + q + '</b>').replace('<b><b>', '<b>').replace('</b></b>', '</b>'), st_q))
        elif stripped.startswith('- '):
            flush_para(); flush_quote(); bullets.append(stripped[2:])
        elif stripped == '' or stripped == '---':
            flush_para(); flush_bullets(); flush_quote()
        else:
            flush_bullets(); flush_quote(); para.append(stripped)
        i += 1
    flush_para(); flush_bullets(); flush_quote()
    return flows

def make_doc(path, footer_label):
    doc = BaseDocTemplate(path, pagesize=A4,
                          leftMargin=21*mm, rightMargin=21*mm, topMargin=20*mm, bottomMargin=18*mm,
                          title=footer_label, author='Maverick Business Academy')
    frame = Frame(doc.leftMargin, doc.bottomMargin, doc.width, doc.height, id='f')

    def on_page(canv, d):
        canv.saveState()
        if d.page > 1:
            canv.setStrokeColor(RULE); canv.setLineWidth(0.6)
            canv.line(21*mm, 285*mm, 189*mm, 285*mm)
            canv.setFont('Helvetica', 7.5); canv.setFillColor(GREY)
            canv.drawString(21*mm, 287*mm, 'MAVERICK BUSINESS ACADEMY')
            canv.drawRightString(189*mm, 287*mm, footer_label)
            canv.setFont('Helvetica', 7.5)
            canv.drawCentredString(105*mm, 11*mm, f'Page {d.page}')
            canv.drawRightString(189*mm, 11*mm, 'Confidential - for client review')
        canv.restoreState()

    doc.addPageTemplates([PageTemplate(id='p', frames=[frame], onPage=on_page)])
    return doc

def cover(flows, title, subtitle, stats_rows):
    flows.append(Spacer(1, 55*mm))
    flows.append(Paragraph('MAVERICK BUSINESS ACADEMY', st_cover_meta))
    flows.append(Spacer(1, 4))
    flows.append(HRFlowable(width='30%', thickness=1.1, color=GOLD, hAlign='CENTER'))
    flows.append(Spacer(1, 10))
    flows.append(Paragraph(title, st_cover_title))
    flows.append(Spacer(1, 7))
    flows.append(Paragraph(subtitle, st_cover_sub))
    flows.append(Spacer(1, 22))
    if stats_rows:
        t = Table(stats_rows, colWidths=[62*mm, 62*mm], hAlign='CENTER')
        t.setStyle(TableStyle([('FONTNAME', (0,0), (0,-1), 'Helvetica-Bold'),
                               ('FONTSIZE', (0,0), (-1,-1), 10),
                               ('TEXTCOLOR', (0,0), (0,-1), NAVY),
                               ('TEXTCOLOR', (1,0), (1,-1), GREY),
                               ('LINEBELOW', (0,0), (-1,-2), 0.4, RULE),
                               ('TOPPADDING', (0,0), (-1,-1), 5),
                               ('BOTTOMPADDING', (0,0), (-1,-1), 5)]))
        flows.append(t)
    flows.append(Spacer(1, 26))
    flows.append(Paragraph('Prepared for client review - 19 August 2026', st_cover_meta))
    flows.append(Paragraph('Confidential. Not for publication until approved.', st_cover_meta))
    flows.append(PageBreak())

# ---------------------------------------------------------------- PDF 1: FAQ pack
def build_faq_pack():
    flows = []
    cover(flows, 'Education FAQ Content Pack',
          'Website FAQ content for university partner programmes<br/>7 providers | 16 category FAQ sets + practical guides | 142 FAQs | 136 programmes covered',
          [['Providers', '7'], ['FAQ sets (categories)', '16'], ['Total FAQs', '142'],
           ['Programmes covered', '136'], ['Content language', 'English'],
           ['Audience', 'Global (country-neutral)']])
    # contents
    flows.append(Paragraph('Contents', st_provider)); flows.append(Spacer(1, 6))
    rows = [[Paragraph('<b>Provider</b>', st_tbl_b), Paragraph('<b>Coverage</b>', st_tbl_b),
             Paragraph('<b>FAQs</b>', st_tbl_b)]]
    for _, name, cov, nq, nprog in PROVIDER_ORDER:
        rows.append([Paragraph(clean_text(name), st_tbl), Paragraph(clean_text(f'{cov} - {nprog} programmes'), st_tbl),
                     Paragraph(str(nq), st_tbl)])
    t = Table(rows, colWidths=[52*mm, 100*mm, 16*mm], repeatRows=1)
    t.setStyle(TableStyle([('BACKGROUND', (0,0), (-1,0), NAVY),
                           ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, LIGHT]),
                           ('GRID', (0,0), (-1,-1), 0.4, RULE),
                           ('VALIGN', (0,0), (-1,-1), 'TOP'),
                           ('TOPPADDING', (0,0), (-1,-1), 4), ('BOTTOMPADDING', (0,0), (-1,-1), 4)]))
    flows.append(t)
    flows.append(Spacer(1, 10))
    flows.append(Paragraph('All content is written for a global audience with no country-specific references, '
                           'in student-friendly English, and formatted for direct upload. Each category FAQ set '
                           'applies to every programme within that category.', st_note))
    flows.append(PageBreak())

    for fname, name, cov, nq, nprog in PROVIDER_ORDER:
        flows.append(Paragraph(clean_text(name), st_provider))
        flows.append(Paragraph(clean_text(f'{cov} | {nq} FAQs | {nprog} programmes covered'), st_note))
        flows.append(HRFlowable(width='100%', thickness=1.1, color=GOLD, spaceAfter=6))
        flows.extend(parse_faq_md(os.path.join(APPROVED, fname)))
        flows.append(Spacer(1, 8))
        flows.append(Paragraph('<i>' + DISCLAIMER + '</i>', st_note))
        flows.append(PageBreak())

    doc = make_doc(os.path.join(OUT, 'Maverick-Education-FAQ-Content-Pack.pdf'), 'Education FAQ Content Pack')
    doc.build(flows)

# ------------------------------------------------------- PDF 2: strategy report
TIER_LABEL = {1: 'Tier 1 - Very High', 2: 'Tier 2 - High', 3: 'Tier 3 - Medium', 4: 'Tier 4 - Branded'}

RANK_DATA = [
 ('Rushford Business School (RBS)', '35 questions | 4 categories + practical guide', [
  (1,'Do I need GMAT or GRE for the MBA?','Biggest global MBA objection query; genuine differentiator',1,4.85),
  (2,'PhD vs DBA - the difference','Top doctoral comparison query; strong PAA candidate',1,4.60),
  (3,'BBA without an English test?','High-demand "without IELTS" family; entry-barrier remover',1,4.45),
  (3,'Careers after a BBA','Evergreen ROI query for students and parents',1,4.45),
  (3,'MBA while working full-time','Core doubt of the working-professional audience',1,4.45),
  (3,'MBA vs MSc - the difference','High-volume comparison; routes readers to both categories',1,4.45),
  (3,'Fully online doctorate?','Fast-growing "online DBA" query family',1,4.45),
  (8,'Is RBS a recognised institution?','No.1 trust objection before any enquiry',2,4.25),
  (8,'Doctoral entry without a master\'s','Recognition-of-experience route is a USP',2,4.25),
  (10,'Career outcomes after the MBA','Core ROI purchase driver',2,4.20),
  (11,'BBA entry requirements','Prevents self-disqualification',2,4.10),
  (11,'MBA duration','Snippet-friendly "how long" pattern',2,4.10),
  (11,'MBA entry requirements','Conversion-critical eligibility query',2,4.10),
  (11,'MSc eligibility','Core eligibility query',2,4.10),
  (11,'Doctoral duration','Three-year positioning is competitive',2,4.10),
  (16,'BBA fee structure','Decision-stage affordability',2,3.85),
  (16,'Master\'s after BBA','Pathway cross-sell to postgraduate portfolio',2,3.85),
  (16,'MBA fee structure','Decision-stage',2,3.85),
  (16,'MSc fees & scholarships','Decision-stage double query',2,3.85),
  (16,'Careers after MSc','Specialist ROI plus doctoral teaser',2,3.85),
  (16,'Doctorate career outcomes','Top-level ROI validation',2,3.85),
  (22,'MSc duration','Snippet-friendly pattern',3,3.75),
  (23,'Doctoral English requirements','Application-barrier remover',3,3.65),
  (24,'Doctoral fee structure','Decision-stage',3,3.50),
  (25,'BBA specialisations (hub)','Category index; highest-intent branded query',4,3.45),
  (25,'MBA specialisations (hub)','Category index',4,3.45),
  (25,'MSc programmes (hub)','Category index',4,3.45),
  (25,'Doctoral options (hub)','Category index',4,3.45),
  (29,'Doctorate after the MBA','Lifetime-value pathway question',3,3.40),
  (30,'Is the BBA fully online?','Delivery-mode filter for distance learners',4,3.20),
  ('P2','How do I apply?','Action-intent application query',2,4.25),
  ('P2','Documents needed to apply','Application-stage checklist query',2,3.90),
  ('P2','Are online degrees taken seriously?','No.1 unspoken objection; verified credential facts',1,4.70),
  ('P2','When can I start?','Urgency and planning query',2,3.85),
  ('P2','What if I need more time?','Risk-reversal; official 3-year no-fee extension',2,3.75)]),
 ('Girne American University (GAU)', '37 questions | 5 categories + practical guide', [
  (1,'MBA vs Executive MBA','Top comparison query; routes to both categories',1,4.60),
  (2,'Study a BSc flexibly while working','Working-learner filter question',1,4.45),
  (2,'IELTS/TOEFL needed? (own-test option)','USP: university-arranged English assessment',1,4.45),
  (2,'MBA without pausing your career','Working-professional core doubt',1,4.45),
  (5,'Is a GAU degree recognised?','Trust objection; est. 1985 + IACBE framing',2,4.25),
  (5,'EMBA entry & work experience','Senior-profile eligibility filter',2,4.25),
  (5,'PhD entry requirements','Checklist format targets list snippets',2,4.25),
  (8,'MBA career outcomes','Core ROI driver',2,4.20),
  (9,'BSc entry requirements','Prevents self-disqualification',2,4.10),
  (9,'MBA duration','Snippet-friendly pattern',2,4.10),
  (9,'MBA entry (no admission test)','Eligibility plus differentiator folded in',2,4.10),
  (9,'EMBA while working full-time','Executive delivery-fit confirmation',2,4.10),
  (9,'MSc eligibility','Core eligibility query',2,4.10),
  (9,'PhD duration','Honest individual-timeline framing',2,4.10),
  (15,'What "MSc with Thesis" means','Definitional entry point for the category',2,4.00),
  (16,'BSc fees & scholarships','Intake-linked scholarship urgency',2,3.85),
  (16,'What comes after the BSc','Cross-sell to postgraduate portfolio',2,3.85),
  (16,'MBA fee structure','Decision-stage',2,3.85),
  (16,'EMBA career impact','"Worth it" ROI family',2,3.85),
  (16,'PhD while working','Working-researcher angle (no overlap with RBS)',2,3.85),
  (16,'PhD career outcomes','ROI validation',2,3.85),
  (22,'Who should choose the thesis route','Captures PhD-aspirant segment',3,3.65),
  (22,'PhD after a thesis MSc','Pathway cross-sell to GAU PhD',3,3.65),
  (24,'EMBA fee structure','Instalment framing for a big-ticket award',3,3.50),
  (24,'MSc fee structure','Decision-stage',3,3.50),
  (24,'PhD fee structure','Decision-stage',3,3.50),
  (27,'BSc programmes (hub)','Category index',4,3.45),
  (27,'MBA specialisations (hub)','Category index',4,3.45),
  (27,'EMBA specialisations (hub)','Index showcasing 16-track width',4,3.45),
  (27,'Thesis MSc programmes (hub)','Category index',4,3.45),
  (27,'PhD programmes (hub)','Category index',4,3.45),
  (32,'How students are assessed','Transparency and trust builder',3,3.40),
  ('P2','How the eligibility review works','Review-first admissions angle',2,3.90),
  ('P2','Document translation/attestation','High-value practical query for global applicants',2,4.00),
  ('P2','How employers verify the degree','Trust and verification query',2,3.75),
  ('P2','How scholarships actually work','Intake-linked benefit mechanics',2,4.20),
  ('P2','Distance-learner support','Support-system reassurance (verified data)',3,3.65)]),
 ('University of the West of Scotland (UWS)', '12 questions | BA (Hons) Global Business (top-up) + practical guide', [
  (1,'What is a top-up degree?','USP definitional; strong snippet family',1,4.60),
  (2,'Study online while working','Working-professional career-upgrade angle',1,4.45),
  (3,'Is UWS recognised?','Trust objection',2,4.25),
  (4,'Programme duration (~12 months)','Fastest-route hook; snippet pattern',2,4.10),
  (4,'Entry requirements (prior learning)','Hope-widener for experienced applicants',2,4.10),
  (6,'Master\'s or MBA afterwards','Pathway cross-sell',2,4.00),
  (7,'Careers after the degree','ROI and promotion-case framing',2,3.85),
  (8,'Fee structure','Decision-stage',3,3.50),
  (9,'Programme overview (hub)','Anchor question',4,3.45),
  ('P2','Work experience into a degree','Experience-to-credential family',2,4.30),
  ('P2','What the certificate says','Top-up stigma neutraliser',2,4.10),
  ('P2','Weekly study hours','Time-commitment practicality',3,3.70)]),
 ('University for the Creative Arts (UCA)', '12 questions | Global MBA (dual award) + practical guide', [
  (1,'What a dual MBA award means','Core USP; high-value comparison family',1,4.85),
  (2,'Duration & credits (12-18 months, 90 ECTS)','Officially verified; snippet pattern',2,4.10),
  (2,'Fully online delivery','Delivery-mode filter',2,4.10),
  (2,'Entry requirements (any discipline)','Hope-widener; officially verified',2,4.10),
  (2,'Cost & instalment plan','Portal-published transparent pricing',2,4.10),
  (6,'Career advantage of the dual award','ROI differentiation',2,3.85),
  (7,'Programme overview (hub)','Triple-certification hook',4,3.70),
  (8,'Programme structure (two stages)','Academic-rigour signal',3,3.65),
  (9,'Doctorate afterwards','Pathway cross-sell',3,3.40),
  ('P2','Two separate certificates?','Dual-award mechanics',2,3.95),
  ('P2','Employer sponsorship','Corporate-funding angle',3,3.60),
  ('P2','Thesis-stage support','Supervision reassurance',3,3.55)]),
 ('University of Wolverhampton (UOW)', '12 questions | Master of Laws (LLM) + practical guide', [
  (1,'LLM without a law degree?','Top PAA family for law-adjacent professionals',1,4.70),
  (2,'The LLM top-up route','Fastest-route hook; portfolio funnel question',2,4.50),
  (3,'Is UOW recognised?','Trust objection',2,4.25),
  (4,'LLM while working','Working legal professionals filter',2,4.10),
  (4,'Entry requirements (three routes)','Route clarity; list-snippet format',2,4.10),
  (6,'LLM career opportunities','ROI with compliance-safe practice-rights note',2,3.85),
  (7,'Fee structure','Decision-stage',3,3.50),
  (8,'Programme overview (hub)','Anchor question',4,3.45),
  (9,'Doctoral study after the LLM','Pathway cross-sell',3,3.40),
  ('P2','Which professionals benefit most','Audience-fit segmentation',2,3.80),
  ('P2','The legal research project','Programme-core transparency',3,3.60),
  ('P2','Straight after degree or work first','Timing-guidance query',3,3.65)]),
 ('Gatehouse Level 7 Diplomas', '12 questions | 4 diploma tracks + practical guide', [
  (1,'What is a Level 7 Diploma?','Education-stage definitional; category traffic engine',1,4.60),
  (1,'Level 7 Diploma vs full master\'s','Comparison magnet; sets up top-up upsell',1,4.60),
  (3,'Top-up to a full master\'s','Portfolio funnel hook',2,4.25),
  (4,'Duration & assessment','Fast-credential positioning',2,4.10),
  (4,'Online while working','Working-professional filter',2,4.10),
  (4,'Entry requirements (degree or experience)','Captures senior non-graduates',2,4.10),
  (7,'Career impact','"Months not years" ROI',2,3.85),
  (8,'Fee structure','Decision-stage',3,3.50),
  (9,'Diplomas overview (hub)','Category index',4,3.45),
  ('P2','Portfolio-of-evidence assessment','Assessment-mechanics transparency (official)',3,3.60),
  ('P2','Diploma vs short courses/CPD','Regulated-vs-unregulated differentiation',2,4.05),
  ('P2','Ideal career stage','Audience-fit reassurance',3,3.50)]),
 ('Qualifi Diplomas', '22 questions | 3 categories + practical guide', [
  (1,'Which level should I start at?','Decision-guidance star; routes undecided prospects',1,4.85),
  (2,'L5 Extended to a bachelor\'s top-up','Funnel star; degree-ladder story',1,4.70),
  (3,'Is Qualifi recognised?','Provider trust anchor (Ofqual-regulated)',2,4.25),
  (3,'What is a Level 3 Diploma?','Entry-level definitional',2,4.25),
  (3,'"Extended Diploma" meaning','Unique definitional term; snippet-friendly',2,4.25),
  (3,'Degree needed for Level 7?','Experience-route hope-widener',2,4.25),
  (3,'Progression after Level 7 (MBA/LLM routes)','Ladder-completion; strongest cross-link',2,4.25),
  (8,'Level 5 entry requirements','Route clarity',2,4.10),
  (9,'Progression after Level 3','Ladder-entry story',2,4.00),
  (9,'Who Level 7 diplomas are for','Audience angle (no overlap with Gatehouse)',2,4.00),
  (11,'Level 3 entry requirements','Accessibility message',3,3.90),
  (12,'Career impact of Level 7','Sector-authority ROI across 23 tracks',2,3.85),
  (13,'L7 duration & assessment','Practicality filter',3,3.55),
  (14,'Level 3 fees','Decision-stage',3,3.50),
  (14,'Level 5 fees','Decision-stage',3,3.50),
  (14,'Level 7 fees','Decision-stage',3,3.50),
  (17,'Level 3 diplomas (hub)','Category index',4,3.45),
  (17,'Level 5 Extended diplomas (hub)','Category index',4,3.45),
  (17,'Level 7 diplomas (hub)','Category index',4,3.45),
  ('P2','Stacking diplomas over time','Ladder-mechanics; staged progression',2,4.15),
  ('P2','Do diplomas expire?','Lifetime-credential trust query',3,3.70),
  ('P2','QAN on the certificate','Verified verification-detail (official)',3,3.65)]),
]

def build_strategy_report():
    flows = []
    cover(flows, 'FAQ Strategy & Ranking Report',
          'Question selection rationale and global search-priority ranking<br/>for all 142 FAQs across 7 education providers',
          [['Questions analysed', '142'], ['Tier 1 (Very High demand)', '19'],
           ['Tier 2 (High demand)', '72'], ['Tier 3 (Medium)', '31'], ['Tier 4 (Branded/hub)', '20']])

    flows.append(Paragraph('Methodology', st_provider)); flows.append(Spacer(1, 4))
    flows.append(Paragraph('Every question was selected using three filters: <b>(a) student psychology</b> - what '
        'prospective students genuinely ask before enrolling; <b>(b) global SEO demand</b> - whether the question '
        'belongs to a globally searched query family (People Also Ask / voice-search patterns); and '
        '<b>(c) conversion role</b> - whether the answer moves the reader closer to an enquiry.', st_body))
    flows.append(Paragraph('Each question is scored 1-5 on four weighted dimensions and ranked by the resulting '
        'priority score (maximum 5.00):', st_body))
    mt = Table([[Paragraph('<b>Dimension</b>', st_tbl_b), Paragraph('<b>Weight</b>', st_tbl_b), Paragraph('<b>What it measures</b>', st_tbl_b)],
                [Paragraph('Global search demand', st_tbl), Paragraph('35%', st_tbl), Paragraph('Worldwide volume of the query family', st_tbl)],
                [Paragraph('Snippet / PAA opportunity', st_tbl), Paragraph('25%', st_tbl), Paragraph('Likelihood of featuring in answer boxes', st_tbl)],
                [Paragraph('Conversion intent', st_tbl), Paragraph('25%', st_tbl), Paragraph('Proximity of the reader to an enquiry', st_tbl)],
                [Paragraph('Ranking feasibility', st_tbl), Paragraph('15%', st_tbl), Paragraph('Realistic chance of ranking for this domain', st_tbl)]],
               colWidths=[45*mm, 18*mm, 105*mm])
    mt.setStyle(TableStyle([('BACKGROUND', (0,0), (-1,0), NAVY), ('GRID', (0,0), (-1,-1), 0.4, RULE),
                            ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, LIGHT]),
                            ('VALIGN', (0,0), (-1,-1), 'TOP'),
                            ('TOPPADDING', (0,0), (-1,-1), 4), ('BOTTOMPADDING', (0,0), (-1,-1), 4)]))
    flows.append(mt); flows.append(Spacer(1, 8))
    flows.append(Paragraph('Demand tiers are qualitative expert assessments based on query-pattern analysis; no numeric '
        'search volumes have been estimated or invented. We recommend validating tiers against Google Search Console '
        'data 30-60 days after publication.', st_note))
    flows.append(Paragraph('Duplicate-prevention was enforced project-wide: every high-value query family is targeted by '
        'exactly one provider page (e.g. "PhD vs DBA" only on the RBS page, "MBA vs EMBA" only on GAU, Level 7 '
        'definitions only on Gatehouse, the qualification-ladder angle only on Qualifi), eliminating internal keyword '
        'competition.', st_body))
    flows.append(Paragraph('Rows marked "P2" are Phase 2 boost-topic additions (practical/application questions), scored on the same model.', st_note))
    flows.append(PageBreak())

    # ----- Annex: How was this ranking derived? (client Q&A) -----
    flows.append(Paragraph('How Was This Ranking Derived?', st_provider))
    flows.append(Paragraph('Common questions about the basis of this report', st_note))
    flows.append(HRFlowable(width='100%', thickness=1.1, color=GOLD, spaceAfter=8))

    qa = [
     ('Is this ranking based on search-volume data from keyword tools?',
      'No - deliberately. This is a <b>prioritisation framework, not a traffic forecast</b>. Pre-launch '
      'volume estimates for long-tail, question-format queries are notoriously unreliable: keyword tools '
      'aggregate and round long-tail data heavily, and most "People Also Ask" questions never appear in '
      'volume databases at all. Rather than attach numbers we cannot stand behind, we state none. '
      'Every figure in this report is a <b>relative priority score</b>, not a search-volume estimate.'),
     ('So what is each score actually based on?',
      'Each question is scored 1-5 on four weighted dimensions (see Methodology): the global demand of its '
      '<b>query family</b>, its <b>featured-snippet/PAA opportunity</b>, its <b>conversion intent</b>, and its '
      '<b>ranking feasibility</b> for this domain. The demand dimension draws on established search-behaviour '
      'patterns in the education niche - families such as "MBA without GMAT", "PhD vs DBA" and "what is a '
      'top-up degree" are well-documented, evergreen high-demand patterns that surface repeatedly in '
      'People Also Ask boxes worldwide. Snippet opportunity follows established format observations: '
      'comparison ("X vs Y"), definitional ("what is...") and "how long" questions win answer boxes most often.'),
     ('What sources were used?',
      'Three source layers: (1) the client\'s own programme data (programme list and repository records); '
      '(2) official provider sources - admissions portals and awarding-body specifications - for every '
      'verifiable fact referenced in a question\'s context (durations, credits, entry routes, regulator '
      'recognition); and (3) publicly established SEO practice for question-format content. No paid '
      'keyword-tool exports were used, and none are cited.'),
     ('What decisions is the ranking used for?',
      'Three practical decisions: the <b>on-page order</b> of questions within each section (higher tiers '
      'first), the <b>priority of questions in FAQPage structured data</b> at publish time, and the '
      '<b>internal-linking plan</b> (hub, comparison and progression questions each link differently). '
      'It is a build-sequence tool - not a promise of traffic.'),
     ('How will the ranking be validated?',
      'With real data. Thirty to sixty days after publication, every tier is checked against <b>Google '
      'Search Console</b> impressions and query reports - actual evidence of what users searched and where '
      'the pages appeared - and the priorities are revised where reality differs from the assessment. '
      'Optionally, indicative volume ranges from Google Keyword Planner can be added for Tier 1 questions '
      'before launch if desired.')]
    for q, a in qa:
        flows.append(Paragraph('Q. ' + clean_text(q), st_q))
        flows.append(Paragraph(clean_text(a), st_body))
    flows.append(PageBreak())

    for name, sub, rows in RANK_DATA:
        flows.append(Paragraph(clean_text(name), st_provider))
        flows.append(Paragraph(clean_text(sub), st_note))
        flows.append(HRFlowable(width='100%', thickness=1.1, color=GOLD, spaceAfter=6))
        data = [[Paragraph('<b>Rank</b>', st_tbl_b), Paragraph('<b>Question</b>', st_tbl_b),
                 Paragraph('<b>Why it was selected</b>', st_tbl_b), Paragraph('<b>Demand tier</b>', st_tbl_b),
                 Paragraph('<b>Score</b>', st_tbl_b)]]
        for rk, q, why, tier, score in rows:
            data.append([Paragraph(str(rk), st_tbl), Paragraph(inline(q), st_tbl),
                         Paragraph(inline(why), st_tbl), Paragraph(TIER_LABEL[tier], st_tbl),
                         Paragraph(f'{score:.2f}', st_tbl)])
        t = Table(data, colWidths=[12*mm, 52*mm, 63*mm, 28*mm, 13*mm], repeatRows=1)
        t.setStyle(TableStyle([('BACKGROUND', (0,0), (-1,0), NAVY), ('GRID', (0,0), (-1,-1), 0.4, RULE),
                               ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, LIGHT]),
                               ('VALIGN', (0,0), (-1,-1), 'TOP'),
                               ('TOPPADDING', (0,0), (-1,-1), 3.5), ('BOTTOMPADDING', (0,0), (-1,-1), 3.5)]))
        flows.append(t)
        flows.append(PageBreak())

    flows.append(Paragraph('Recommendations', st_provider)); flows.append(Spacer(1, 5))
    for r in ['Place Tier 1 questions at the top of each page section - on-page order is a crawl-priority signal.',
              'Generate FAQPage structured data (JSON-LD) for all questions at publish time, Tier 1 first.',
              'Add internal links: hub questions to programme pages; comparison questions to both categories they compare; ladder/progression questions across providers (diploma to degree to master\'s).',
              'Validate demand tiers against Google Search Console impressions 30-60 days after publication and re-prioritise where actual data differs.',
              'Complete the outstanding partner confirmations (fees, English thresholds, progression agreements) before adding further specificity to answers.']:
        flows.append(Paragraph(inline(r), st_bullet, bulletText='\u2022'))
    doc = make_doc(os.path.join(OUT, 'Maverick-FAQ-Strategy-Ranking-Report.pdf'), 'FAQ Strategy & Ranking Report')
    doc.build(flows)

# ------------------------------------------------- PDF 3: blocker resolution
BLOCKERS = [
 ('Blocker 1 - Programme Durations (RBS & GAU)',
  'Initial drafts relied on third-party directory figures: RBS MBA "12-24 months", RBS MSc "18-24 months", '
  'GAU MBA "1.5-2 years" (unverified) - and the RBS BBA duration was missing entirely. Publishing an incorrect '
  'duration risks a wrong featured snippet that persists in caches even after correction.',
  ['The official Rushford admissions portal publishes a nominal duration of <b>16 months (90 ECTS)</b> consistently across MBA specialisation pages.',
   'Official Rushford MSc pages publish a three-route structure: <b>60 ECTS (~12 months)</b>, <b>90 ECTS (~18 months, master\'s thesis)</b>, <b>120 ECTS (~24 months, capstone consulting project)</b>.',
   'The official Rushford BBA page publishes <b>180 ECTS across 6 semesters (~36 months)</b>.',
   'GAU master\'s programmes are consistently described across independent sources as <b>~2 years (4 semesters)</b>, with shorter completion possible by route - supporting a published "1.5-2 years" range.'],
  'All duration answers were replaced with the official figures; the BBA duration was added; every obsolete third-party range was removed.',
  'apply.rushford.ch official course pages (MBA specialisations); rushford.ch official programme pages (MSc Business Management, BBA); corroborating independent directories for GAU.'),
 ('Blocker 2 - Accreditation & Regulation Wording',
  'It was unconfirmed whether Gatehouse and Qualifi diplomas could be described as regulated qualifications; GAU\'s '
  'recognising authorities carry country references in their full names, conflicting with the strict globally-neutral '
  'content policy; and university heritage claims (founding dates) were unverified.',
  ['<b>Gatehouse Awards</b> is confirmed as an awarding organisation <b>recognised by Ofqual</b> (Office of Qualifications and Examinations Regulation). Its Level 7 Diploma in Education Leadership & Management is verified on the official Gatehouse site as a regulated qualification: <b>QAN 610/7539/5</b>, TQT 1200, assessed by coursework and portfolio of evidence.',
   '<b>Qualifi</b> is confirmed as <b>approved and regulated by Ofqual (regulator reference RN5160)</b>; its qualifications carry unique accreditation numbers on the Regulated Qualifications Framework (e.g. Level 5 Extended Diploma in Business Management, QAN 610/1675/5, 240 credits).',
   'For GAU, the recognising authorities can be referenced by acronym alone - fully accurate and free of any country descriptor.'],
  'Regulator recognition statements were added to the Gatehouse and Qualifi content with exact regulator naming; Gatehouse assessment wording was corrected to "coursework and portfolio of evidence"; GAU\'s authorities are now referenced country-neutrally as "the higher-education authorities YODAK and YOK" alongside IACBE international accreditation; university heritage remains as neutral phrasing ("long-established public university") with no dates - the lowest-risk accurate formulation.',
  'gatehouseawards.org (official qualification specification); regulated-qualification listings confirming Gatehouse\'s Ofqual recognition; Qualifi delivery-centre specifications citing Ofqual reference RN5160 and QANs; Maverick programme data (primary) for GAU recognitions.'),
 ('Blocker 3 - UWS BA (Hons) Global Business: Top-Up Structure',
  'Three answers (programme meaning, duration, entry requirements) depended on a top-up-route assumption drawn from a single partner source.',
  ['Three independent partner institutions delivering this UWS award describe it identically: a <b>top-up degree awarded by the University of the West of Scotland</b>, completed in <b>~12 months</b>, delivered <b>100% online</b>, with entry via a <b>completed HND or equivalent qualification, or relevant work experience (subject to approval)</b>.'],
  'The top-up structure, 12-month duration and entry criteria were confirmed and firmed in the content; all assumption flags were removed.',
  'Independent partner-institution programme pages (three separate providers with consistent details).'),
 ('Blocker 4 - UCA Global MBA: Entry-Criteria Conflict',
  'The official Rushford admissions portal lists entry as a bachelor\'s degree in any discipline, while another listing of the same UCA award required minimum age 21, 3+ years\' management experience and higher English scores.',
  ['The stricter criteria belong to a <b>different delivery partner\'s route</b> to the same UCA award. The listed offer is explicitly the <b>Rushford-delivered route</b> ("Global MBA + Rushford Business School"), for which the official portal publishes: <b>bachelor\'s degree (or equivalent) in any discipline</b>.'],
  'Source hierarchy was applied: the official criteria for the correct delivery route were retained and the conflict was closed with a clarification recorded in the content notes.',
  'apply.rushford.ch official Global MBA (via UCA) course page, compared against the alternate delivery partner\'s listing.'),
]

def build_blocker_report():
    flows = []
    cover(flows, 'Publish-Blocker Resolution Report',
          'Deep-research verification and correction of the four publish-blocking issues<br/>identified in the Education FAQ content pack',
          [['Blockers identified', '4'], ['Blockers resolved', '4'],
           ['Content files updated', '7'], ['Publication status', 'On hold - awaiting approval']])
    flows.append(Paragraph('Executive Summary', st_provider)); flows.append(Spacer(1, 4))
    flows.append(Paragraph('Before publication, four issues were flagged as publish-blockers: unverified programme '
        'durations, unconfirmed accreditation wording, an unconfirmed programme structure, and conflicting entry '
        'criteria. Each was investigated against official and corroborated sources, and the approved FAQ content has '
        'been corrected accordingly. All four blockers are now closed. The remaining open items (fee amounts, exact '
        'English-test thresholds, and internal progression agreements) require partner offer sheets and cannot be '
        'resolved through public research; they are listed at the end of this report.', st_body))
    flows.append(Spacer(1, 6))
    for title, issue, findings, resolution, sources in BLOCKERS:
        flows.append(Spacer(1, 4))
        flows.append(HRFlowable(width='100%', thickness=0.8, color=RULE, spaceAfter=4))
        flows.append(Paragraph(inline(title), st_h2))
        flows.append(Paragraph('<b>The issue.</b> ' + inline(issue), st_body))
        flows.append(Paragraph('<b>Research findings.</b>', st_body))
        for f in findings:
            flows.append(Paragraph(clean_text(f), st_bullet, bulletText='\u2022'))
        flows.append(Spacer(1, 3))
        flows.append(Paragraph('<b>Resolution applied.</b> ' + inline(resolution), st_body))
        flows.append(Paragraph('<b>Sources.</b> <i>' + inline(sources) + '</i>', st_body))
    flows.append(Spacer(1, 6))
    flows.append(HRFlowable(width='100%', thickness=0.8, color=RULE, spaceAfter=4))
    flows.append(Paragraph('Bonus Finding', st_h2))
    flows.append(Paragraph('Official Rushford portal pages confirm that each MBA specialisation also carries an '
        '<b>Ofqual-regulated Level 7 Diploma award by default</b> - added to the MBA content as a value point. The '
        'portal\'s accompanying "WES approved" phrasing remains excluded under the project\'s compliance policy '
        '(equivalency claims require separately verified sources).', st_body))
    flows.append(Paragraph('Outstanding Items (require partner offer sheets)', st_h2))
    for o in ['Fee amounts and billing currencies for all providers (UCA figures are portal-published: CHF 9,900 one-time, or CHF 1,800 + 6 x CHF 1,400 - to be reconfirmed per intake).',
              'Official English-proficiency thresholds per provider and level.',
              'Portfolio progression agreements (Qualifi L7 Law to UOW LLM feeder; L5 to degree top-up receiving programmes; L3 to L5 internal ladder).',
              'GAU delivery-mode confirmation per category (esp. Psychology, EMBA, PhD) and exact entry thresholds.',
              'Accreditation numbers for the remaining three Gatehouse Level 7 diplomas.']:
        flows.append(Paragraph(clean_text(o), st_bullet, bulletText='\u2022'))
    flows.append(Spacer(1, 8))
    flows.append(Paragraph('<b>Status:</b> content is verification-complete to the limit of public sources and is held '
        'unpublished pending client approval.', st_body))
    doc = make_doc(os.path.join(OUT, 'Maverick-Blocker-Resolution-Report.pdf'), 'Publish-Blocker Resolution Report')
    doc.build(flows)

if __name__ == '__main__':
    os.makedirs(OUT, exist_ok=True)
    build_faq_pack(); print('PDF 1 built')
    build_strategy_report(); print('PDF 2 built')
    build_blocker_report(); print('PDF 3 built')

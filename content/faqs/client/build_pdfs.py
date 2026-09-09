#!/usr/bin/env python3
"""Build branded FAQ and information PDFs for the Education FAQ project.
Outputs (content/faqs/client/):
  1. Maverick-Education-FAQ-Content-Pack.pdf      — all approved FAQs, cleaned
  2. Maverick-FAQ-Strategy-Ranking-Report.pdf     — selection & ranking report (English)
  3. Maverick-Blocker-Resolution-Report.pdf       — publish-blocker resolutions
"""
import re, os, sys
from collections import Counter
from datetime import date, datetime, timezone
from decimal import Decimal
from pathlib import Path
from xml.sax.saxutils import escape

sys.path.insert(0, str(Path(__file__).resolve().parents[1] / 'tools'))
from faq_content import (ROOT, REVIEW_DATE, PROVIDERS, read_faqs, public_markdown,
                         IMMIGRATION_QUESTIONS, is_immigration_faq)
from reportlab import rl_config
rl_config.invariant = 1
os.environ.setdefault('SOURCE_DATE_EPOCH', str(int(datetime.fromisoformat(REVIEW_DATE).replace(tzinfo=timezone.utc).timestamp())))
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
st_h2          = S('h2', keepWithNext=True, fontName='Helvetica-Bold', fontSize=14, leading=18, textColor=NAVY, spaceBefore=14, spaceAfter=4)
st_h3          = S('h3', keepWithNext=True, fontName='Helvetica-Bold', fontSize=12, leading=16, textColor=GOLD, spaceBefore=10, spaceAfter=3)
st_h4          = S('h4', keepWithNext=True, fontName='Helvetica-Bold', fontSize=10.5, leading=14, textColor=GREY, spaceBefore=8, spaceAfter=2)
st_q           = S('q', keepWithNext=True,  fontName='Helvetica-Bold', fontSize=10.5, leading=15, textColor=NAVY, spaceBefore=9, spaceAfter=2)
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
    (p.path.name, p.name,
     f'{len(p.listing_categories)} category sets + practical guide'
     + (' + immigration clarification' if p.slug in IMMIGRATION_QUESTIONS else '') + f' - {p.coverage}',
     len(read_faqs(p.path)), p.programme_count)
    for p in PROVIDERS
]
TOTAL_FAQS = sum(row[3] for row in PROVIDER_ORDER)
TOTAL_PROGRAMMES = sum(row[4] for row in PROVIDER_ORDER)
TOTAL_CATEGORIES = sum(len(p.listing_categories) for p in PROVIDERS)

def parse_faq_md(path=None, *, text=None):
    """Return flowables for one cleaned provider file (no comments, no verify tables)."""
    s = Path(path).read_text(encoding='utf-8') if text is None else text
    s = public_markdown(s)
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
            flows.append(Paragraph(inline(q), st_q))
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
    doc = BaseDocTemplate(str(path), pagesize=A4,
                          leftMargin=21*mm, rightMargin=21*mm, topMargin=20*mm, bottomMargin=18*mm,
                          title=footer_label, author='Maverick Business Academy')
    frame = Frame(doc.leftMargin, doc.bottomMargin, doc.width, doc.height, id='f', leftPadding=0, rightPadding=0)

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
            canv.drawRightString(189*mm, 11*mm, 'Maverick Business Academy')
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
    flows.append(Paragraph('Updated ' + date.fromisoformat(REVIEW_DATE).strftime('%d %B %Y').lstrip('0'), st_cover_meta))
    flows.append(PageBreak())

# ---------------------------------------------------------------- PDF 1: FAQ pack
def build_faq_pack():
    flows = []
    cover(flows, 'Education FAQ Content Pack',
          f'Programme frequently asked questions<br/>{len(PROVIDERS)} providers | {TOTAL_CATEGORIES} category sets + practical guides | {TOTAL_FAQS} FAQs | {TOTAL_PROGRAMMES} supplied programme entries',
          [['Providers', str(len(PROVIDERS))], ['FAQ sets (categories)', str(TOTAL_CATEGORIES)], ['Total FAQs', str(TOTAL_FAQS)],
           ['Supplied programme entries', str(TOTAL_PROGRAMMES)], ['Content language', 'English (UK)'],
           ['Audience', 'Global']])
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
    flows.append(Paragraph('This collection covers seven programme providers, with questions grouped by '
                           'programme category and practical information. Each set addresses its listed programmes '
                           'without assuming identical entry, delivery or commercial terms. The GAU and RBS sections '
                           'also explain general US/UK immigration requirements. Exact award acceptance and individual '
                           'eligibility must be assessed for the intended purpose.', st_note))
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

RANK_QUESTION_ORDER = (
    (13,24,5,7,10,18,26,3,27,15,4,11,12,20,25,6,8,14,21,22,30,19,28,29,1,9,17,23,16,2,31,32,33,34,35),
    (16,3,6,11,2,18,30,14,5,10,12,17,24,29,22,7,8,13,20,28,32,23,26,19,25,31,1,9,15,21,27,4,33,34,35,36,37),
    (2,4,5,3,6,9,8,7,1,10,11,12),
    (2,3,4,6,7,8,1,5,9,10,11,12),
    (3,2,5,4,6,8,7,1,9,10,11,12),
    (2,3,8,4,5,6,9,7,1,10,11,12),
    (9,12,2,3,8,15,18,10,6,14,4,19,16,5,11,17,1,7,13,20,21,22),
)


def legacy_component_scores(provider):
    """Original editorial inputs are independent of the presentation reports."""
    import json
    data = json.loads((ROOT / 'inputs/priority-components.json').read_text(encoding='utf-8'))
    weights = tuple(Decimal(weight) for weight in data['weights'])
    if len(weights) != 4 or sum(weights) != 1:
        raise ValueError('Priority weights must total 1 across four dimensions')
    result = {}
    for number, record in data['providers'][provider.slug].items():
        components = tuple(record['components'])
        if len(components) != 4 or any(value not in range(1, 6) for value in components):
            raise ValueError('Priority components must contain four values from 1 to 5')
        if record['tier'] not in range(1, 5):
            raise ValueError('Priority tier must be between 1 and 4')
        score = sum(Decimal(value) * weight for value, weight in zip(components, weights))
        result[int(number)] = (components, float(score), record['tier'])
    return result


def current_rank_data():
    output = []
    for provider, (_, _, rows), positions in zip(PROVIDERS, RANK_DATA, RANK_QUESTION_ORDER):
        faqs = read_faqs(provider.path)
        immigration = {number: faq for number, faq in enumerate(faqs, 1)
                       if is_immigration_faq(provider.slug, faq)}
        historical_positions = [number for number in range(1, len(faqs) + 1) if number not in immigration]
        if len(rows) != len(historical_positions) or sorted(positions) != historical_positions:
            raise ValueError(f'Update ranking coverage for {provider.slug}; every current FAQ must map exactly once')
        components = legacy_component_scores(provider)
        current = []
        for (_, old_label, reason, tier, score), number in zip(rows, positions):
            dims = components.get(number)
            if dims:
                score = dims[1]
                tier = dims[2]
            # Reader intent, not claims about a verified benefit or measured market volume.
            faq = faqs[number - 1]
            if faq.bucket == 'Fees, Scholarships & Payments':
                reason = 'Cost, payment and funding clarity before accepting an offer'
            elif faq.bucket == 'Eligibility & Admission':
                reason = 'Clarifies route-specific eligibility and application evidence'
            elif faq.bucket == 'Careers & Outcomes':
                reason = 'Helps assess career or further-study fit without outcome promises'
            elif faq.category == 'Applying & Practical Information':
                reason = 'Practical pre-enrolment planning and responsibilities'
            elif 'recognis' in faq.question.lower() or 'accredit' in faq.question.lower():
                reason = 'Distinguishes awarding status, qualification scope and intended use'
            elif 'duration' in old_label.lower() or 'how long' in faq.question.lower():
                reason = 'Study-time and route planning; exact terms need confirmation'
            elif any(word in old_label.lower() for word in ('online', 'working', 'career')):
                reason = 'Checks delivery, attendance and work-study compatibility'
            elif 'difference' in faq.question.lower() or 'mean' in faq.question.lower():
                reason = 'Explains qualification terminology and helps compare suitable routes'
            else:
                reason = 'Programme orientation and informed course selection'
            current.append({'number': number, 'question': faq.question, 'reason': reason,
                            'tier': tier, 'score': score,
                            'components': list(dims[0]) if dims else None,
                            'basis': 'Historical component scores, recalculated' if dims else 'Historical editorial total; components not recorded'})
        current.sort(key=lambda row: (-row['score'], row['number']))
        for row in current:
            row['rank'] = 1 + sum(other['score'] > row['score'] for other in current)
        for number, faq in immigration.items():
            current.append({'number': number, 'question': faq.question,
                            'reason': 'US/UK eligibility clarification; no migration entitlement is implied',
                            'tier': None, 'score': None, 'rank': None, 'components': None,
                            'basis': 'Informational clarification; no demand score or ranking assigned'})
        output.append((provider.name, f'{len(faqs)} questions | {provider.coverage}', current))
    return output


def build_strategy_report():
    flows = []
    ranking = current_rank_data()
    tiers = Counter(row['tier'] for _, _, rows in ranking for row in rows)
    weighted = sum(row['components'] is not None for _, _, rows in ranking for row in rows)
    unscored = sum(row['score'] is None for _, _, rows in ranking for row in rows)
    cover(flows, 'FAQ Strategy & Ranking Report',
          f'Editorial question priorities for {TOTAL_FAQS} programme FAQs<br/>Question selection, reader needs and evidence limitations',
          [['Questions analysed', str(TOTAL_FAQS)],
           *[[TIER_LABEL[tier], str(tiers[tier])] for tier in range(1,5)],
           ['Informational; unscored', str(unscored)]])

    flows.append(Paragraph('Methodology and limits', st_provider))
    flows.append(Spacer(1, 5))
    flows.append(Paragraph('This is an <b>editorial prioritisation framework, not a measured search ranking, '
        'traffic forecast or claim of highest demand</b>. No keyword-tool export, search-volume dataset or '
        'Search Console data was available for this review. Historical demand tiers remain hypotheses, '
        'not independently validated market measurements.', st_body))
    flows.append(Paragraph(f'For {weighted} original questions, recorded component scores are recalculated using '
        '<b>Demand 35% + answer-format opportunity 25% + conversion intent 25% + feasibility 15%</b>, '
        'with each component scored from 1 to 5. The 25 historical practical additions retain editorial '
        'totals because their component scores were not recorded. The two immigration questions '
        'are informational clarifications and have no demand tier, score or search rank assigned. '
        'No missing components or new search-volume data have been invented.', st_body))
    flows.append(Paragraph('Every row now maps to an exact question in the current content. Rankings are sorted '
        'by score within each provider; tied scores share a rank. Unscored informational questions '
        'are shown separately at the end of the provider table. Scores are retained for planning continuity, '
        'not as a forecast of traffic or a guarantee of any educational outcome.', st_body))
    flows.append(Paragraph('There are no exact duplicate questions in the FAQ collection. Related topics such as '
        'fees, recognition and working while studying intentionally recur for different providers. Different '
        'wording does not prove separate search intent or eliminate keyword competition; actual page/query '
        'data is needed to assess that.', st_body))
    flows.append(Paragraph('US/UK immigration: GAU and RBS', st_h2))
    flows.append(Paragraph('The two immigration FAQs distinguish programme completion from visa eligibility, '
        'qualifying post-study work and permanent residence. They explain a common principle across the relevant '
        'programme categories without declaring every award suitable for immigration. These are informational '
        'clarifications; no measured demand or conversion benefit is established. The US/UK Immigration FAQ '
        'Report provides the detailed route explanations and dated source register.', st_body))
    flows.append(Paragraph('Sources: '
        '<link href="https://www.gov.uk/graduate-visa/course-you-studied" color="#122A46">GOV.UK Graduate-route requirements</link>; '
        '<link href="https://www.uscis.gov/policy-manual/volume-2-part-f-chapter-5" color="#122A46">USCIS practical-training policy</link>; '
        '<link href="https://www.uscis.gov/working-in-the-united-states/h-1b-specialty-occupations" color="#122A46">USCIS H-1B eligibility</link>. '
        'Immigration sources checked 9 September 2026.', st_note))
    flows.append(Paragraph('Search and AI visibility', st_h2))
    flows.append(Paragraph('Google documents that <b>FAQ rich results stopped appearing from 7 May 2026</b>. '
        'FAQPage structured data does not promise rich results, higher ranking or inclusion in AI Overviews. '
        'Useful, accurate answers and clear page '
        'structure remain the purpose of this content. No special markup or question order guarantees '
        'search or AI visibility.', st_body))
    flows.append(Paragraph('Official update: <link href="https://developers.google.com/search/updates#deprecating-the-faq-rich-result-feature" color="#122A46">Google Search documentation changelog</link>.', st_note))
    flows.append(PageBreak())

    for name, sub, rows in ranking:
        flows.append(Paragraph(clean_text(name), st_provider))
        flows.append(Paragraph(clean_text(sub), st_note))
        flows.append(HRFlowable(width='100%', thickness=1.1, color=GOLD, spaceAfter=6))
        data = [[Paragraph('<b>Rank</b>', st_tbl_b), Paragraph('<b>Current question</b>', st_tbl_b),
                 Paragraph('<b>Reader need</b>', st_tbl_b), Paragraph('<b>Estimated tier</b>', st_tbl_b),
                 Paragraph('<b>Score</b>', st_tbl_b)]]
        for row in rows:
            data.append([Paragraph(str(row['rank']) if row['rank'] is not None else '-', st_tbl), Paragraph(inline(row['question']), st_tbl),
                         Paragraph(inline(row['reason']), st_tbl), Paragraph(TIER_LABEL[row['tier']] if row['tier'] is not None else 'Not assigned', st_tbl),
                         Paragraph(f"{row['score']:.2f}" if row['score'] is not None else "Not scored", st_tbl)])
        t = Table(data, colWidths=[12*mm, 61*mm, 54*mm, 28*mm, 13*mm], repeatRows=1)
        t.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,0),NAVY),('GRID',(0,0),(-1,-1),0.4,RULE),
                               ('ROWBACKGROUNDS',(0,1),(-1,-1),[colors.white,LIGHT]),
                               ('VALIGN',(0,0),(-1,-1),'TOP'),
                               ('TOPPADDING',(0,0),(-1,-1),3.5),('BOTTOMPADDING',(0,0),(-1,-1),3.5)]))
        flows.append(t)
        flows.append(PageBreak())
    flows.append(Paragraph('Practical recommendations', st_provider))
    flows.append(Spacer(1, 5))
    for item in [
        'Keep the highest-priority reader questions easy to find. This is a usability choice, not a demonstrated crawl-priority signal.',
        'Confirm exact award titles, programme routes, commercial terms and any recognition requirements before relying on a particular offer.',
        'Add only relevant, confirmed internal links using current programme information and evidenced progression routes.',
        'Use Search Console query/page data and enquiry performance to refine priorities when measured data is available. Do not present an editorial tier as measured demand.',
        'Exact duplicate checks do not establish an external plagiarism percentage. No numerical originality certificate is provided by this analysis.',
    ]:
        flows.append(Paragraph(inline(item), st_bullet, bulletText='\u2022'))
    make_doc(os.path.join(OUT, 'Maverick-FAQ-Strategy-Ranking-Report.pdf'), 'FAQ Strategy & Ranking Report').build(flows)

# ------------------------------------------------- PDF 3: blocker resolution
def build_blocker_report():
    import json
    evidence = json.loads((ROOT / 'reports/verification-evidence.json').read_text(encoding='utf-8'))
    flows = []
    cover(flows, 'Programme Information<br/>& Source Verification',
          'Published specifications, interpretation and programme-specific requirements',
          [['Information areas', str(len(evidence['areas']))], ['Programme providers', str(len(PROVIDERS))],
           ['Programme FAQs', str(TOTAL_FAQS)], ['Site-page FAQs', '18']])
    flows.append(Paragraph('Overview', st_provider))
    flows.append(Spacer(1, 5))
    flows.append(Paragraph(inline(evidence['summary']), st_body))
    for area in evidence['areas']:
        flows.append(Paragraph(inline(area['title']), st_h2))
        flows.append(Paragraph('<b>Source-supported information.</b> ' + inline(area['finding']), st_body))
        flows.append(Paragraph('<b>Interpretation.</b> ' + inline(area['interpretation']), st_body))
        flows.append(Paragraph('<b>Programme details to confirm.</b> ' + inline(area['details_to_confirm']), st_body))
        for source in area['sources']:
            flows.append(Paragraph('<link href="' + escape(source['url'], {'"':'&quot;'}) + '" color="#122A46">' + inline(source['title']) + '</link> — checked ' + source['checked_on'], st_note))
    flows.append(Paragraph('Scope and limitations', st_h2))
    for item in evidence['limits']:
        flows.append(Paragraph(inline(item), st_bullet, bulletText='\u2022'))
    make_doc(os.path.join(OUT, 'Maverick-Blocker-Resolution-Report.pdf'), 'Programme Information & Source Verification').build(flows)

if __name__ == '__main__':
    os.makedirs(OUT, exist_ok=True)
    build_faq_pack(); print('PDF 1 built')
    build_strategy_report(); print('PDF 2 built')
    build_blocker_report(); print('PDF 3 built')

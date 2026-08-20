#!/usr/bin/env python3
"""Site-pages client PDFs: Homepage + Edutainment FAQ pack + selection report."""
import os
from build_pdfs import (make_doc, cover, parse_faq_md, clean_text, inline,
                        S, NAVY, GOLD, GREY, LIGHT, RULE,
                        st_provider, st_note, st_body, st_bullet, st_tbl, st_tbl_b, st_q)
from reportlab.lib.units import mm
from reportlab.lib import colors
from reportlab.platypus import Paragraph, Spacer, PageBreak, Table, TableStyle, HRFlowable

BASE = os.path.dirname(os.path.abspath(__file__))
DRAFTS = os.path.join(BASE, '..', 'drafts')

PAGES = [
 ('site-homepage.md', 'Homepage FAQs', 'Brand-level questions for the website homepage', 8,
  'Fee, programme and entry details are confirmed individually during the admissions eligibility review and may change without notice.'),
 ('edutainment.md', 'Edutainment — Educational Tours FAQs',
  'Upgraded set for the Educational Tours page (replaces the current 15 basic on-page Q&As with a structured set of 10)', 10,
  'Programme availability, inclusions and pricing are confirmed in each institution\'s written proposal and may vary by destination and season.'),
]

def build_pack():
    flows = []
    cover(flows, 'Site Pages FAQ Pack',
          'Homepage & Educational Tours (Edutainment)<br/>18 FAQs prepared for client review and approval',
          [['Pages covered', '2'], ['Homepage FAQs', '8'], ['Edutainment FAQs', '10'],
           ['Content language', 'English'], ['Audience', 'Global (country-neutral)']])
    flows.append(Paragraph('About this pack', st_provider)); flows.append(Spacer(1, 5))
    flows.append(Paragraph('The <b>Homepage set</b> answers brand-level questions a first-time visitor asks before '
        'choosing any programme: what Maverick is, how the partner-university model works, who awards the '
        'qualification, and what support is provided. The <b>Edutainment set</b> is a proposed upgrade of the '
        'current on-page FAQ list - tightened from 15 basic questions to 10 structured ones, and adding the '
        'question parents ask first: safety and supervision. All content follows the same standards as the '
        'approved provider FAQ pack: globally neutral wording, no unverified claims, direct answer-first '
        'structure.', st_body))
    flows.append(PageBreak())
    for fname, title, sub, nq, disclaimer in PAGES:
        flows.append(Paragraph(clean_text(title), st_provider))
        flows.append(Paragraph(clean_text(f'{sub} | {nq} questions'), st_note))
        flows.append(HRFlowable(width='100%', thickness=1.1, color=GOLD, spaceAfter=6))
        flows.extend(parse_faq_md(os.path.join(DRAFTS, fname)))
        flows.append(Spacer(1, 8))
        flows.append(Paragraph('<i>' + clean_text(disclaimer) + '</i>', st_note))
        flows.append(PageBreak())
    doc = make_doc(os.path.join(BASE, 'Maverick-Site-Pages-FAQ-Pack.pdf'), 'Site Pages FAQ Pack')
    doc.build(flows)

TIER = {1:'Tier 1 - Very High', 2:'Tier 2 - High', 3:'Tier 3 - Medium', 4:'Tier 4 - Branded'}
DATA = [
 ('Homepage', [
  (1,'Who awards the degree or diploma I receive?','No.1 brand-level trust objection; verification-ready answer',2,4.25),
  (1,'Are the programmes accredited and recognised?','Institution-level accreditation framing; trust anchor',2,4.25),
  (3,'How do I choose the right programme?','Guidance query; consultation funnel entry',2,4.10),
  (4,'How does the partner-university model work?','Model transparency; differentiator',2,3.90),
  (5,'Can I study from anywhere in the world?','Global-access reassurance',2,3.85),
  (6,'Which types of programmes are offered?','Portfolio orientation; ladder story',3,3.65),
  (7,'What is Maverick Business Academy?','Branded hub/anchor',4,3.45),
  (8,'What support from enquiry to graduation?','Support differentiator',3,3.40)]),
 ('Edutainment - Educational Tours', [
  (1,'How are safety and supervision handled?','Parents\' first question - NEW vs current page; strong "school trip safety" family',1,4.60),
  (2,'Edutainment vs a normal school tour','Comparison format; PAA-friendly',2,4.25),
  (3,'What is included - and are flights included?','Cost-transparency; decision-stage',2,4.10),
  (4,'Who can take part?','Eligibility clarity for institutions',2,3.90),
  (4,'Can it be customised to our curriculum?','Core institutional buying question',2,3.90),
  (4,'How early to plan + how to get a proposal?','Booking funnel; action-intent',2,3.90),
  (7,'What is Maverick Edutainment?','Branded hub/definitional',4,3.70),
  (8,'What experiences are included?','Value structure (4 experience layers)',3,3.65),
  (8,'Certificates or documented outcomes?','Tangible-outcome reassurance',3,3.65),
  (8,'Visa and travel documentation help?','Logistics reassurance for international trips',3,3.65)]),
]

def build_report():
    flows = []
    cover(flows, 'Site Pages FAQ Report',
          'Question selection rationale & priority ranking<br/>Homepage and Educational Tours FAQ sets (18 questions)',
          [['Questions analysed', '18'], ['Tier 1 (Very High)', '1'], ['Tier 2 (High)', '9'],
           ['Tier 3 (Medium)', '6'], ['Tier 4 (Branded/hub)', '2']])
    flows.append(Paragraph('Methodology', st_provider)); flows.append(Spacer(1, 4))
    flows.append(Paragraph('Identical to the approved provider FAQ Strategy & Ranking Report: each question is '
        'scored 1-5 on four weighted dimensions - global search demand of its query family (35%), '
        'featured-snippet/PAA opportunity (25%), conversion intent (25%) and ranking feasibility (15%) - '
        'giving a priority score out of 5.00. Demand tiers are qualitative expert assessments based on '
        'query-pattern analysis; no numeric search volumes are estimated or invented. Tiers are validated '
        'against Google Search Console data 30-60 days after publication.', st_body))
    flows.append(Paragraph('Uniqueness note: all 18 questions were checked against the 142 approved provider FAQs - '
        'zero duplicate query targets project-wide (now 160 unique questions).', st_note))
    flows.append(Spacer(1, 6))
    for name, rows in DATA:
        flows.append(Paragraph(clean_text(name), st_provider))
        flows.append(HRFlowable(width='100%', thickness=1.1, color=GOLD, spaceAfter=6))
        data = [[Paragraph('<b>Rank</b>', st_tbl_b), Paragraph('<b>Question</b>', st_tbl_b),
                 Paragraph('<b>Why it was selected</b>', st_tbl_b), Paragraph('<b>Demand tier</b>', st_tbl_b),
                 Paragraph('<b>Score</b>', st_tbl_b)]]
        for rk, q, why, tier, sc in rows:
            data.append([Paragraph(str(rk), st_tbl), Paragraph(inline(q), st_tbl),
                         Paragraph(inline(why), st_tbl), Paragraph(TIER[tier], st_tbl),
                         Paragraph(f'{sc:.2f}', st_tbl)])
        t = Table(data, colWidths=[12*mm, 52*mm, 63*mm, 28*mm, 13*mm], repeatRows=1)
        t.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,0),NAVY),('GRID',(0,0),(-1,-1),0.4,RULE),
                               ('ROWBACKGROUNDS',(0,1),(-1,-1),[colors.white, LIGHT]),
                               ('VALIGN',(0,0),(-1,-1),'TOP'),
                               ('TOPPADDING',(0,0),(-1,-1),3.5),('BOTTOMPADDING',(0,0),(-1,-1),3.5)]))
        flows.append(t); flows.append(Spacer(1, 10))
    flows.append(Paragraph('Edutainment: what changed vs the current page', st_provider)); flows.append(Spacer(1, 4))
    for b in ['Tightened from 15 overlapping basic questions to 10 structured ones (customisation + subject-link merged; booking + proposal merged).',
              'ADDED safety & supervision - the highest-priority parent question, absent from the current list.',
              'Destination wording kept neutral ("local and international destinations") pending the client\'s preference on naming specific destinations.',
              'Answer-first structure and bold key facts applied throughout for snippet and AI-answer readiness.']:
        flows.append(Paragraph(clean_text(b), st_bullet, bulletText='\u2022'))
    flows.append(Spacer(1, 8))
    flows.append(Paragraph('Items to confirm before publication', st_provider)); flows.append(Spacer(1, 4))
    for b in ['Homepage: approved wording for the Maverick-vs-partner positioning; "free consultation" claim; support-service list.',
              'Edutainment: destination-naming preference; safety specifics (supervision ratios, insurance, protocols); package inclusions & flights policy; certificate format; recommended booking lead time; visa-assistance scope.']:
        flows.append(Paragraph(clean_text(b), st_bullet, bulletText='\u2022'))
    doc = make_doc(os.path.join(BASE, 'Maverick-Site-Pages-FAQ-Report.pdf'), 'Site Pages FAQ Report')
    doc.build(flows)

if __name__ == '__main__':
    build_pack(); print('Site pack built')
    build_report(); print('Site report built')

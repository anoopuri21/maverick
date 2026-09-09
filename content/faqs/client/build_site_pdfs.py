#!/usr/bin/env python3
"""Branded homepage and Edutainment FAQ documents."""
import os
from collections import Counter
from build_pdfs import (make_doc, cover, parse_faq_md, clean_text, inline, ROOT, read_faqs,
                        TOTAL_FAQS, NAVY, GOLD, LIGHT, RULE,
                        st_provider, st_note, st_body, st_bullet, st_tbl, st_tbl_b, st_h2)
from reportlab.lib.units import mm
from reportlab.lib import colors
from reportlab.platypus import Paragraph, Spacer, PageBreak, Table, TableStyle, HRFlowable

BASE = os.path.dirname(os.path.abspath(__file__))
DRAFTS = ROOT / 'drafts'
PAGES = [
    ('site-homepage.md', 'Homepage FAQs', 'Brand and programme-orientation questions',
     'Programme availability, fees and entry requirements are confirmed for the selected route and intake before enrolment and may change.'),
    ('edutainment.md', 'Edutainment — Educational Tours FAQs', 'Learning objectives, participants and travel arrangements',
     "Programme availability, inclusions and pricing are confirmed in each institution's written proposal and may vary by destination and season."),
]
SITE_COUNTS = [len(read_faqs(DRAFTS / row[0])) for row in PAGES]
SITE_TOTAL = sum(SITE_COUNTS)
TIER = {1: 'Tier 1 - Very High', 2: 'Tier 2 - High', 3: 'Tier 3 - Medium', 4: 'Tier 4 - Branded'}
# Retained editorial estimates, not measured search volumes or reproducible component scores.
PRIORITIES = [
    [(5, 2, 4.25, 'Identifies the actual issuer and verification route'),
     (7, 2, 4.25, 'Separates institutional status from programme and purpose-specific recognition'),
     (6, 2, 4.10, 'Helps the reader compare suitable study options'),
     (2, 2, 3.90, 'Explains partner and delivery responsibilities'),
     (4, 2, 3.85, 'Clarifies worldwide enquiries versus route-specific attendance and access'),
     (3, 3, 3.65, 'Provides portfolio orientation without automatic progression promises'),
     (1, 4, 3.45, 'Explains Maverick to first-time visitors'),
     (8, 3, 3.40, 'Prompts confirmation of programme-specific support')],
    [(5, 1, 4.60, 'Helps institutions request the actual safety and supervision plan'),
     (2, 2, 4.25, 'Explains the learning-led itinerary model'),
     (7, 2, 4.10, 'Clarifies package inclusions, exclusions and flights'),
     (3, 2, 3.90, 'Checks participant suitability and accompanying arrangements'),
     (6, 2, 3.90, 'Addresses curriculum-linked customisation'),
     (9, 2, 3.90, 'Supports realistic booking and proposal planning'),
     (1, 4, 3.70, 'Defines the educational travel service'),
     (4, 3, 3.65, 'Explains possible experience types'),
     (8, 3, 3.65, 'Clarifies conditional certificates and learning documentation'),
     (10, 3, 3.65, 'Clarifies travel-document assistance without visa promises')],
]


def current_site_rank_data():
    output = []
    for (filename, title, _, _), rows in zip(PAGES, PRIORITIES):
        faqs = read_faqs(DRAFTS / filename)
        if sorted(row[0] for row in rows) != list(range(1, len(faqs) + 1)):
            raise ValueError(f'Update site priority coverage: {filename}')
        current = [{'number': number, 'question': faqs[number - 1].question,
                    'tier': tier, 'score': score, 'reason': reason}
                   for number, tier, score, reason in rows]
        current.sort(key=lambda row: (-row['score'], row['number']))
        for row in current:
            row['rank'] = 1 + sum(other['score'] > row['score'] for other in current)
        output.append((title, current))
    return output


def build_pack():
    flows = []
    cover(flows, 'Site Pages FAQ Pack',
          f'Homepage & Educational Tours (Edutainment)<br/>{SITE_TOTAL} frequently asked questions',
          [['Pages covered', str(len(PAGES))], ['Homepage FAQs', str(SITE_COUNTS[0])],
           ['Edutainment FAQs', str(SITE_COUNTS[1])], ['Content language', 'English (UK)'],
           ['Audience', 'Global']])
    flows.append(Paragraph('About this pack', st_provider))
    flows.append(Spacer(1, 5))
    flows.append(Paragraph('The <b>Homepage set</b> explains programme choice, partner responsibilities, '
        'awarding arrangements and support. The <b>Edutainment set</b> addresses learning objectives, '
        'participant suitability, safety planning, package terms and travel logistics. Exact academic, '
        'commercial and operational arrangements depend on the programme offer or travel proposal.', st_body))
    flows.append(PageBreak())
    for (filename, title, subtitle, disclaimer), count in zip(PAGES, SITE_COUNTS):
        flows.append(Paragraph(clean_text(title), st_provider))
        flows.append(Paragraph(clean_text(f'{subtitle} | {count} questions'), st_note))
        flows.append(HRFlowable(width='100%', thickness=1.1, color=GOLD, spaceAfter=6))
        flows.extend(parse_faq_md(DRAFTS / filename))
        flows.append(Spacer(1, 8))
        flows.append(Paragraph('<i>' + inline(disclaimer) + '</i>', st_note))
        flows.append(PageBreak())
    make_doc(os.path.join(BASE, 'Maverick-Site-Pages-FAQ-Pack.pdf'), 'Site Pages FAQ Pack').build(flows)


def build_report():
    flows = []
    ranking = current_site_rank_data()
    tiers = Counter(row['tier'] for _, rows in ranking for row in rows)
    cover(flows, 'Site Pages FAQ Report',
          f'Question selection and editorial priorities<br/>Homepage and Educational Tours ({SITE_TOTAL} questions)',
          [['Questions analysed', str(SITE_TOTAL)], *[[TIER[tier], str(tiers[tier])] for tier in range(1, 5)]])
    flows.append(Paragraph('Methodology and limits', st_provider))
    flows.append(Spacer(1, 5))
    flows.append(Paragraph('These are <b>historical editorial priority estimates</b>, retained for planning continuity, '\
        'not measured demand or search rankings. The original site-page report did not record four individual '\
        'component scores, so its totals cannot be presented as a reproducible weighted calculation. No missing '\
        'scores, search volumes or keyword-tool data have been invented. Validate priorities with actual query '\
        'and enquiry data when measured data is available.', st_body))
    flows.append(Paragraph(f'The {SITE_TOTAL} site questions were checked against the {TOTAL_FAQS} programme '\
        f'questions: {SITE_TOTAL + TOTAL_FAQS} distinct exact questions. Related themes can still overlap in '\
        'search intent. Clear answers may help readers, but there is no guarantee of snippets, rankings or AI '\
        'Overview inclusion. Google documents the end of FAQ rich results from 7 May 2026.', st_body))
    flows.append(Paragraph('Official update: <link href="https://developers.google.com/search/updates#deprecating-the-faq-rich-result-feature" color="#122A46">Google Search documentation changelog</link>.', st_note))
    for title, rows in ranking:
        flows.append(Paragraph(clean_text(title), st_h2))
        data = [[Paragraph('<b>Rank</b>', st_tbl_b), Paragraph('<b>Current question</b>', st_tbl_b),
                 Paragraph('<b>Reader need</b>', st_tbl_b), Paragraph('<b>Estimated tier</b>', st_tbl_b),
                 Paragraph('<b>Score</b>', st_tbl_b)]]
        for row in rows:
            data.append([Paragraph(str(row['rank']), st_tbl), Paragraph(inline(row['question']), st_tbl),
                         Paragraph(inline(row['reason']), st_tbl), Paragraph(TIER[row['tier']], st_tbl),
                         Paragraph(f"{row['score']:.2f}", st_tbl)])
        table = Table(data, colWidths=[12*mm, 61*mm, 54*mm, 28*mm, 13*mm], repeatRows=1)
        table.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,0),NAVY),('GRID',(0,0),(-1,-1),0.4,RULE),
                                   ('ROWBACKGROUNDS',(0,1),(-1,-1),[colors.white,LIGHT]),
                                   ('VALIGN',(0,0),(-1,-1),'TOP'),
                                   ('TOPPADDING',(0,0),(-1,-1),3.5),('BOTTOMPADDING',(0,0),(-1,-1),3.5)]))
        flows.append(table)
        flows.append(Spacer(1, 10))
    flows.append(Paragraph('Programme-specific information to confirm', st_h2))
    for item in [
        'Homepage: awarding institutions and responsibilities, programme-specific accreditation evidence, access conditions and actual support commitments.',
        'Edutainment: documented safeguarding, risk assessments, supervision, emergency procedures and insurance responsibilities; no safety promise without evidence.',
        'Edutainment: participant/age suitability, inclusions and flights, certificate availability and issuer, booking lead time, and travel-document assistance scope.',
    ]:
        flows.append(Paragraph(inline(item), st_bullet, bulletText='\u2022'))
    make_doc(os.path.join(BASE, 'Maverick-Site-Pages-FAQ-Report.pdf'), 'Site Pages FAQ Report').build(flows)


if __name__ == '__main__':
    build_pack()
    build_report()
    print('Both site-page PDFs built.')

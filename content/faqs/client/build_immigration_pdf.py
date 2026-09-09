#!/usr/bin/env python3
"""Build the scoped GAU/RBS evidence report from the ledger and canonical FAQ answers."""
import json
from datetime import date
from urllib.parse import urlparse
from xml.sax.saxutils import escape

from build_pdfs import (ROOT, PROVIDERS, read_faqs, inline, make_doc, cover, parse_faq_md,
                        Paragraph, Spacer, PageBreak, Table, TableStyle, HRFlowable,
                        NAVY, GOLD, LIGHT, RULE, colors, mm,
                        st_provider, st_h2, st_h3, st_body, st_note, st_bullet, st_tbl, st_tbl_b)
from faq_content import (IMMIGRATION_APPROVAL, IMMIGRATION_QUESTIONS, IMMIGRATION_DISCLAIMER,
                         IMMIGRATION_EVIDENCE, IMMIGRATION_REPORT_MD, IMMIGRATION_REPORT_PDF,
                         is_immigration_faq)


def load_report_data():
    evidence = json.loads(IMMIGRATION_EVIDENCE.read_text(encoding='utf-8'))
    if set(evidence['providers']) != set(IMMIGRATION_QUESTIONS):
        raise ValueError('This report is limited to GAU and RBS')
    sources = {source['id']: source for source in evidence['sources']}
    if len(sources) != len(evidence['sources']):
        raise ValueError('Duplicate evidence source ID')
    for source in sources.values():
        if source['checked_on'] != evidence['review_date']:
            raise ValueError('Record the actual review date for every source')
        url = urlparse(source['url'])
        if url.scheme != 'https' or not url.hostname:
            raise ValueError('Sources require an explicit HTTPS URL')
        if source['type'].startswith('Government') and url.hostname not in ('www.gov.uk', 'www.uscis.gov'):
            raise ValueError('Government evidence must link directly to the named authority')
    for item in evidence['sections'] + evidence['claim_map']:
        if not item['source_ids'] or not set(item['source_ids']).issubset(sources):
            raise ValueError('Every claim must map to known evidence')
    pairs = []
    for slug in evidence['providers']:
        provider = next(provider for provider in PROVIDERS if provider.slug == slug)
        faqs = [faq for faq in read_faqs(provider.path) if is_immigration_faq(slug, faq)]
        if (len(faqs) != 1 or IMMIGRATION_DISCLAIMER not in faqs[0].plain_answer
                or IMMIGRATION_APPROVAL not in faqs[0].metadata):
            raise ValueError(f'Expected one source-aligned, disclaimed FAQ for {slug}')
        pairs.append((provider, faqs[0]))
    return evidence, sources, pairs


def md_references(ids, sources):
    return ' '.join(f"[{source_id}]({sources[source_id]['url']})" for source_id in ids)


def source_links(ids, sources):
    return ' '.join('<link href="' + escape(sources[source_id]['url'], {'"': '&quot;'})
                    + '" color="#122A46">[' + str(source_id) + ']</link>' for source_id in ids)


def markdown_report(evidence, sources, pairs):
    reviewed = date.fromisoformat(evidence['review_date']).strftime('%d %B %Y').lstrip('0')
    programmes = sum(provider.programme_count for provider, _ in pairs)
    categories = sum(len(provider.listing_categories) for provider, _ in pairs)
    lines = [f"# {evidence['title']}", '', f'**Sources reviewed:** {reviewed}',
             '## 1. Overview', '', evidence['summary'], '', evidence['scope'], '',
             'This is source-backed general guidance, not confirmation of any individual award equivalence, '
             'applicant eligibility or immigration outcome.', '',
             '## 2. Frequently asked questions', '',
             'Questions about qualification recognition and immigration eligibility.', '']
    for provider, faq in pairs:
        lines += [f'### {provider.name}', '', f'**Q. {faq.question}**', '', faq.answer, '']
    lines += ['## 3. Evidence and interpretation', '']
    for section in evidence['sections']:
        lines += [f"### {section['title']}", '', section['finding'], '',
                  '**What this means:** ' + section['application'], '',
                  '**Sources:** ' + md_references(section['source_ids'], sources), '']
    lines += ['## 4. Why the clarification applies across programme categories', '',
              f'The two provider sets cover {categories} programme categories and {programmes} programme-list entries. '
              'That is a coverage count, not verification that every award is available or accepted for immigration.', '',
              '| Provider | Programme categories | Application |', '|---|---|---|']
    for row in evidence['programme_application']:
        lines.append(f"| {row['provider']} | {row['categories']} | {row['application']} |")
    lines += ['', 'Specific affirmative statements about a particular degree, sponsored transfer or visa route '
              'would require separate programme, awarding-body and applicant evidence. Current programme and individual requirements must be established.', '', '## 5. FAQ claim-to-source mapping', '']
    for row in evidence['claim_map']:
        lines.append('- ' + row['claim'] + ' ' + md_references(row['source_ids'], sources))
    lines += ['', '## 6. Source register — dates and evidence limits', '',
              'Government sources establish route requirements. Provider and scheme-owner sources establish '
              'only the contextual facts identified below; they are not individual immigration approvals.', '']
    for source in sources.values():
        lines += [f"### [{source['id']}] {source['title']}", '',
                  f"- **Publisher:** {source['publisher']}", f"- **Type:** {source['type']}",
                  f"- **Checked:** {source['checked_on']}", f"- **URL:** [{source['id']}]({source['url']})",
                  f"- **Scope/limit:** {source['scope']}", '']
    lines += ['## 7. Scope and limitations', '']
    lines.extend('- ' + item for item in evidence['exclusions'])
    lines += ['', '*' + IMMIGRATION_DISCLAIMER + '*', '']
    return '\n'.join(lines)


def paragraph(text, style=st_body):
    return Paragraph(inline(text), style)


def build_report():
    evidence, sources, pairs = load_report_data()
    programmes = sum(provider.programme_count for provider, _ in pairs)
    categories = sum(len(provider.listing_categories) for provider, _ in pairs)
    reviewed = date.fromisoformat(evidence['review_date']).strftime('%d %B %Y').lstrip('0')
    flows = []
    cover(flows, 'US / UK Immigration<br/>FAQ Report',
          'Girne American University & Rushford Business School<br/>Qualification recognition, route requirements and sources',
          [['Questions covered', str(len(pairs))], ['Programme categories covered', str(categories)],
           ['Programme-list entries', str(programmes)], ['Dated sources', str(len(sources))]])
    flows += [paragraph('1. Overview', st_provider), Spacer(1, 5),
              paragraph(evidence['summary']), paragraph(evidence['scope'], st_note),
              paragraph('2. Frequently asked questions', st_h2)]
    for provider, faq in pairs:
        flows.append(paragraph(provider.name, st_h3))
        flows.extend(parse_faq_md(text=f'**Q. {faq.question}**\n\n{faq.answer}'))
    flows.append(PageBreak())
    flows += [paragraph('3. Evidence and interpretation', st_provider), Spacer(1, 5),
              paragraph(f'Primary immigration sources and contextual evidence rechecked on {reviewed}. '
                        'This is a summary, not a complete visa checklist or an individual assessment.', st_note)]
    for section in evidence['sections']:
        flows += [paragraph(section['title'], st_h2), paragraph(section['finding']),
                  paragraph('**What this means.** ' + section['application']),
                  Paragraph('Sources: ' + source_links(section['source_ids'], sources), st_note)]
    flows.append(PageBreak())
    flows += [paragraph('4. Application across programmes', st_provider), Spacer(1, 5),
              paragraph(f'The common clarification covers {categories} programme categories and {programmes} '
                        'programme-list entries. This does not certify the availability, recognition or '
                        'immigration acceptance of every award.')]
    data = [[paragraph('Provider', st_tbl_b), paragraph('Programme categories', st_tbl_b),
             paragraph('How the FAQ applies', st_tbl_b)]]
    for row in evidence['programme_application']:
        data.append([paragraph(row['provider'], st_tbl), paragraph(row['categories'], st_tbl),
                     paragraph(row['application'], st_tbl)])
    table = Table(data, colWidths=[41*mm, 49*mm, 78*mm], repeatRows=1)
    table.setStyle(TableStyle([('BACKGROUND', (0,0), (-1,0), NAVY),
                               ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, LIGHT]),
                               ('GRID', (0,0), (-1,-1), 0.4, RULE), ('VALIGN', (0,0), (-1,-1), 'TOP'),
                               ('TOPPADDING', (0,0), (-1,-1), 5), ('BOTTOMPADDING', (0,0), (-1,-1), 5)]))
    flows += [table, Spacer(1, 8),
              paragraph('No particular visa route, credential equivalence or sponsored transfer has been '
                        'established for all these programmes. Current requirements need individual assessment.'),
              paragraph('5. FAQ claim-to-source mapping', st_h2)]
    for row in evidence['claim_map']:
        flows += [paragraph(row['claim']), Paragraph('Sources: ' + source_links(row['source_ids'], sources), st_note)]
    flows += [PageBreak(),
              paragraph('6. Source register', st_provider), Spacer(1, 5),
              paragraph('Every link below is clickable and its full URL is shown. The review date records when '
                        'the source was checked, not a new publication date or a guarantee of future rules.', st_note)]
    for source in sources.values():
        flows += [paragraph(f"[{source['id']}] {source['title']}", st_h3),
                  paragraph(f"{source['publisher']} | {source['type']} | Checked: {source['checked_on']}", st_note),
                  Paragraph('<link href="' + escape(source['url'], {'"': '&quot;'}) + '" color="#122A46">'
                            + inline(source['url']) + '</link>', st_note),
                  paragraph('**Scope and limit.** ' + source['scope'])]
    flows += [paragraph('7. Scope and limitations', st_h2)]
    for item in evidence['exclusions']:
        flows.append(Paragraph(inline(item), st_bullet, bulletText='\u2022'))
    flows += [Spacer(1, 6), paragraph(IMMIGRATION_DISCLAIMER, st_note)]
    md_path = ROOT / 'reports' / IMMIGRATION_REPORT_MD
    pdf_path = ROOT / 'client' / IMMIGRATION_REPORT_PDF
    md_path.write_text(markdown_report(evidence, sources, pairs), encoding='utf-8')
    make_doc(pdf_path, 'US / UK Immigration FAQ Report — GAU & RBS').build(flows)
    return md_path, pdf_path


if __name__ == '__main__':
    md, pdf = build_report()
    print(f'Built {md.name} and {pdf.name}.')

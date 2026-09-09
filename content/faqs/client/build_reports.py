#!/usr/bin/env python3
"""Generate audience-facing Markdown reports from the canonical FAQ data and evidence."""
from collections import Counter
from datetime import date
import json
import re

from build_pdfs import ROOT, PROVIDERS, current_rank_data, TIER_LABEL
from faq_content import REVIEW_DATE, SITE_FILES, IMMIGRATION_SECTION, read_faqs, normalise
from audit_faqs import country_policy_hits

REPORTS = ROOT / 'reports'
UPDATED = date.fromisoformat(REVIEW_DATE).strftime('%d %B %Y').lstrip('0')


def write_report(name, lines):
    (REPORTS / name).write_text('\n'.join(line.rstrip() for line in lines).rstrip() + '\n', encoding='utf-8')


def source_lines(sources):
    lines = []
    for number, source in enumerate(sources, 1):
        checked = f" — checked {source['checked_on']}" if source.get('checked_on') else ''
        lines.append(f"- {source['title']}{checked}: [{number}]({source['url']})")
    return lines


def programme_coverage():
    rows = []
    for provider in PROVIDERS:
        faqs = read_faqs(provider.path)
        rows.append((provider, faqs))
    return rows


def coverage_table(rows):
    table = ['| Provider | Programme categories | FAQs | Programme-list entries |', '|---|---:|---:|---:|']
    for provider, faqs in rows:
        table.append(f'| {provider.name} | {len(provider.listing_categories)} | {len(faqs)} | {provider.programme_count} |')
    table.append(f'| **Total** | **{sum(len(p.listing_categories) for p, _ in rows)}** | **{sum(len(q) for _, q in rows)}** | **{sum(p.programme_count for p, _ in rows)}** |')
    return table


def build_provider_reports():
    immigration = json.loads((REPORTS / 'immigration-evidence-2026-09-09.json').read_text())
    for provider, (_, _, rows) in zip(PROVIDERS, current_rank_data()):
        faqs = read_faqs(provider.path)
        base = 'rushford' if provider.slug == 'rushford-business-school' else provider.slug
        scored = [row for row in rows if row['score'] is not None]
        tiers = Counter(row['tier'] for row in scored)
        lines = [f'# FAQ Selection & Priority Report — {provider.name}', '', f'**Updated:** {UPDATED}',
                 f'**Questions:** {len(faqs)} · **Programme categories:** {len(provider.listing_categories)} · **Programme-list entries:** {provider.programme_count}', '',
                 '## 1. Purpose and coverage', '',
                 'The questions support programme selection, admissions, costs, study commitments and further-study '
                 'or career planning. Category-level information applies to the listed programmes without assuming '
                 'identical entry, delivery or commercial terms. Programme-list coverage does not independently '
                 'confirm the availability or recognition of every award.', '',
                 f'**Coverage:** {provider.coverage}.', '',
                 '## 2. Priority methodology', '',
                 'Priorities are editorial estimates, not measured search rankings, search volumes or traffic '
                 'forecasts. Recorded component scores use Demand (35%), Answer-format opportunity (25%), '
                 'Conversion intent (25%) and Feasibility (15%), each on a 1–5 scale. Where components were not '
                 'recorded, only the existing editorial total is retained. Missing components are not inferred.', '',
                 'Informational immigration questions have no assigned tier, score or rank. Similar themes may '
                 'occur for different providers; unique wording does not prove separate search intent.', '',
                 '| Estimated tier | Questions |', '|---|---:|']
        for tier in range(1,5):lines.append(f'| {TIER_LABEL[tier]} | {tiers[tier]} |')
        lines += [f'| Informational — not scored | {len(rows)-len(scored)} |', '',
                  '## 3. Question register and rationale', '',
                  '| ID | Question | Reader need | Estimated tier | D/S/C/F | Score |', '|---|---|---|---|---|---:|']
        for row in sorted(rows, key=lambda row:row['number']):
            dims = '/'.join(map(str,row['components'])) if row['components'] else 'Not recorded'
            tier = TIER_LABEL[row['tier']] if row['tier'] is not None else 'Not assigned'
            score = f"{row['score']:.2f}" if row['score'] is not None else 'Not scored'
            lines.append(f"| Q{row['number']} | {row['question']} | {row['reason']} | {tier} | {dims} | {score} |")
        if any(faq.category == IMMIGRATION_SECTION for faq in faqs):
            lines += ['', '## 4. US/UK immigration guidance', '',
                      'Completing a programme does not, on its own, establish visa or permanent-residence '
                      'eligibility. An individual may qualify through an applicable route if all its requirements '
                      'are met. Academic evidence, post-study work and independently qualifying work/residence '
                      'routes must be assessed separately.', '',
                      'The HPI list finding concerns qualifications awarded from 1 November 2025 to 31 October '
                      '2026 only. Neither GAU nor RBS appears on that list. Other award periods and partner-issued '
                      'qualifications require their own checks. Historical academic transfer or provider '
                      'certification is not immigration permission.', '',
                      'The immigration question is an informational clarification and is not assigned a search-demand score.', '',
                      '**Sources checked 9 September 2026:**']
            lines.extend(source_lines([source for source in immigration['sources'] if source['id'] in (1,2,3,4,5,6,7)]))
        lines += ['', '## Information references and limitations', '',
                  'Programme-specific award titles, eligibility, fees, delivery and support should be checked '
                  'against the current written offer. Qualification acceptance is determined for the intended '
                  'purpose by the relevant receiving institution, employer, evaluator or authority. The '
                  'Programme Information & Source Verification report explains these distinctions.', '']
        urls = sorted({url.rstrip('.,') for faq in faqs for url in re.findall(r'https://[^\s;<>]+', faq.metadata)})
        for number,url in enumerate(urls,1):lines.append(f'- [{number}]({url})')
        lines += ['', 'Clear answers and relevant internal links can support reader understanding, but neither '
                  'question order nor structured data guarantees ranking, traffic or AI inclusion. Google '
                  'documents the end of FAQ rich results from 7 May 2026. '
                  '[1](https://developers.google.com/search/updates#deprecating-the-faq-rich-result-feature)']
        write_report(f'{base}-faq-selection-report.md', lines)


def build_verification_reports():
    data = json.loads((REPORTS/'verification-evidence.json').read_text())
    lines = [f"# {data['title']}", '', f'**Updated:** {UPDATED}', '',
             'Programme/provider sources checked 7 September 2026; immigration sources checked 9 September 2026.', '',
             '## Overview', '', data['summary']]
    summary = ['# Programme Verification Summary', '', f'**Updated:** {UPDATED}', '', data['summary'], '',
               'The findings below distinguish published information from the details that need to be established '
               'for the selected programme or individual purpose.']
    for area in data['areas']:
        lines += ['', f"## {area['title']}", '', '**Source-supported information.** '+area['finding'], '',
                  '**Interpretation.** '+area['interpretation'], '',
                  '**Programme details to confirm.** '+area['details_to_confirm'], '', '**Sources:**']
        lines.extend(source_lines(area['sources']))
        summary += ['', f"## {area['title']}", '', area['finding'], '',
                    '**Details to establish:** '+area['details_to_confirm'], '']
        summary.extend(source_lines(area['sources']))
    for target in (lines,summary):
        target += ['', '## Scope and limitations', '']
        target.extend('- '+item for item in data['limits'])
    write_report('publish-blocker-resolution-report.md',lines)
    write_report('verification-update-2026-09-07.md',summary)


def build_quality_reports():
    rows = programme_coverage()
    faqs = [faq for _, items in rows for faq in items]
    site = [faq for slug in SITE_FILES for faq in read_faqs(ROOT/'drafts'/f'{slug}.md')]
    all_faqs = faqs+site
    duplicates = sum(count-1 for count in Counter(normalise(faq.question) for faq in all_faqs).values() if count>1)
    answered = sum(bool(faq.plain_answer) for faq in all_faqs)
    referenced = sum('| Source:' in faq.metadata for faq in all_faqs)
    geo = sum(bool(country_policy_hits(p.path,p.path.read_text())) for p,_ in rows)
    core = sum(faq.category.startswith('Category ') for faq in faqs)
    practical = sum(faq.category=='Applying & Practical Information' for faq in faqs)
    immigration = sum(faq.category==IMMIGRATION_SECTION for faq in faqs)
    common = coverage_table(rows)
    lines = ['# FAQ Content Quality Report', '', f'**Updated:** {UPDATED}', '',
             f'The collection contains **{len(faqs)} programme FAQs and {len(site)} site-page FAQs**, '
             f'for a total of **{len(all_faqs)} questions**. Coverage counts refer to programme-list entries, '
             'not independent recognition or availability decisions.', '', '## 1. Coverage', '', *common, '',
             '## 2. Content checks', '', '| Check | Result |', '|---|---:|',
             f'| Questions with an answer | {answered}/{len(all_faqs)} |',
             f'| Questions with an evidence or general-guidance reference | {referenced}/{len(all_faqs)} |',
             f'| Exact duplicate question strings | {duplicates} |',
             f'| Programme files with geographic references outside the defined information scope | {geo} |',
             f'| US/UK immigration clarification questions | {immigration} |', '',
             'The geographic scope includes institutional proper names and the two GAU/RBS US/UK immigration '
             'questions. These are content checks, not an immigration assessment or a certificate of academic recognition.', '',
             '## 3. Interpretation', '',
             '- One programme\'s duration, credits, entry requirements, assessment or fee structure is not assumed to apply to all routes.',
             '- Institutional certification, programme accreditation, qualification regulation, credit volume and acceptance for a particular purpose are treated separately.',
             '- Programme details that require confirmation are expressed conditionally rather than presented as guaranteed services or outcomes.',
             '- The immigration answers retain the distinction between programme completion and individual eligibility, together with the general-information disclaimer.', '',
             '## 4. Evidence limitations', '',
             'Each question carries evidence context or a general-guidance reference. This does not mean every '
             'programme-specific detail has been independently verified. Exact awards, commercial terms, '
             'progression arrangements and operational travel safeguards need the relevant current documents.', '',
             'Exact-string uniqueness does not prove zero topic overlap or keyword competition. It also does not '
             'establish an external plagiarism percentage. No numerical originality certificate, measured search '
             'volume, traffic forecast or guarantee of AI visibility is provided.', '',
             'Programme and immigration source references are detailed in the Programme Information & Source '
             'Verification report and the GAU/RBS US/UK Immigration FAQ Report.']
    write_report('quality-audit-report.md',lines)
    lines = ['# Programme FAQ Coverage Summary', '', f'**Updated:** {UPDATED}', '',
             'The programme FAQs are grouped by academic category, with additional practical and immigration '
             'information where relevant. The following counts are derived from the current collection.', '',
             '## Provider coverage', '', *common, '', '## Question structure', '',
             '| Section type | Questions |', '|---|---:|', f'| Programme-category questions | {core} |',
             f'| Applying and practical information | {practical} |', f'| US/UK immigration clarification | {immigration} |',
             f'| **Programme total** | **{len(faqs)}** |', f'| Homepage and educational travel | {len(site)} |',
             f'| **Complete collection** | **{len(all_faqs)}** |', '',
             '## Programme applicability', '',
             'Category-level answers support the programmes listed within that category. Where duration, '
             'credit structure, entry, delivery or assessment differs, the answer directs readers to the '
             'actual route. The programme list is a coverage reference, not evidence that every intake or '
             'qualification is currently available.', '',
             'The GAU and RBS immigration questions explain a common principle across their respective '
             'categories: completing a programme alone does not establish migration eligibility. They do '
             'not certify identical immigration acceptance of every award.', '',
             '## Supporting information', '',
             '- The provider priority reports explain each question\'s reader need and the limits of editorial scoring.',
             '- The Programme Information & Source Verification report identifies published facts and programme-specific details to confirm.',
             '- The GAU/RBS US/UK Immigration FAQ Report provides the route explanations, dated source register and claim mapping.',
             '- The Site Pages FAQ Report explains the homepage and educational-travel question sets.']
    write_report('phase2-completion-report.md',lines)


def build_reports():
    build_provider_reports()
    build_verification_reports()
    build_quality_reports()


if __name__=='__main__':
    build_reports()
    print('Eleven programme, coverage and verification reports generated.')

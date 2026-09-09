#!/usr/bin/env python3
"""Local review checks only: never imports FAQs into Laravel or installs CI checks."""
import argparse
from collections import Counter, defaultdict
import hashlib
import json
from pathlib import Path
import re
import sys

from presentation import presentation_issues

from faq_content import (ROOT, REVIEW_DATE, PROVIDERS, SITE_FILES, QUESTION, public_markdown,
                         read_faqs, parse_faqs, normalise, coverage_results, schema_for, programme_listing,
                         IMMIGRATION_APPROVAL, IMMIGRATION_SECTION, IMMIGRATION_QUESTIONS,
                         IMMIGRATION_DISCLAIMER, IMMIGRATION_REPORT_PDF, IMMIGRATION_EVIDENCE,
                         is_immigration_faq)

PDF_NAMES = (
    'Maverick-Education-FAQ-Content-Pack.pdf',
    'Maverick-FAQ-Strategy-Ranking-Report.pdf',
    'Maverick-Blocker-Resolution-Report.pdf',
    'Maverick-Site-Pages-FAQ-Pack.pdf',
    'Maverick-Site-Pages-FAQ-Report.pdf',
    IMMIGRATION_REPORT_PDF,
)
PROTECTED_NAMES = (
    'University of the West of Scotland', 'University of Wolverhampton',
    'Girne American University',
)
KNOWN_RETIRED_CLAIMS = (
    'without paying an additional fee', 'and Rushford\'s do', 'dispute-proof',
    'one of the fastest recognised ways', 'widest EMBA portfolios available',
    'establishes you as a published authority', 'location is not a barrier',
    'WES approved', 'WES recognised', 'WES recognized', 'guaranteed visa',
    'guaranteed citizenship', '100% placement', '100% job guarantee',
)
INTERNAL_LEAK = re.compile(r'<!--|-->|\[VERIFY|Facts to Verify|Target keyword:|STATUS:|<\/?(?:b|i|p|strong|em)>')


def country_terms():
    import pycountry
    terms = {country.name for country in pycountry.countries}
    terms.update(getattr(country, 'official_name', country.name) for country in pycountry.countries)
    terms.update(('UK', 'USA', 'UAE', 'United States', 'Britain', 'British', 'Swiss', 'Scottish',
                  'Indian', 'Chinese', 'North Cyprus', 'Scotland', 'England', 'Europe', 'European',
                  'Asia', 'Asian', 'London', 'Delhi', 'Dubai', 'Lucerne', 'Geneva', 'Canterbury',
                  'Wolverhampton', 'Girne'))
    return terms


def country_hits(text):
    for name in PROTECTED_NAMES:
        text = re.sub(re.escape(name), '', text, flags=re.I)
    hits = {term for term in country_terms()
            if re.search(r'(?<!\w)' + re.escape(term) + r'(?!\w)', text, re.I)}
    # Do not mistake the ordinary pronoun "us" for the country abbreviation.
    if re.search(r'\bUS\b', text):
        hits.add('US')
    return sorted(hits)


def country_policy_hits(path, text):
    """Allow US/UK only inside an exact, explicitly authorised FAQ, not its whole file."""
    path = Path(path).resolve()
    visible = public_markdown(text)
    faqs = parse_faqs(text)
    matches = list(QUESTION.finditer(visible))
    if path.parent == (ROOT / 'approved').resolve():
        for index in range(len(faqs) - 1, -1, -1):
            faq = faqs[index]
            if not is_immigration_faq(path.stem, faq) or IMMIGRATION_APPROVAL not in faq.metadata:
                continue
            start = matches[index].start()
            end = matches[index + 1].start() if index + 1 < len(matches) else len(visible)
            boundary = re.search(r'^#{2,3} |^---\s*$', visible[matches[index].end():end], re.M)
            if boundary:
                end = matches[index].end() + boundary.start()
            block = re.sub(r'\b(?:US|UK)\b', '', visible[start:end])
            visible = visible[:start] + block + visible[end:]
    return country_hits(visible)


def immigration_policy_errors(path, faqs):
    path = Path(path).resolve()
    approved = (path.parent == (ROOT / 'approved').resolve()
                and path.stem in IMMIGRATION_QUESTIONS)
    items = [faq for faq in faqs if faq.category == IMMIGRATION_SECTION]
    errors = []
    if not approved:
        if items:
            errors.append(f'{path.name}: immigration section is not authorised for this provider/page')
        return errors
    if len(items) != 1 or not is_immigration_faq(path.stem, items[0]):
        return [f'{path.name}: expected exactly the one authorised immigration question']
    faq = items[0]
    if IMMIGRATION_APPROVAL not in faq.metadata:
        errors.append(f'{path.name}: missing scoped exception approval marker')
    if normalise(IMMIGRATION_DISCLAIMER) not in normalise(faq.plain_answer):
        errors.append(f'{path.name}: missing mandatory immigration disclaimer')
    return errors


def run_audit(write_schema=False, check_pdfs=False):
    errors, warnings, files, all_faqs, coverage = [], [], [], [], []
    question_locations = defaultdict(list)
    answer_locations = defaultdict(list)
    keyword_locations = defaultdict(list)
    paths = [provider.path for provider in PROVIDERS] + [ROOT / 'drafts' / f'{slug}.md' for slug in SITE_FILES]
    for path in paths:
        text = path.read_text(encoding='utf-8')
        try:
            visible = public_markdown(text)
            faqs = read_faqs(path)
        except ValueError as exc:
            errors.append(f'{path.name}: {exc}')
            continue
        if not faqs or len(faqs) != len(re.findall(r'^\*\*Q\.', visible, re.M)):
            errors.append(f'{path.name}: missing or unparsed questions')
        if INTERNAL_LEAK.search(visible):
            errors.append(f'{path.name}: visible internal markup')
        hits = country_policy_hits(path, text)
        if hits:
            errors.append(f'{path.name}: geographic terms outside institution names: {hits}')
        errors.extend(immigration_policy_errors(path, faqs))
        for claim in KNOWN_RETIRED_CLAIMS:
            if claim.casefold() in visible.casefold():
                errors.append(f'{path.name}: retired claim remains visible: {claim}')
        categories = defaultdict(list)
        inventory = []
        for number, faq in enumerate(faqs, 1):
            location = f'{path.stem}:Q{number}'
            words = len(faq.plain_answer.split())
            question_locations[normalise(faq.question)].append(location)
            answer_locations[normalise(faq.plain_answer)].append(location)
            keyword_locations[normalise(faq.keyword)].append(location)
            categories[faq.category].append(faq)
            if not faq.plain_answer:
                errors.append(f'{location}: empty answer')
            if words < 30:
                warnings.append(f'{location}: short answer ({words} words), reviewed for usefulness')
            if not faq.keyword or '| Source:' not in faq.metadata:
                errors.append(f'{location}: missing keyword or source metadata')
            if any(token in faq.plain_answer for token in ('**', '|---', '<!--', '[VERIFY')):
                errors.append(f'{location}: export formatting leak')
            if not faq.question.endswith('?'):
                errors.append(f'{location}: question must end in a question mark')
            for key in faq.verification_ids:
                if f'| {key} |' not in text:
                    errors.append(f'{location}: {key} has no verification-table entry')
            inventory.append({'id': location, 'question': faq.question, 'words': words,
                              'open_verification_ids': list(faq.verification_ids),
                              'country_exception': IMMIGRATION_APPROVAL if is_immigration_faq(path.stem, faq) else None})
        for category, items in categories.items():
            if category.startswith('Category '):
                if not 5 <= len(items) <= 10:
                    errors.append(f'{path.name}: {category} has {len(items)} FAQs; expected 5–10')
                needed = {'General Information', 'Eligibility & Admission', 'Fees, Scholarships & Payments', 'Careers & Outcomes'}
                missing = needed - {faq.bucket for faq in items}
                if missing:
                    errors.append(f'{path.name}: {category} lacks buckets: {sorted(missing)}')
            elif category == 'Applying & Practical Information' and not 3 <= len(items) <= 5:
                errors.append(f'{path.name}: expected 3–5 practical questions')
        provider = next((p for p in PROVIDERS if p.path == path), None)
        if provider:
            for row in coverage_results(provider):
                coverage.append({'provider': provider.slug, **row})
                if row['missing']:
                    errors.append(f'{provider.slug}/{row["listing_category"]}: missing supplied coverage: {row["missing"]}')
            schema_path = ROOT / 'schema' / f'{provider.slug}.jsonld'
            expected = schema_for(faqs)
            if write_schema:
                schema_path.parent.mkdir(exist_ok=True)
                schema_path.write_text(json.dumps(expected, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
            try:
                actual = json.loads(schema_path.read_text(encoding='utf-8'))
                if actual != expected:
                    errors.append(f'{schema_path.name}: schema differs from current visible answers')
            except (OSError, ValueError) as exc:
                errors.append(f'{schema_path.name}: invalid or missing schema: {exc}')
        all_faqs.extend(faqs)
        open_ids = re.findall(r'^\| ([A-Z]+-\d{2}) \|', text, re.M)
        files.append({'file': str(path.relative_to(ROOT)), 'faqs': len(faqs),
                      'categories': {key: len(value) for key, value in categories.items()},
                      'open_verification_items': open_ids,
                      'sha256': hashlib.sha256(text.encode()).hexdigest(), 'questions': inventory})
    for label, locations in (('question', question_locations), ('answer', answer_locations)):
        for value, occurrences in locations.items():
            if value and len(occurrences) > 1:
                errors.append(f'Duplicate exact {label}: {occurrences}')
    duplicate_keywords = {key: rows for key, rows in keyword_locations.items() if key and len(rows) > 1}
    if duplicate_keywords:
        warnings.append(f'Repeated keyword strings require intent review: {duplicate_keywords}')
    listing = programme_listing()
    mapped = [key for provider in PROVIDERS for key in provider.listing_categories]
    if Counter(mapped) != Counter(listing.keys()):
        errors.append('Listing categories do not map one-to-one to the provider catalogue')
    presentation_files = [*sorted((ROOT / 'reports').glob('*.md')),
                          *sorted((ROOT / 'reports').glob('*.json')),
                          ROOT.parents[1] / 'public/downloads/faqs/index.html']
    for path in presentation_files:
        issues = presentation_issues(path.read_text(encoding='utf-8'))
        if issues:
            errors.append(f'{path.name}: internal presentation context: {issues}')
    pdf_results = []
    if check_pdfs:
        from pypdf import PdfReader
        for name in PDF_NAMES:
            path = ROOT / 'client' / name
            try:
                reader = PdfReader(path, strict=True)
                text = '\n'.join(page.extract_text() or '' for page in reader.pages)
                metadata = ' '.join(str(value) for value in (reader.metadata or {}).values())
                context = presentation_issues(text + ' ' + metadata)
                if context:
                    errors.append(f'{name}: internal presentation context: {context}')
                if INTERNAL_LEAK.search(text) or 'Phase 2 addition' in text or 'Provider-level questions' in text:
                    errors.append(f'{name}: client PDF contains internal/formatting junk')
                if '\u25a0' in text or '\ufffd' in text:
                    errors.append(f'{name}: missing-glyph placeholder in client PDF')
                expected_faqs = []
                if name == PDF_NAMES[0]:
                    expected_faqs = [faq for provider in PROVIDERS for faq in read_faqs(provider.path)]
                elif name == PDF_NAMES[3]:
                    expected_faqs = [faq for slug in SITE_FILES for faq in read_faqs(ROOT / 'drafts' / f'{slug}.md')]
                elif name == IMMIGRATION_REPORT_PDF:
                    expected_faqs = [faq for provider in PROVIDERS for faq in read_faqs(provider.path)
                                     if is_immigration_faq(provider.slug, faq)]
                    ledger = json.loads(IMMIGRATION_EVIDENCE.read_text(encoding='utf-8'))
                    linked_urls = {
                        annotation.get_object().get('/A', {}).get('/URI')
                        for page in reader.pages for annotation in page.get('/Annots', [])
                    }
                    for source in ledger['sources']:
                        if source['url'] not in linked_urls:
                            errors.append(f'{name}: missing clickable primary/context source {source["id"]}')
                if expected_faqs:
                    if len(re.findall(r'\bQ\.', text)) != len(expected_faqs):
                        errors.append(f'{name}: PDF question count differs from markdown')
                    normalized = normalise(text)
                    for faq in expected_faqs:
                        if normalise(faq.question) not in normalized:
                            errors.append(f'{name}: missing question: {faq.question}')
                public = ROOT.parents[1] / 'public/downloads/faqs' / name
                if not public.exists() or public.read_bytes() != path.read_bytes():
                    errors.append(f'{name}: PDF preview copy is out of sync')
                pdf_results.append({'file': name, 'pages': len(reader.pages), 'bytes': path.stat().st_size})
            except Exception as exc:
                errors.append(f'{name}: {exc}')
    return {
        'review_date': REVIEW_DATE,
        'scope': 'Repository review files and PDF artefacts, not production database contents or universal accreditation',
        'publication': 'No website/DB import. Schema is an unpublished local review export.',
        'country_exception': {'approval': IMMIGRATION_APPROVAL, 'providers': list(IMMIGRATION_QUESTIONS),
                              'scope': 'US and UK tokens only inside the two exact authorised immigration FAQs'},
        'result': 'PASS' if not errors else 'FAIL',
        'totals': {'providers': len(PROVIDERS), 'programme_categories': len(mapped),
                   'supplied_programme_entries': sum(map(len, listing.values())),
                   'provider_faqs': sum(item['faqs'] for item in files if item['file'].startswith('approved/')),
                   'site_faqs': sum(item['faqs'] for item in files if item['file'].startswith('drafts/')),
                   'all_faqs': len(all_faqs),
                   'immigration_faqs': sum(faq.category == IMMIGRATION_SECTION for faq in all_faqs),
                   'open_verification_items': sum(len(item['open_verification_items']) for item in files)},
        'limitations': ['Country check uses a scoped two-question US/UK exception, a curated geographic lexicon and manual review.',
                        'Exact-string uniqueness is not proof of no semantic overlap or keyword cannibalisation.',
                        'No third-party plagiarism certificate or numerical similarity percentage was obtained.',
                        'Coverage of an owner-supplied programme entry does not verify current award availability.'],
        'errors': errors, 'warnings': warnings, 'coverage': coverage, 'pdfs': pdf_results, 'files': files,
    }


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--write-schema', action='store_true', help='Regenerate only content/faqs/schema, never publish')
    parser.add_argument('--pdfs', action='store_true', help='Check all review PDFs and their preview copies')
    parser.add_argument('--json', type=Path, help='Save the internal audit result outside audience-facing reports')
    args = parser.parse_args()
    result = run_audit(args.write_schema, args.pdfs)
    if args.json:
        destination = args.json.resolve()
        if not destination.is_relative_to((ROOT / 'internal').resolve()):
            parser.error('Machine QA results must remain under content/faqs/internal, outside presentation reports')
        destination.parent.mkdir(parents=True, exist_ok=True)
        destination.write_text(json.dumps(result, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    print(f'{result["result"]}: {result["totals"]}')
    for message in result['errors']:
        print('ERROR:', message)
    for message in result['warnings']:
        print('NOTE:', message)
    print('Partner confirmations remain open; a passing technical audit is not publication approval.')
    return 1 if result['errors'] else 0


if __name__ == '__main__':
    sys.exit(main())

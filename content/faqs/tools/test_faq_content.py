"""Offline regression checks for review rendering and content exports."""
import json
from pathlib import Path
import sys
import tempfile
import unittest
from unittest.mock import patch

from faq_content import (ROOT, PROVIDERS, SITE_FILES, parse_faqs, read_faqs, public_markdown,
                         plain_text, schema_for, coverage_results, programme_listing)
from audit_faqs import run_audit, country_hits, country_policy_hits, immigration_policy_errors, PDF_NAMES
from presentation import presentation_issues

sys.path.insert(0, str(ROOT / 'client'))
from build_pdfs import (make_doc, parse_faq_md, current_rank_data, legacy_component_scores,
                        TOTAL_FAQS, TOTAL_PROGRAMMES, TOTAL_CATEGORIES)
from build_site_pdfs import current_site_rank_data, SITE_TOTAL
from build_immigration_pdf import load_report_data, markdown_report
from faq_content import (IMMIGRATION_APPROVAL, IMMIGRATION_SECTION, IMMIGRATION_QUESTIONS,
                         IMMIGRATION_DISCLAIMER, IMMIGRATION_REPORT_PDF)

FIXTURE = '''# Example
<!-- STATUS: PRIVATE -->
## Category 1 — Example
### General Information
<!-- Target keyword: "example first" | Source: official specification -->
**Q. What is the first question?**

A **bold answer
across lines** with a useful explanation.
<!-- [VERIFY: TEST-01 — internal note, never public.] -->

| Feature | First | Second |
|---|---|---|
| Credits | 60 | 90 |

### Eligibility & Admission
<!-- Target keyword: "example second" | Source: current offer -->
**Q. What is the next question?**

Check the current terms.

- A document
- A transcript

---

## Facts to Verify
PRIVATE VERIFICATION TABLE
'''


class ParserTests(unittest.TestCase):
    def test_comments_and_backlog_are_never_public(self):
        visible = public_markdown(FIXTURE)
        for token in ('STATUS', 'VERIFY', 'PRIVATE', 'Target keyword', '<!--'):
            self.assertNotIn(token, visible)

    def test_unbalanced_comment_fails_closed(self):
        with self.assertRaises(ValueError):
            public_markdown(FIXTURE + '<!-- unfinished')

    def test_answers_do_not_swallow_next_heading(self):
        faqs = parse_faqs(FIXTURE)
        self.assertEqual(len(faqs), 2)
        self.assertEqual(faqs[0].bucket, 'General Information')
        self.assertEqual(faqs[1].bucket, 'Eligibility & Admission')
        self.assertNotIn('Eligibility', faqs[0].answer)
        self.assertNotIn('PRIVATE', faqs[1].answer)

    def test_keywords_stay_with_the_correct_question(self):
        faqs = parse_faqs(FIXTURE)
        self.assertEqual(faqs[0].keyword, 'example first')
        self.assertEqual(faqs[1].keyword, 'example second')
        self.assertIn('current offer', faqs[1].metadata)

    def test_multiline_bold_does_not_leak_asterisks(self):
        text = parse_faqs(FIXTURE)[0].plain_answer
        self.assertIn('bold answer across lines', text)
        self.assertNotIn('*', text)

    def test_table_exports_preserve_column_relationships(self):
        text = parse_faqs(FIXTURE)[0].plain_answer
        self.assertIn('Credits — First: 60; Second: 90.', text)
        self.assertNotIn('|', text)
        self.assertNotIn('---', text)

    def test_lists_do_not_merge_into_one_word(self):
        self.assertIn('A document; A transcript', parse_faqs(FIXTURE)[1].plain_answer)

    def test_schema_has_every_current_question_and_exact_answer(self):
        faqs = parse_faqs(FIXTURE)
        schema = schema_for(faqs)
        self.assertEqual(schema['@type'], 'FAQPage')
        self.assertEqual([row['name'] for row in schema['mainEntity']], [faq.question for faq in faqs])
        self.assertEqual(schema['mainEntity'][0]['acceptedAnswer']['text'], faqs[0].plain_answer)

    def test_full_institution_names_do_not_fail_country_neutrality(self):
        self.assertEqual(country_hits('University of the West of Scotland; University of Wolverhampton; Girne American University'), [])
        self.assertIn('Scotland', country_hits('Study in Scotland'))
        self.assertIn('UAE', country_hits('Study in the UAE'))
        self.assertIn('Swiss', country_hits('A Swiss award'))

    def test_coverage_detects_a_missing_subject(self):
        provider = PROVIDERS[0]
        changed = provider.path.read_text().replace('Management Information Systems', 'Unlisted subject')
        rows = coverage_results(provider, changed)
        self.assertIn('BBA Management Information Systems', rows[0]['missing'])

    def test_doctoral_prefix_is_normalised_for_coverage(self):
        self.assertTrue(all(not row['missing'] for row in coverage_results(PROVIDERS[1])))


class ReviewArtefactTests(unittest.TestCase):
    def test_complete_local_audit(self):
        result = run_audit()
        self.assertEqual(result['errors'], [])
        self.assertGreater(result['totals']['open_verification_items'], 0)
        self.assertIn('not production', result['scope'])

    def test_published_schema_directory_is_not_used(self):
        for provider in PROVIDERS:
            actual = json.loads((ROOT / 'schema' / f'{provider.slug}.jsonld').read_text())
            self.assertEqual(actual, schema_for(read_faqs(provider.path)))
        self.assertFalse((ROOT.parents[1] / 'public/downloads/faqs/rushford-business-school.jsonld').exists())

    def test_pdf_counts_are_derived_from_source(self):
        self.assertEqual(TOTAL_FAQS, sum(len(read_faqs(provider.path)) for provider in PROVIDERS))
        self.assertEqual(TOTAL_PROGRAMMES, sum(map(len, programme_listing().values())))
        self.assertEqual(TOTAL_CATEGORIES, len(programme_listing()))
        self.assertEqual(SITE_TOTAL, sum(len(read_faqs(ROOT / 'drafts' / f'{slug}.md')) for slug in SITE_FILES))

    def test_every_provider_question_has_one_current_ranking_row(self):
        for provider, (_, _, rows) in zip(PROVIDERS, current_rank_data()):
            questions = [faq.question for faq in read_faqs(provider.path)]
            self.assertCountEqual([row['question'] for row in rows], questions)
            ranked = [row for row in rows if row['score'] is not None]
            scores = [row['score'] for row in ranked]
            self.assertEqual(scores, sorted(scores, reverse=True))
            for row in ranked:
                self.assertEqual(row['rank'], 1 + sum(score > row['score'] for score in scores))
            for row in rows:
                if row['score'] is None:
                    self.assertIsNone(row['rank'])
                    self.assertIsNone(row['tier'])
                    self.assertIn(row['question'], IMMIGRATION_QUESTIONS.values())

    def test_every_site_question_has_one_ranking_row(self):
        for slug, (_, rows) in zip(SITE_FILES, current_site_rank_data()):
            self.assertCountEqual([row['question'] for row in rows],
                                  [faq.question for faq in read_faqs(ROOT / 'drafts' / f'{slug}.md')])

    def test_original_weighted_score_error_is_fixed(self):
        qualifi = PROVIDERS[-1]
        components, score, _ = legacy_component_scores(qualifi)[16]
        self.assertEqual(score, 3.50)
        self.assertAlmostEqual(sum(v*w for v,w in zip(components, (.35,.25,.25,.15))), score)

    def test_unrecorded_component_scores_are_not_invented(self):
        original = sum(row['components'] is not None for _, _, rows in current_rank_data() for row in rows)
        practical = sum(row['components'] is None and row['score'] is not None
                        for _, _, rows in current_rank_data() for row in rows)
        self.assertEqual(original, 117)
        self.assertEqual(practical, 25)

    def test_pdf_question_formatting_has_no_literal_html(self):
        from pypdf import PdfReader
        with tempfile.TemporaryDirectory() as directory:
            md = Path(directory) / 'fixture.md'
            pdf = Path(directory) / 'fixture.pdf'
            md.write_text(FIXTURE)
            make_doc(pdf, 'Test review').build(parse_faq_md(md))
            text = '\n'.join(page.extract_text() or '' for page in PdfReader(pdf, strict=True).pages)
            self.assertIn('Q. What is the first question?', text)
            for token in ('<b>', '</b>', '<i>', 'VERIFY', 'PRIVATE', 'Target keyword'):
                self.assertNotIn(token, text)


class ScopedImmigrationTests(unittest.TestCase):
    def setUp(self):
        self.provider = PROVIDERS[0]
        self.text = self.provider.path.read_text(encoding='utf-8')

    def test_only_two_exact_provider_questions_are_exempt(self):
        for provider in PROVIDERS[:2]:
            text = provider.path.read_text(encoding='utf-8')
            self.assertEqual(country_policy_hits(provider.path, text), [])
            self.assertEqual(immigration_policy_errors(provider.path, read_faqs(provider.path)), [])
            self.assertIn('US', country_hits(public_markdown(text)))
            self.assertIn('UK', country_hits(public_markdown(text)))

    def test_exception_does_not_allow_geography_in_provider_intro(self):
        text = self.text.replace('> **About:**', '> **About:** Study in the UK.')
        self.assertIn('UK', country_policy_hits(self.provider.path, text))

    def test_exception_does_not_allow_geography_in_another_question(self):
        text = self.text.replace('**Q. When can I start my programme?**', '**Q. When can I start my UK programme?**')
        self.assertIn('UK', country_policy_hits(self.provider.path, text))

    def test_exception_does_not_allow_another_country_in_the_new_answer(self):
        text = self.text.replace(IMMIGRATION_DISCLAIMER, 'Options in Canada. ' + IMMIGRATION_DISCLAIMER)
        self.assertIn('Canada', country_policy_hits(self.provider.path, text))

    def test_exception_does_not_allow_another_provider(self):
        self.assertIn('UK', country_policy_hits(PROVIDERS[2].path, self.text))
        self.assertTrue(immigration_policy_errors(PROVIDERS[2].path, parse_faqs(self.text)))

    def test_exception_does_not_allow_the_same_name_in_drafts(self):
        path = ROOT / 'drafts' / self.provider.path.name
        self.assertIn('UK', country_policy_hits(path, self.text))

    def test_exact_question_and_section_are_required(self):
        text = self.text.replace('## ' + IMMIGRATION_SECTION, '## Other Information')
        self.assertIn('UK', country_policy_hits(self.provider.path, text))
        self.assertTrue(immigration_policy_errors(self.provider.path, parse_faqs(text)))
        text = self.text.replace(IMMIGRATION_QUESTIONS[self.provider.slug], 'Can this degree help with US or UK migration?')
        self.assertIn('UK', country_policy_hits(self.provider.path, text))
        self.assertTrue(immigration_policy_errors(self.provider.path, parse_faqs(text)))

    def test_approval_marker_and_disclaimer_are_required(self):
        text = self.text.replace(IMMIGRATION_APPROVAL, 'UNAPPROVED')
        self.assertIn('US', country_policy_hits(self.provider.path, text))
        self.assertTrue(immigration_policy_errors(self.provider.path, parse_faqs(text)))
        text = self.text.replace(IMMIGRATION_DISCLAIMER, '')
        self.assertTrue(immigration_policy_errors(self.provider.path, parse_faqs(text)))

    def test_ordinary_pronoun_us_is_not_a_country_claim(self):
        self.assertEqual(country_hits('Please contact us for information.'), [])
        self.assertIn('US', country_hits('Migrate to the US.'))

    def test_new_questions_are_not_given_invented_scores(self):
        rows = [row for _, _, items in current_rank_data() for row in items if row['score'] is None]
        self.assertEqual(len(rows), 2)
        self.assertCountEqual([row['question'] for row in rows], list(IMMIGRATION_QUESTIONS.values()))
        self.assertTrue(all(row['components'] is None and row['tier'] is None and row['rank'] is None for row in rows))

    def test_report_uses_canonical_answers_and_mapped_sources(self):
        evidence, sources, pairs = load_report_data()
        text = markdown_report(evidence, sources, pairs)
        self.assertEqual(len(pairs), 2)
        self.assertEqual(set(evidence['providers']), set(IMMIGRATION_QUESTIONS))
        self.assertNotIn('approval_id', evidence)
        self.assertEqual(len(sources), 10)
        for _, faq in pairs:
            self.assertEqual(text.count('**Q. ' + faq.question + '**'), 1)
            self.assertIn(faq.answer, text)
        for source in sources.values():
            self.assertIn(source['url'], text)
            self.assertEqual(source['checked_on'], '2026-09-09')
        for claim in evidence['claim_map']:
            self.assertTrue(set(claim['source_ids']).issubset(sources))

    def test_hpi_finding_is_period_limited_and_not_an_evergreen_faq_claim(self):
        evidence, _, pairs = load_report_data()
        hpi = next(row for row in evidence['sections'] if row['id'] == 'UK-HPI')
        self.assertIn('1 November 2025', hpi['finding'])
        self.assertIn('31 October 2026', hpi['finding'])
        for _, faq in pairs:
            self.assertNotIn('HPI', faq.answer)


class PresentationTests(unittest.TestCase):
    def test_approval_and_internal_delivery_banners_are_rejected(self):
        for text in (
            'Prepared for client review', 'Awaiting client approval',
            'Confidential. Not for publication until approved.', 'Client Review Documents',
            'Owner exception: IMM-US-UK-2026-09-09', 'Website publication: not authorised',
            'The review set has not been imported into the CMS.',
        ):
            with self.subTest(text=text):
                self.assertTrue(presentation_issues(text))

    def test_substantive_legal_approval_and_source_dates_are_retained(self):
        text = ('A Skilled Worker needs an approved employer. Visa approval rests with the relevant '
                'authority. Sources reviewed 9 September 2026. Exact programme requirements need confirmation.')
        self.assertEqual(presentation_issues(text), [])

    def test_all_report_text_and_the_download_index_are_clean(self):
        paths = [*sorted((ROOT / 'reports').glob('*.md')), *sorted((ROOT / 'reports').glob('*.json')),
                 ROOT.parents[1] / 'public/downloads/faqs/index.html']
        for path in paths:
            with self.subTest(file=path.name):
                self.assertEqual(presentation_issues(path.read_text()), [])

    def test_all_pdf_content_and_metadata_are_clean(self):
        from pypdf import PdfReader
        for name in PDF_NAMES:
            with self.subTest(file=name):
                pdf = PdfReader(ROOT / 'client' / name, strict=True)
                text = ' '.join(page.extract_text() or '' for page in pdf.pages)
                metadata = ' '.join(str(value) for value in (pdf.metadata or {}).values())
                self.assertEqual(presentation_issues(text + ' ' + metadata), [])

    def test_machine_qa_is_separate_from_presentation_reports(self):
        self.assertFalse((ROOT / 'reports/latest-verification.json').exists())
        self.assertTrue((ROOT / 'internal/latest-verification.json').is_file())

    def test_priority_inputs_do_not_depend_on_formatted_reports(self):
        original = Path.read_text
        def read_without_reports(path, *args, **kwargs):
            if path.parent.name == 'reports':
                raise AssertionError('Formatted reports must not feed their own score calculation')
            return original(path, *args, **kwargs)
        with patch.object(Path, 'read_text', new=read_without_reports):
            self.assertEqual(sum(len(legacy_component_scores(provider)) for provider in PROVIDERS), 117)


if __name__ == '__main__':
    unittest.main()

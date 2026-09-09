# Education FAQ Project — Review Pipeline

**Current review:** 9 September 2026 · **144 provider FAQs + 18 site-page FAQs = 162**.
The seven provider files cover **16 category sets and all 136 entries in the supplied
programme list**, plus one practical-information section per provider and two scoped
immigration clarifications (RBS and GAU only).

**Coverage is not verification of current availability.** Exact award titles, partner
routes and commercial/operational terms still have open confirmations. Start with
[`reports/verification-update-2026-09-07.md`](reports/verification-update-2026-09-07.md),
not the historical “all blockers resolved” assessment. For the newly approved topic, see
[`reports/gau-rbs-immigration-report-2026-09-09.md`](reports/gau-rbs-immigration-report-2026-09-09.md).

## Publication boundary

- `approved/` preserves the location of historically owner-approved provider drafts.
  The September corrections are a **client-review revision**, not fresh publication approval.
- `drafts/site-homepage.md` and `drafts/edutainment.md` remain **pending client approval**.
- No FAQ seeder, database record, settings value, Blade content or application route is
  modified by this pipeline.
- Only the six client-review PDFs may be shared through the existing
  `public/downloads/faqs/` preview, following the owner's PDF-only instruction.
- Markdown, research records, Python tools and JSON-LD stay outside the public web root.
  Schema files are **local review exports**, not deployed structured data.
- The owner approved two US/UK immigration clarification FAQs on 9 September 2026.
  This is a narrow exception, not permission to add country references elsewhere or
  publish website content. See `policies/us-uk-immigration-exception.md`.

## Workflow

```text
owner programme list → evidence review → draft → client review → explicit website approval
                              ↑ corrections and unresolved partner confirmations
```

The FAQ persona is defined in
[`.claude/agents/education-faq-specialist.md`](../../.claude/agents/education-faq-specialist.md).
The owner delegated routine maintenance and the current all-file verification pass;
that delegation is not permission to publish website content.

## Folder map

| Path | Purpose |
|---|---|
| `inputs/listing.md` | Original portfolio scope; do not silently replace supplied award titles |
| `approved/` | Historically approved provider files, now carrying September review corrections |
| `drafts/` | Proposed homepage and Edutainment sets |
| `reports/verification-update-2026-09-07.md` | Current findings, source limits and publication blockers |
| `reports/verification-evidence.json` | Evidence ledger used by the client verification PDF |
| `internal/latest-verification.json` | Reproducible local QA result and per-question inventory |
| `reports/*-faq-selection-report.md` | Clean English question registers and editorial priorities |
| `client/` | PDF builders, dependency versions and six client-review PDFs |
| `policies/us-uk-immigration-exception.md` | Exact two-question owner exception and mandatory safeguards |
| `reports/immigration-evidence-2026-09-09.json` | Ten dated official/contextual sources and claim mapping for the new topic |
| `schema/` | Unpublished provider FAQPage review exports |
| `tools/` | Shared parser, local audit and regression tests; no CI workflow installed |
| `TRACKER.md` | Current state and historical work log |

## Local verification and rebuild

Python 3.11 or later is recommended. Install `client/requirements.txt` in an isolated
Python environment, then run from the repository root:

```bash
python3 -m pip install -r content/faqs/client/requirements.txt
python3 content/faqs/tools/audit_faqs.py --write-schema
python3 -m unittest discover -s content/faqs/tools -p 'test_*.py' -v
python3 content/faqs/client/build_pdfs.py
python3 content/faqs/client/build_site_pdfs.py
python3 content/faqs/client/build_immigration_pdf.py
python3 content/faqs/client/build_reports.py
```

These commands do **not** copy anything to the web root. After an authorised PDF-only
refresh, copy only the six PDFs into `public/downloads/faqs/`, reconcile the existing
index date/page counts, then run:

```bash
python3 content/faqs/tools/audit_faqs.py --pdfs \
  --json content/faqs/internal/latest-verification.json
```

A passing audit checks coverage, structure, exact duplicates, metadata, selected
compliance patterns, country/region terms, schema parity and PDF cleanliness/copy parity.
It does **not** certify academic recognition, all current programme offers, production
CMS records, plagiarism percentage, search demand or legal acceptance.

## Content standards

- English (UK), short direct sentences and an answer-first opening. A 40–60-word summary
  can be useful; there is no rigid search-engine sentence-length requirement.
- One generic set per category. Where routes differ, say so explicitly rather than
  extending one programme's credits, assessment or policy across the category.
- No geographic targeting in visible FAQs, except US/UK inside the two exact authorised
  RBS/GAU immigration questions and answers. Full institutional proper names are retained
  where necessary; neither exception permits other destination claims.
- Current awarding-university/awarding-body specifications take precedence over old
  portal snapshots, directories and repository marketing defaults.
- Unresolved evidence uses internal `[VERIFY: ID — item]` comments with matching entries
  in the file's verification table. Visible answers must remain conditional or omit the
  unsupported claim. Hiding a comment in a PDF is not evidence verification.
- Institutional certification, programme accreditation, diploma regulation, academic
  level and acceptance for an intended use are distinct concepts.
- No guaranteed outcomes, automatic progression, universal acceptance, unverified free
  services, scholarships, fixed payment plans or fee-free extensions.
- Original wording is required, but **no numerical similarity certificate is claimed**.
- Priority scores are editorial estimates. Original component scores are recalculated
  where recorded; missing components are not invented. The two client-requested
  immigration additions are explicitly unscored, without an invented demand tier.
- Google ended FAQ rich results from 7 May 2026; no ranking or AI-inclusion benefit is
  promised for FAQPage JSON-LD. See the
  [1](https://developers.google.com/search/updates#deprecating-the-faq-rich-result-feature)
  official documentation update.

## Presentation delivery — 9 September 2026

The PDFs, report Markdown/evidence and download index use neutral informational wording,
without client-approval banners, confidential/review-only footers or internal delivery
status. All FAQ answers, legal qualifications and source references remain intact.
Original editorial component scores are preserved in `inputs/priority-components.json`,
independently of the formatted reports. Machine QA belongs in `internal/`, not `reports/`.

The owner authorised a commit and push of this FAQ/document work to the session branch.
That instruction does not import FAQs into the website CMS or change eligibility facts.

# Education FAQ Project — Current Summary

**Updated:** 9 September 2026
**Historical provider approvals:** 19 August 2026; Phase 2 additions completed 20 August 2026
**Current state:** two owner-approved US/UK immigration clarifications added; website publication on hold

## Coverage

| Provider | Category sets | Practical FAQs | Total FAQs | Supplied programme entries |
|---|---:|---:|---:|---:|
| Rushford Business School | 4 | 5 | 36 | 41 |
| Girne American University | 5 | 5 | 38 | 43 |
| University of the West of Scotland | 1 | 3 | 12 | 1 |
| University for the Creative Arts | 1 | 3 | 12 | 1 |
| University of Wolverhampton | 1 | 3 | 12 | 1 |
| Gatehouse Diplomas | 1 | 3 | 12 | 4 |
| Qualifi Diplomas | 3 | 3 | 22 | 45 |
| **Provider total** | **16** | **25** | **144** | **136** |

Homepage adds **8** proposed FAQs and Edutainment **10**, for **162 review questions**. The two immigration FAQs are additional provider-level
clarifications, not new programme categories.
All supplied entries are mapped; this is not confirmation that all award titles,
partner routes or intake availability are verified.

## Baseline audit — 7 September 2026

- Same branch safely pulled to `f37fa7e`; original local artefacts preserved.
- Existing questions reviewed without adding new programme topics.
- Category-wide assumptions corrected: durations/credits, recognition, diploma extras,
  entry and English rules, payment plans, support, assessment and progression.
- UWS questions made award-title-neutral pending BA versus BA (Hons) clarification.
- GAU thesis-master's wording no longer assumes the supplied Counselling Psychology MSc
  title is confirmed; the official catalogue lists MA.
- Old fixed UCA fees and undefined credit equivalence removed; exact current offer needed.
- UOW's standard and professional top-up LLM routes are no longer conflated.
- Gatehouse single-qualification evidence no longer applied to every track; delivery
  authorisation and remaining qualification numbers are open.
- Homepage and Edutainment remain drafts; operational safety and service claims are not
  promised without supporting evidence.

**41 grouped confirmation items remain open.** The previous “all four publish blockers
resolved” status is superseded, not silently carried forward.

See [`reports/verification-update-2026-09-07.md`](reports/verification-update-2026-09-07.md)
for exact findings, sources, CMS boundaries and hand-off. The evidence ledger feeding the
client verification report is `reports/verification-evidence.json`.

## Artefacts and checks

- Seven corrected provider markdown files; two corrected site-page drafts.
- All six client PDFs built/refreshed, including the dedicated immigration report; literal HTML tags no longer print around questions.
- Shared parser and seven local JSON-LD exports with exact answer parity.
- Local coverage/compliance/metadata/PDF checks and regression tests in `tools/`.
- Current exact-question registers appended to seven selection reports; report text is
  presented in clean English without approval or delivery-status context.
- Summary counts and tier totals now derive from the source content and recorded data.

The 117 original priorities have recorded component scores; 25 practical additions and
18 site questions have editorial totals without recorded components. The two new
immigration FAQs have no assigned demand tier or priority score. No components,
search-volume figures, rankings, AI visibility or plagiarism percentage are invented.
Local duplicate checks are not a tool-based originality certificate.

Google ended FAQ rich results from 7 May 2026. Local FAQPage files are review exports,
not a promise of a search feature or a deployment plan. Official update:
[1](https://developers.google.com/search/updates#deprecating-the-faq-rich-result-feature).

## Publication and preview

Only the six review PDFs are shared in the existing `public/downloads/faqs/` preview.
The markdown, source reports, tools and JSON-LD remain outside the web root. **No FAQ
seeder, application route, Blade content, database row or settings value was changed.**

The latest branch now uses CMS settings for Edutainment, Dual MBA and the MBA/Master's
landing page. Their source defaults were inspected separately; actual production CMS
content was not available. The preview on port 8080 is the PDF review index, not Laravel.

## Next work

1. Review the dedicated GAU/RBS US/UK immigration report and dated source register.
2. Preserve the scoped exception in future revisions; do not imply degree-specific acceptance.
3. Resolve exact award/route and operational blockers before any website approval.
4. Obtain explicit publication instructions before importing content or deploying schema.

## Approved extension — 9 September 2026

- Exactly one US/UK immigration clarification added to RBS and one to GAU under
  `IMM-US-UK-2026-09-09`; the old 160 questions are unchanged.
- New section: **Immigration & Visa Eligibility**. The shared principle applies across
  their nine supplied categories (84 entries), without certifying immigration acceptance.
- Separate topic sections added to both provider reports, the strategy PDF and the
  cumulative verification report.
- Dedicated client report: `client/Maverick-GAU-RBS-Immigration-FAQ-Report.pdf`, backed by
  `reports/immigration-evidence-2026-09-09.json` (ten dated sources).
- Rules and local tests limit the exception to the exact two FAQs and require the disclaimer.
- The prior 41 grouped confirmations remain open. Source-backed general guidance is
  not an individual immigration assessment. No website/CMS import or GitHub push.

## Presentation update — 9 September 2026

All six PDFs and the presentation reports are directly shareable without approval/review
labels. Legal and programme-specific caveats are retained. The technical QA result is
kept separately at `internal/latest-verification.json`. Commit/push was explicitly
authorised for this document work; website FAQ publication remains a separate action.

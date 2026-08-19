# FAQ Project — University Tracker

> Single source of truth for the queue (order = programme list PDF, `inputs/listing.md`).
> The agent may only work on the **topmost non-approved** university. Statuses:
> `⬜ Pending` → `🟡 Drafted` → `🔵 In Review` → `🟠 Changes Requested` → `✅ Approved`.
>
> **Owner mandates (2026-08-19):** (1) FAQs are generic per programme CATEGORY — one set
> applies to all programmes in that category. (2) Content is country-neutral — no country
> names anywhere in FAQ content (global audience).

| # | University / Provider | Categories (FAQ sets) | Programmes covered | Status | Draft File | Approved On |
|---|----------------------|----------------------|--------------------|--------|-----------|-------------|
| 1 | Rushford Business School (RBS) | 4 — BBA · MBA · MSc · Doctoral (PhD/DBA/EPD) | 41 | ✅ Approved | `approved/rushford-business-school.md` + `reports/rushford-faq-selection-report.md` | 2026-08-19 |
| 2 | Girne American University (GAU) | 5 — BSc · MBA · EMBA · MSc (Thesis) · PhD | 43 | ✅ Approved | `approved/girne-american-university.md` + `reports/girne-american-university-faq-selection-report.md` | 2026-08-19 |
| 3 | University of the West of Scotland (UWS) | 1 — Undergraduate (BA Hons Global Business) | 1 | ✅ Approved | `approved/university-west-scotland.md` + `reports/university-west-scotland-faq-selection-report.md` | 2026-08-19 |
| 4 | University for the Creative Arts (UCA) | 1 — Global MBA (dual with RBS) | 1 | ✅ Approved | `approved/university-creative-arts.md` + `reports/university-creative-arts-faq-selection-report.md` | 2026-08-19 |
| 5 | University of Wolverhampton (UOW) | 1 — Master of Laws (LLM) | 1 | ✅ Approved | `approved/university-wolverhampton.md` + `reports/university-wolverhampton-faq-selection-report.md` | 2026-08-19 |
| 6 | Gatehouse Diplomas (separate provider — confirmed) | 1 — Level 7 Diplomas | 4 | ✅ Approved | `approved/gatehouse-diplomas.md` + `reports/gatehouse-diplomas-faq-selection-report.md` | 2026-08-19 |
| 7 | Qualifi Diplomas (separate provider — confirmed) | 3 — Level 3 · Level 5 Extended · Level 7 | 45 | ✅ Approved | `approved/qualifi-diplomas.md` + `reports/qualifi-diplomas-faq-selection-report.md` | 2026-08-19 |

**Totals: 7 providers · 16 category FAQ sets · 136 programmes covered · 117 FAQs — ALL APPROVED ✅ (project complete 2026-08-19)**

## Resolved decisions (owner)

1. ✅ Gatehouse & Qualifi = **separate providers**, own files.
2. ✅ Country-neutral rule is **STRICT** — even the university's own country/city is never
   mentioned in content.
3. ✅ Queue order = PDF order (RBS first).

## Log

- **2026-08-19** — Pipeline created. GAU demo draft (v1, single programme) generated from
  repo data and reviewed by owner: structure OK'd.
- **2026-08-19** — Programme list received (PDF content pasted by owner → `inputs/listing.md`).
  Queue built: 7 providers, 16 category FAQ sets, 136 programmes. New owner mandates logged:
  category-generic FAQs + country-neutral content. Awaiting owner confirmation of category
  breakdown before drafting begins.
- **2026-08-19** — Owner confirmed: separate providers for Gatehouse/Qualifi; STRICT
  country-neutral; PDF queue order. Cycle #1 (RBS) drafted — 4 category sets, 30 FAQs,
  9 items in Facts-to-Verify. Submitted for owner review.
- **2026-08-19** — ✅ **RBS APPROVED** by owner. File moved to `approved/`. New standard
  artifact added on owner request: per-university **FAQ Selection & Global Ranking Report**
  (`reports/rushford-faq-selection-report.md` delivered). Next: GAU (Cycle #2) — awaiting
  owner go-ahead.
- **2026-08-19** — Cycle #2 (GAU) drafted: v1 single-programme demo rewritten to v2
  category-generic (5 sets, 32 FAQs, 10 Facts-to-Verify) + selection/ranking report.
  Duplicate-prevention applied vs RBS (no "PhD vs DBA", no standalone GMAT question).
  Submitted for owner review.
- **2026-08-19** — ✅ **GAU APPROVED** by owner; moved to `approved/`. Cycle #3 (UWS)
  drafted — single category (BA Hons Global Business), 9 FAQs, 6 Facts-to-Verify, report
  delivered. Key research finding: partner sources describe the programme as a ~1-year
  online TOP-UP route (flagged Verify #1). Submitted for owner review.
- **2026-08-19** — ✅ **UWS APPROVED** by owner; moved to `approved/`. Cycle #4 (UCA)
  drafted — Global MBA dual award, 9 FAQs, 6 Facts-to-Verify, report delivered. Official
  portal facts used (12–18 mo, 90 ECTS, CHF fee structure); "WES Recognized" brochure claim
  EXCLUDED per compliance blacklist; entry-criteria conflict between delivery routes
  flagged (Verify #3). Submitted for owner review.
- **2026-08-19** — ✅ **UCA APPROVED** by owner; moved to `approved/`. Cycle #5 (UOW)
  drafted — Master of Laws (LLM), 9 FAQs, 6 Facts-to-Verify, report delivered. Key SME
  finding: partner sources describe a 6-month online LLM TOP-UP route via Level 7 law
  diploma — potential Qualifi L7 Law → LLM internal funnel (Verify #2). "WES Approved"
  claim EXCLUDED; practice-rights disclaimer added. Submitted for owner review.
- **2026-08-19** — ✅ **UOW APPROVED** by owner; moved to `approved/`. Cycle #6 (Gatehouse)
  drafted — Level 7 Diplomas (4 tracks), 9 FAQs, 7 Facts-to-Verify, report delivered.
  Compliance: "equivalent to master's" over-claim avoided (framework-level wording only);
  regulation status flagged as publish-blocker (Verify #1). Cannibalisation risk vs
  Qualifi L7 noted for Cycle #7 handling. Submitted for owner review.
- **2026-08-19** — ✅ **GATEHOUSE APPROVED** by owner; moved to `approved/`. Cycle #7
  (Qualifi — FINAL) drafted — 3 category sets (L3: 6 · L5: 6 · L7: 7 = 19 FAQs), 8
  Facts-to-Verify, report delivered. Cannibalisation control executed: Qualifi file owns
  the "ladder" angle (which-level guidance, Extended meaning, top-up progressions) — zero
  duplicate query targets vs Gatehouse. Submitted for owner review.
- **2026-08-19** — ✅ **QUALIFI APPROVED** by owner; moved to `approved/`.
  🏁 **PROJECT COMPLETE:** all 7 providers approved — 16 category FAQ sets, 117 FAQs,
  136 programmes covered. Consolidated verification backlog and next steps in
  `PROJECT-SUMMARY.md`.

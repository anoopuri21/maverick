# 03 — Content Pipeline (Stages, Gates, Handoffs)
**Maverick AI Content Team · Date:** 2026-09-14 · **Owner:** T1

One pipeline for every content task: landing pages, guide articles, programme pages,
email/WhatsApp sequences, client packs. Stages may shrink for small tasks; gates never do.

---

## 1. Stage map

| Stage | Name | Lead | Support | Output | Gate |
|---|---|---|---|---|---|
| **S0** | Brief intake | T1 | T6 | Filled brief (`templates/content-brief-template.md`) | Gate 0 |
| **S1** | Research | T2, T3, T4 | T6 | Market pack · competitor matrix · keyword map | Gate 1 + 1b |
| **S2** | Verification | T10 | T6 | Fact-check table + consistency matrix + issues log | Gate 2 |
| **—** | Client Approval #1 | T1 | — | Plain-language plan (client-facing) | **HARD STOP** |
| **S3** | Strategy | T7 | T4, T5 | Messaging framework · structure blueprint · AEO/GEO answer plan | Gate 3 |
| **S4** | Draft | T8 | T6 consult | Full draft: keyword homes + claim IDs + [PLACEHOLDER] tags | Gate 4 |
| **S5** | Humanize | T9 | — | Naturalized draft + humanization scorecard + change notes | Gate 5 |
| **S6** | Optimize check | T4, T5 | — | SEO/AEO/GEO compliance notes on the draft | Gate 6 |
| **S7** | QA + production | T10 | — | QA report (P0/P1/P2) + delivery package | Gate 7 |
| **—** | Client Approval #2 | T1 | — | Signed content | **HARD STOP** |
| **S8** | Package & handoff | T1 | — | Master deliverable + build notes (schema, links, events) | — |

## 2. Gate checklists

### Gate 0 — Brief
- [ ] Purpose, conversions (primary/secondary), personas, products all filled.
- [ ] Client-owned blanks listed by name (fees, durations, testimonials, photos, logos).
- [ ] URL/slug proposed; deadline + sign-off chain set.

### Gate 1 — Research
- [ ] Every external number has a named source + date.
- [ ] Personas map 1:1 to keyword + programme route + page location.
- [ ] Competitor prices observed in live SERP/listings, not remembered.

### Gate 1b — Keywords
- [ ] Every keyword (both tiers) has exactly one assigned home before any copy starts.

### Gate 2 — Verification
- [ ] Fact table 100% PASS or fixed; inconsistencies logged with severity.
- [ ] Regulation framing matches the R-pack in `02-niche-knowledge-base.md` §3.

### Gate 3 — Strategy
- [ ] Message hierarchy agreed; two-audience tone check defined per section.
- [ ] Answer plan exists: which questions get 40–60 word answer blocks, which get FAQ homes, which schema types.

### Gate 4 — Draft
- [ ] All sections present per blueprint; keyword homes match the map.
- [ ] Every claim carries a whitelist ID (a/b/c per `G06`); placeholders tagged `[LIKE THIS]`.
- [ ] One duration per programme family; durations only from the client-confirmed table (or delivery-model wording if unconfirmed).

### Gate 5 — Humanization
- [ ] Banned-pattern grep = 0 hits (`G02` §4 list).
- [ ] Naturalness scorecard ≥ 9/10 (`G02` §5).
- [ ] Read-aloud test + counsellor-desk test passed; change notes attached.
- [ ] Facts untouched by the humanizer — any needed fact change goes back to T8/T6.

### Gate 6 — Optimization
- [ ] Meta within limits (title ≤ 60, description ≤ 155 chars).
- [ ] H1 benefit-led; primary keyword present without forcing.
- [ ] Answer blocks present for every mapped question; FAQ 1:1 with planned schema.
- [ ] Extractability pass: core answers inside the first 200 words of each section.

### Gate 7 — QA & production
- [ ] Placeholder sweep = 0 (`[FEE] [MONTH] [Student Name] [Add] [TBD]`).
- [ ] Mechanical sweep = 0 (Masters-draft trap list: "programmed", "program memes", verbatim repeats).
- [ ] Claims map 100%; regulation phrasing exact; do-not-promise list respected.
- [ ] Schema spec valid against `G04` §6; links resolve; internal-link rails present.
- [ ] Humanization scorecard + claims map + keyword map attached to the package.

## 3. Handoff contracts (artifact + acceptance rule)

| From → To | Artifact | Accepts when |
|---|---|---|
| T1 → team | Brief | All fields populated or blank listed as client-owned |
| T2/T3/T4 → T10 | Research packs | Sources + dates on every number; placements on every keyword |
| T10 → T1 | Verification report | Fact table closed; issues log closed or escalated |
| T7 → T8 | Messaging + structure doc | Hierarchy, per-section tone notes, CTA language locked |
| T5 → T8 | Answer plan | Question list with homes (answer block / FAQ / guide) + schema types |
| T8 → T9 | Draft | Gate 4 passed; claim IDs visible |
| T9 → T4/T5 | Humanized draft | Scorecard attached; zero fact changes |
| T4/T5 → T10 | Optimization notes | Per-issue fix list, already applied or flagged |
| T10 → T1 | QA report + package | Verdict line: APPROVED / REVISE; P0 count stated |

## 4. Issue severity (used in every report)

| Grade | Meaning | Action |
|---|---|---|
| **P0** | Blocks ship: unverified claim, placeholder in publish text, wrong regulation framing, copyrighted text, mechanical trap | Stop. Fix before anything else |
| **P1** | Quality risk: weak answer block, tone miss, missing source date | Fix this cycle |
| **P2** | Improvement: better hook, tighter meta, extra FAQ | Log; fix if time allows |

## 5. Client stop-points

Two hard stops per page task (Approval #1 after verification, #2 after QA) — same pattern as
`../bachelors/00-work-plan.md`. Client-facing documents are plain language, list style, no
internal jargon, no agent labels, no tool mentions. The approval *mechanics* (sign-off lines,
dates) live in the orchestrator's tracker, not inside the client document.

## 6. File naming

Deliverables in the page folder, numbered by stage:

```
{NN}-{stage}-{topic}-{market}.md     e.g. 06-bachelors-landing-content-gcc-uae.md
```

- NN = sequence inside the page folder (continue the existing numbering).
- Reports end in `-report.md`; client packs end in `-client.md` or ship as PDF.
- Every file opens with: project line, date line, owner role line, companion-files line.

## 7. Definition of done (per artifact type)

- **Research:** every number sourced + dated; personas mapped; implications explicit.
- **Strategy:** hierarchy + tone + answer plan locked; nothing left to the writer's guesswork.
- **Content:** all blocks, keyword homes correct, claims mapped, two-audience pass signed.
- **Package:** zero placeholders, schema valid, meta within limits, humanization scorecard ≥ 9/10,
  QA verdict APPROVED, next-step notes for the build team.

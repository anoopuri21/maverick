# 03 · Content Pipeline (Stages, Gates, Handoffs)
**Maverick AI Content Team · Date:** 2026-09-14 (generalized 2026-09-15) · **Owner:** T1

One pipeline for every content task, on any page and any asset type: landing pages, guide
articles, programme pages, email/WhatsApp sequences, client packs. The pipeline is
page-agnostic. The brief names the page and the market; the same stages, gates and roles run
whether the task is the Bachelors page, the Masters page, a single programme page, or a guide.
Stages may shrink for small tasks; gates never do.

---

## 1. Stage map

| Stage | Name | Lead | Support | Output | Gate |
|---|---|---|---|---|---|
| **S0** | Brief intake | T1 | T6 | Filled brief (`templates/content-brief-template.md`) | Gate 0 |
| **S1** | Research | T2, T3, T4 | T6 | Market pack · competitor matrix · keyword map | Gate 1 + 1b |
| **S2** | Verification | T10 | T6 | Fact-check table + consistency matrix + issues log | Gate 2 |
| **A1** | Client Approval #1 | T1 | · | Plain-language plan (client-facing) | **HARD STOP** |
| **S3** | Strategy | T7 | T4, T5 | Messaging framework · structure blueprint · AEO/GEO answer plan | Gate 3 |
| **S4** | Draft | T8 | T6 consult | Full draft: keyword homes + claim IDs + [PLACEHOLDER] tags | Gate 4 |
| **S5** | Humanize | T9 | · | Naturalized draft + humanization scorecard + change notes | Gate 5 |
| **S6** | Optimize check | T4, T5 | · | SEO/AEO/GEO compliance notes on the draft | Gate 6 |
| **S7** | QA + production | T10 | · | QA report (P0/P1/P2) + delivery package | Gate 7 |
| **A2** | Client Approval #2 | T1 | · | Signed content | **HARD STOP** |
| **S8** | Package & handoff | T1 | · | Master deliverable + build notes (schema, links, events) | · |

## 2. Gate checklists

### Gate 0 · Brief
- [ ] Purpose, conversions (primary/secondary), personas, products all filled.
- [ ] Client-owned blanks listed by name (fees, durations, testimonials, photos, logos).
- [ ] URL/slug proposed; deadline + sign-off chain set.

### Gate 1 · Research
- [ ] Every external number has a named source + date.
- [ ] Personas map 1:1 to keyword + programme route + page location.
- [ ] Competitor prices observed in live SERP/listings, not remembered.
- [ ] Research is run fresh for this page and this market, not recycled from another page's
      pack. Each page gets its own market and competitor notes.

### Gate 1b · Keywords
- [ ] Keyword source recorded: client-supplied, or researched by T4 for this task.
- [ ] If the client supplied no keywords, T4 has built the location-based map (see §8) and it
      is attached, with dated demand evidence per phrase.
- [ ] Every keyword (both tiers) has exactly one assigned home before any copy starts.

### Gate 2 · Verification
- [ ] Fact table 100% PASS or fixed; inconsistencies logged with severity.
- [ ] Regulation framing matches the R-pack in `02-niche-knowledge-base.md` §3.

### Gate 3 · Strategy
- [ ] Message hierarchy agreed; two-audience tone check defined per section.
- [ ] Answer plan exists: which questions get 40–60 word answer blocks, which get FAQ homes, which schema types.

### Gate 4 · Draft
- [ ] All sections present per blueprint; keyword homes match the map.
- [ ] Every claim carries a whitelist ID (a/b/c per `G06`); placeholders tagged `[LIKE THIS]`.
- [ ] One duration per programme family; durations only from the client-confirmed table (or delivery-model wording if unconfirmed).

### Gate 5 · Humanization
- [ ] Banned-pattern grep = 0 hits (`G02` §4 list).
- [ ] Em dash count = 0 and en dash count = 0 (`G02` §2d).
- [ ] Naturalness scorecard ≥ 10/11 (`G02` §5).
- [ ] Read-aloud test + counsellor-desk test passed; change notes attached.
- [ ] Facts untouched by the humanizer. Any needed fact change goes back to T8/T6.

### Gate 6 · Optimization
- [ ] Meta within limits (title ≤ 60, description ≤ 155 chars).
- [ ] H1 benefit-led; primary keyword present without forcing.
- [ ] Answer blocks present for every mapped question; FAQ 1:1 with planned schema.
- [ ] Extractability pass: core answers inside the first 200 words of each section.

### Gate 7 · QA & production
- [ ] Placeholder sweep = 0 (`[FEE] [MONTH] [Student Name] [Add] [TBD]`), in the markdown and
      in any rendered PDF or DOCX.
- [ ] Instruction-residue sweep = 0: no "note to writer", no "add here", no process notes in
      anything the client reads (`G02` §4 command).
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

Two hard stops per page task (Approval #1 after verification, #2 after QA), the same pattern
as `../bachelors/00-work-plan.md`. Client-facing documents are plain language, list style, no
internal jargon, no agent labels, no tool mentions, no placeholders, no instructions. The
approval *mechanics* (sign-off lines, dates) live in the orchestrator's tracker, never inside
the client document. Full rule: `guidelines/G07-production-readiness-checklist.md` §7.

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
- **Package:** zero placeholders, zero instructions, zero em dashes, schema valid, meta within
  limits, humanization scorecard ≥ 10/11, QA verdict APPROVED, next-step notes for the build team.

## 8. Keyword research when the client supplies none (location-based)

Many tasks arrive with no keyword list. The team never guesses and never reuses another
page's map. T4 builds a fresh, location-based map before any copy starts.

Steps (T4 leads, T2 supplies intent, T3 supplies the SERP picture):

1. **Fix the location and the market.** The brief states the geo (for example: Dubai,
   Sharjah, Abu Dhabi, wider GCC) and the audience. Every phrase must serve that location.
2. **Observe the live SERP.** For the page's topic and city, record who ranks today, what
   format wins (listing page, guide, agent page, campus page), and what the titles say. Date
   every observation.
3. **Mine the demand surfaces.** Programme-listing platforms (educations.com,
   coursetakers.ae, bachelorsportal), directory counts for the city, and competitor pages
   already ranking. These give real volume signals without paid tools.
4. **Build the two tiers.** Tier 1 (headline keywords) shape H1, meta and sections. Tier 2
   (reach phrases) are woven in. Cover the whole journey: discover, verify, narrow, localise,
   de-risk (price), compare.
5. **Assign one home per phrase** before drafting. A phrase may headline one guide article;
   that is its home. Two homes is stuffing.
6. **Record demand evidence per phrase.** Intent, dated SERP note, who ranks, win angle.
   Volumes are directional until re-run in Keyword Planner; the doc says so.

Reference examples of a finished map: `../bachelors/02-keyword-research-bachelors-gcc-uae.md`
(undergraduate) and `../gulf-masters/01-keyword-research-gcc-uae.md` (postgraduate). Use them
as the format model, not as a source of phrases.

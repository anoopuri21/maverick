# Team Charter — Virtual Content Production Pipeline
**Maverick Business Academy London** · v1.0 · fixed 2026-10-05

This charter defines six AI agent roles that operate as a content production agency for programme pages. **It is fixed for all phases.** Every programme (Doctorate, Diploma, or any future batch) moves through the same six stations, in the same order, producing the same artefacts.

Authoritative references this charter is bound to:
- `docs/program-content-template.md` — the field spec (field names, word counts, formats)
- `docs/programs-content-structure-understanding.md` — the data model and site behaviour
- `docs/listing.pdf` — the programme catalogue (source of truth for *which* programmes exist)

---

## The pipeline

```
docs/listing.pdf
      │
      ▼
 ① RESEARCH ANALYST ───────────► 01-research/{slug}.research.md      (facts + source URLs)
      │                                     │
      ▼                                     │ QA gate 1: every fact has a URL
 ② CONTENT STRATEGIST ────────► 02-mapped/{slug}.mapped.md          (template fields + GAPS)
      │                                     │
      ▼                                     │ QA gate 2: every template field addressed
 ③ SEO CONTENT WRITER ────────► 03-draft/{slug}.draft.md            (full copy)
      │                                     │
      ▼                                     │ QA gate 3: word counts in range
 ④ HUMANIZER / EDITOR ────────► 04-edited/{slug}.edited.md          (de-AI'd copy)
      │                                     │
      ▼                                     │ QA gate 4: house style pass
 ⑤ QA / FACT-CHECKER ─────────► 05-qa/{slug}.qa.md                  (VERDICT: PASS/FAIL)
      │                                     │
      │◄──── FAIL: returns to the named agent with specific line references
      ▼ PASS
 ⑥ FILE ORGANIZER ────────────► 06-approved/{category}/{slug}.md    (+ seeder-ready payload)
```

**Rule:** no agent may skip the station before it. No agent may invent what the previous station failed to supply — it raises a **GAP** or a **FAIL** instead.

---

## ① Research Analyst Agent

> **Responsibility:** Extract accurate, verifiable programme facts — duration, modules, credits, eligibility, fees, accreditation, intakes, career paths — from official university / awarding-body sources only.
> **Input:** A programme title + awarding body from `docs/listing.pdf`.
> **Output:** `01-research/{slug}.research.md` — a facts-only dossier where **every single data point carries its source URL and access date**.
> **Quality standard:** Zero creative writing, zero inference, zero rounded-off guesses. Unfound data is written as `NOT FOUND — searched: {urls}`, never estimated. Official domain only (university site, Qualifi, Gatehouse, Ofqual register); aggregator and agent sites are rejected.

**Source hierarchy (strict):** 1. Awarding university's own programme page → 2. Official awarding body (Qualifi / Gatehouse / Ofqual register) → 3. University PDF prospectus/handbook → 4. Accreditation body register (IACBE, YÖK, YÖDAK). Anything else = not a source.

**Never researches:** Maverick's own fees, scholarships, instalments or support offering — those are internal policy, supplied by the Strategist from existing live programmes.

---

## ② Content Strategist Agent

> **Responsibility:** Map the research dossier onto the exact field structure of `docs/program-content-template.md`, decide what goes in `snapshot` vs `highlights`, pick Lucide icons, set category/level/slug/sort_order, and flag every gap.
> **Input:** `01-research/{slug}.research.md` + `docs/program-content-template.md`.
> **Output:** `02-mapped/{slug}.mapped.md` — every template field listed with either source-backed raw material or an explicit `⚠️ GAP` line stating what is missing and who must resolve it.
> **Quality standard:** 100% field coverage — no template field may be silently absent. Writes no prose; supplies raw material and structural decisions only. Facts are never upgraded, softened, or filled in.

**Owns these decisions:** category assignment, `level` badge value, slug pattern, `university_partner_id` target (and whether the partner record exists yet), which 6 highlights, which 6–7 snapshot rows, stage/module grouping, the 6 standard support points, and the 5 fee labels.

**Gap taxonomy:** `GAP-SOURCE` (not published anywhere) · `GAP-POLICY` (needs a Maverick business decision, e.g. fee labels) · `GAP-SCHEMA` (needs a DB/category/partner record created first, e.g. Qualifi as a UniversityPartner).

---

## ③ SEO Content Writer Agent

> **Responsibility:** Turn the mapped material into finished, on-brand, keyword-optimised copy for every prose field — short description, overview, benefits, outcomes, GCC reasons, FAQs, and SEO meta.
> **Input:** `02-mapped/{slug}.mapped.md` + house style rules from `docs/program-content-template.md`.
> **Output:** `03-draft/{slug}.draft.md` — complete copy, field-by-field, with a word/char count printed next to each field.
> **Quality standard:** Every field inside its measured range; British English; one primary keyword per page used naturally in title, meta, first overview paragraph and one H2 — no stuffing. A field with an unresolved `⚠️ GAP` is left blank with the gap restated, never papered over.

**Hard limits carried from the template:** `short_description` ≤ 300 chars and plain text · `meta_title` 45–78 chars · `meta_description` 120–161 chars · `og_title` ≤ 60 · `twitter_title` ≤ 70 · HTML only in `description`, `benefits.desc`, `module.overview`, `reviews.review`, `faqs.answer`.

**Forbidden:** numeric fees on the page, unattributed market statistics, retyping university description copy (it is inherited from `UniversityPartner`), and placeholder/lorem text of any kind.

---

## ④ Humanizer / Editor Agent

> **Responsibility:** Strip AI tells — tricolons, "In today's fast-paced world", "delve/leverage/robust/seamless", uniform sentence lengths, every paragraph opening the same way, em-dash overuse, hollow superlatives — and restore a real human cadence.
> **Input:** `03-draft/{slug}.draft.md`.
> **Output:** `04-edited/{slug}.edited.md` — rewritten copy plus a short changelog of what was removed and why.
> **Quality standard:** Sentence length must vary (short punches next to longer sentences); no two consecutive sentences may share an opening structure; at least one honest caveat per page; and **not a single fact may change during editing** — rhythm and wording only.

**The voice it protects** (observed in the 53 live programmes): second person, plain, unhyped. Short declaratives with the occasional deliberate fragment. Attributed stats. Honest limits stated out loud — *"The bachelor's is a foundation, not a licence."* Dry, confident, never breathless.

**Banned register:** "unlock your potential", "embark on a journey", "in an ever-evolving landscape", "game-changing", "cutting-edge", "world-class" (unless it is a quoted accreditation claim), and any sentence that would read identically on a competitor's site.

---

## ⑤ QA / Fact-Checker Agent

> **Responsibility:** Re-open every source URL from the research dossier and verify the edited copy against it, then verify the copy against the template structure, word counts and hard limits.
> **Input:** `04-edited/{slug}.edited.md` + `01-research/{slug}.research.md` + `docs/program-content-template.md`.
> **Output:** `05-qa/{slug}.qa.md` — a line-by-line verification table ending in a single **VERDICT: PASS** or **VERDICT: FAIL**, with every failure naming the responsible agent and the exact field.
> **Quality standard:** Any fabricated, drifted, or unsourceable claim is an automatic FAIL — no partial passes. QA never edits the copy; it only reports.

**Checklist it runs (all must pass):**
1. Every factual claim traces to a live source URL in the dossier — no drift from the original wording's meaning.
2. No fabricated module names, durations, credits, accreditations, rankings, or statistics.
3. Every required (●) template field is present and populated.
4. All word/char counts inside the documented ranges; all hard `maxLength` limits respected.
5. No numeric fees; all market stats attributed inline.
6. No HTML in plain-text fields; `<p>`-wrapped HTML where HTML is expected.
7. Slug unique against the existing 53 programmes; category and university partner exist.
8. No unresolved `⚠️ GAP` silently filled with invented content.

**On FAIL:** the file returns to the named agent only. Max two remediation loops; a third failure escalates to a human decision.

---

## ⑥ File Organizer Agent

> **Responsibility:** Take only PASS-verdict content and commit it to the correct folder, filename and format, then produce the import-ready payload for the Laravel seeder.
> **Input:** `04-edited/{slug}.edited.md` + a PASS verdict in `05-qa/{slug}.qa.md`.
> **Output:** `06-approved/{category}/{slug}.md` plus an entry appended to `06-approved/{category}/_payload.php` (same heredoc-JSON shape as `database/seeders/data/master_programs.php`), and a line in `06-approved/INDEX.md`.
> **Quality standard:** Refuses anything without a PASS verdict. Filenames are lowercase-hyphen and must match the programme `slug` exactly — the slug is the join key across all six stations and the live URL.

**Never:** renames a slug on its own, overwrites an approved file without a new PASS, edits copy, or touches `database/` directly. Seeder wiring is a developer action, not an agent action.

---

## Shared conventions

### Working directory
```
content/programs/
├── 01-research/{slug}.research.md
├── 02-mapped/{slug}.mapped.md
├── 03-draft/{slug}.draft.md
├── 04-edited/{slug}.edited.md
├── 05-qa/{slug}.qa.md
└── 06-approved/
    ├── INDEX.md
    ├── doctorate/{slug}.md        + _payload.php
    └── diploma/{slug}.md          + _payload.php
```

### Naming
- `{slug}` = lowercase, hyphenated, matches the DB `slug` and the live `/programs/{slug}` URL.
- Disambiguate shared titles with the university: `mba-in-marketing-rushford-business-school`.
- `{category}` folder = the `ProgramCategory` slug (`doctorate`, `diploma`, …).
- One programme = one file at every station. No batched multi-programme files.

### Front-matter on every station file
```yaml
---
slug: qualifi-level-7-diploma-in-business-strategy
title: Qualifi Level 7 Diploma in Business Strategy
awarding_body: Qualifi
category: diploma
station: 03-draft
agent: SEO Content Writer
status: in-progress | complete | blocked
gaps: [GAP-POLICY: fee labels]
updated: 2026-10-05
---
```

### Rules that bind all six agents
1. **No fabrication, ever.** Unknown is written as unknown and escalated as a GAP.
2. **Single direction of travel.** Work moves forward on PASS, backward only on an explicit FAIL naming the field.
3. **Stay in your lane.** Research doesn't write; Strategy doesn't phrase; Writing doesn't re-source; Editing doesn't change facts; QA doesn't edit; Organizer doesn't author.
4. **The template is law.** `docs/program-content-template.md` wins any dispute about fields, lengths or formats.
5. **Empty beats fake.** A blank block auto-hides its section on the live site — that is the correct outcome for missing data, not placeholder prose.
6. **University copy is inherited,** never retyped onto a programme.
7. **Traceability.** Every approved page can be walked back from live URL → payload → approved file → QA verdict → source URL.

---

## Status ledger (`06-approved/INDEX.md`)

| Slug | Title | Category | Awarding body | Station | Verdict | Blocking gaps |
|---|---|---|---|---|---|---|
| _(populated as programmes move through the pipeline)_ | | | | | | |

---

## Known pipeline blockers (from Phase 1, must be cleared before Station ⑥)

| ID | Blocker | Owner |
|---|---|---|
| GAP-SCHEMA-01 | No `doctorate` ProgramCategory exists | Human / developer |
| GAP-SCHEMA-02 | No `diploma` ProgramCategory exists | Human / developer |
| GAP-SCHEMA-03 | `Gatehouse Qualifications` and `Qualifi` are not `UniversityPartner` records, and `university_partner_id` is effectively required | Human / developer |
| GAP-POLICY-01 | Doctorate granularity — 13 topic pages vs 39 (topic × PhD/DBA/EPD) vs 3 parent pages | Human |
| GAP-POLICY-02 | Fee-label set for Doctorate and Diploma pages (amounts stay off-page) | Human |

---

*Charter fixed at v1.0. Any change to role boundaries, artefact names, or quality gates requires a version bump and a note here.*

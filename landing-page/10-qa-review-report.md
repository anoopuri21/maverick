# 10 — QA & Review Report
**Agent 11 — Quality Assurance & Review Agent**
**Project:** Maverick Business Academy London — Conversion Landing Page package
**Date:** 2026-09-09
**Scope:** all 8 upstream deliverables (01–09) — consistency, brand/tone alignment, SEO verification, claims compliance, structural gaps.

---

## 1. Cross-Deliverable Consistency Check

| Check | Result | Evidence / Fix |
|---|---|---|
| Message order (recognition → flexibility → speed → price) used consistently | ✅ PASS | 01 §5 sets it; 04 pillars follow it; 05 blueprint section order §3→§4→§6→§12 matches; 06 copy lands proof points in the same order |
| Personas → sections coverage | ✅ PASS | All 10 pains (01 §3) have a named home: P1 §12, P2 §11+§15, P3 §6 table+FAQ, P4 §3, P5 §11, P6 §9, P7 §8, P8 §6 ladder, P9 tone (04 §3), P10 §7 tile 5 + FAQ #8 |
| Competitor claims vs MVP facts | ✅ PASS | 02 fees all sourced with dates; 06 never repeats a competitor fee as a Maverick claim; "£17k–£52k" range matches 02's verified spread (Aberdeen £17,260 → Henley £52,000) |
| Keyword placement vs copy | ✅ PASS | `online mba` variant in subhead (§2) + H2s; `part time mba london` in programmes meta + FAQ #5; `mba without entry requirements` served by §6 routes table + FAQ #2; long-tail objections all have FAQ homes (8/10 of the LSI list; "level 3→7 progression" answered in FAQ #1 + Rung 1 — logged as minor) |
| CTA labels match language system (04 §5) | ✅ PASS | Primary "Start my enquiry", secondary "WhatsApp admissions", pricing "Get my fee plan", final "Reserve my place" — used verbatim in 06; no "Submit"/"Apply now" first-touch labels |
| Design-system compliance (ban list) | ✅ PASS | 05/08 explicitly enforce: glass form on hero (not white card), typographic trust strip (not logo tiles), hairline icons (no emoji), editorial paper/void rhythm, sticky mobile bar spec matches production MLP |
| Design tokens consistent | ✅ PASS | 08 re-uses #071444 / #0f2983 / #b20202 / #f5f0eb + PP Neue Montreal/Poppins exactly as `docs/mlp-design-system.md` |
| UVP consistency | ✅ PASS | "A recognised business qualification that fits the life you're already living" = 04 UVP = 06 H1-A; hero H1-B ("The MBA that fits your life") is a tested variant, not a drift |
| SOP ↔ actual process | ✅ PASS | 09 phases mirror the real pipeline; section library ⊇ the 16 blocks used in 05; gates have signable checklists |
| Schema plan feasible in this codebase | ✅ PASS | EducationalOrganization/Course/FAQPage match the pattern already shipped on `/online-mba-masters-uae` (JSON-LD from settings, FAQPage from FAQ items) |

## 2. Tone & Brand Alignment

- **PASS:** 06 copy reads practitioner-to-practitioner ("No September-or-bust", "The real cost of 'when I have time'", "a person, not a portal"); no brochure-speak detected on full read-aloud pass.
- **PASS:** "Maverick" defiance present at 3/10 wit dial (e.g., §12 FAQ "Different buyer, different job") without crossing into arrogance.
- **MINOR (fixed in v1.1):** 06 §10 lead line initially mixed third-person institutional voice ("from our own standard, said by the people it's for") — rewritten to a named-quote-forward layout in the locked copy.
- **WATCH:** Founder name/designation left as `[Founding Director]` placeholder — correct decision (settings-driven on the live site; must not hard-code a name QA cannot verify). Dev must pull from `CeoSettings` equivalent for this LP.

## 3. Claims Compliance (critical for UK market)

| Risk | Status | Action |
|---|---|---|
| "Accredited MBA" overclaim | ⚠️ CONTROLLED | All copy uses "UK-recognised diplomas (Ofqual/QUALIFI)" and "university-awarded MBAs/MScs" — matches 04 §6 inventory. Dev must not "tidy" copy into generic "accredited". |
| Partner accreditation attribution | ✅ PASS | IACBE/YÖK/YÖDAK attributed to Girne American University (the partner), never to Maverick itself. |
| Fee claims | ⚠️ GATED | All fees `[FEE]`-placeholder in 06; 09 Gate 5 blocks publish until client confirms. Competitor fees (Bayes £29k etc.) are *market context*, dated, and sourced — acceptable in copy ("premium online MBAs run £17,000–£52,000"). |
| Rankings | ✅ PASS | Zero ranking claims. |
| "Since 2012" | ✅ PASS | Sourced from the live site's own org description (HALI Management heritage); phrased "delivering since 2012", not "founded 2012" — accurate. |
| No-visa claim | ⚠️ VERIFY | FAQ #8 claims online routes need no student visa — standard and true for distance learning, but **client must confirm** before publish (some awarding-partner routes may require in-country components). Logged as launch blocker until confirmed. |
| Response-time promise ("1 business day") | ⚠️ VERIFY | Operational promise — confirm the team can actually hold it (CRO §4 makes it load-bearing). If not, soften to "promptly". |
| Testimonial permissions | ⚠️ VERIFY | 4 named alumni are on the live site today; re-confirm written permission + exact current titles (06 §9 note). |
| Registration number | ⚠️ PLACEHOLDER | `[XXXXXXX]` in footer — pull real Companies House number from the live site before publish. |

## 4. SEO Verification

- [x] Meta title 54 chars ≤ 60 ✅ · description 152 chars ≤ 155 ✅
- [x] H1 count = 1 (hero; About "statement lines" are styled statements, must be marked `<p>`/`<h2>`, not H1 — noted in 06 §5)
- [x] Primary keyword in title, subhead (first 100 words), and 2+ H2s ✅
- [x] FAQ (10 items) ↔ FAQPage schema 1:1 ✅
- [x] Internal link budget 3–5 defined (programmes, accreditations, student success, contact, our-story) ✅
- [x] Performance budget consistent across 03/08/09 (LCP < 2.5s, CLS < 0.1, JS ≤ 60KB gz, hero ≤ 180KB) ✅
- **MINOR:** `mba without dissertation` is a real query (Aston/LSIB target it) — FAQ #4 addresses it implicitly; add the exact phrase to FAQ #4's first line at build time (one-line edit, no copy risk).

## 5. Residual Risks & Launch Blockers

| # | Item | Owner | Blocker? |
|---|---|---|---|
| R1 | Fee bands confirmed in writing | Client | **YES** |
| R2 | No-visa FAQ claim confirmed | Client/admissions | **YES** |
| R3 | Testimonial permissions + titles | Client | **YES** |
| R4 | Companies House registration number | Client | **YES** (footer) |
| R5 | Response-time promise feasibility | Admissions team | Soft (copy fallback exists) |
| R6 | Founder portrait + name for §8 | Client | Soft (section degrades to faculty-only) |
| R7 | Real photos for hero/§15 (else approved stock) | Client/Design | Soft (fallback defined in 08 §4) |
| R8 | `[MONTH]` intake date source (needs a settings field, e.g., `next_intake_month`) | Dev | **YES** (drives announcement bar + final CTA) |

## 6. Verdict

> **APPROVED for design handoff (Gate 4)** — package is internally consistent, on-voice, on-system, and SEO-verified.
> **NOT approved for publish** until R1–R4 + R8 are closed (Gate 5). Every blocker has an owner and a defined fallback path; none require rework of strategy or copy architecture.

**Package sign-off:** QA Agent 11 · 2026-09-09

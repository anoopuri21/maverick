# 04 — Plan & Research Verification Report (Quality Re-Check)
**Project:** Maverick Business Academy — Bachelors LP (GCC/UAE)
**Date:** 2026-09-11 · **Agent:** QA & Review (Agent 11) · **Scope:** `00-work-plan.md` + `03-research-bachelors-gcc.md` (verified against live sources, `01-SOP`, `02-keywords`, `uploads/listing.pdf`)
**Verdict:** **PASS — ready for Client Approval Pack #1** (2 × P1 issues found & fixed during verification; 3 × P2 flags carried as client-confirmation items)

---

## 1. Method
1. **External fact-check:** every market statistic, regulatory statement, competitor price and accreditation claim in the plan + research re-checked against its named source (search re-run 2026-09-11). Any claim without a dated source → removed or demoted to "verify".
2. **Internal consistency:** plan ↔ research ↔ SOP ↔ keywords ↔ client catalogue cross-matrix (section 3).
3. **Client-catalogue ground truth:** programme counts re-derived from `uploads/listing.pdf` (pypdf re-extract 2026-09-11), not from memory.
4. Every issue gets severity (P0/P1/P2), fix, and location. P0 = blocks the approval pack; none found.

## 2. External fact-check table

| # | Claim (where used) | Source cited | Re-verified 2026-09-11 | Status |
|---|---|---|---|---|
| F1 | 57,035 new HE students 2024–25, +13%, highest in a decade (M1) | MoHESR via The National (2025-11-13) | ✅ confirmed (The National + EducationFair + Ken Research all carry the same figure) | PASS |
| F2 | KHDA Dubai 42,026 students +20%; intl +29% → 35% share; 37 intl campuses (M2) | KHDA via The National (2025-11-13) | ✅ confirmed (KHDA May-2025 release via The National + uaeHumanJourney 2026-04-29) | PASS |
| F3 | Indian students ≈ 42% of Dubai intl intake (M3) | EducationFair UAE market report (2026-05-04) | ✅ single reputable market source; flagged as report-cited (not MoHESR primary) | PASS (cite as "industry report") |
| F4 | Business & economics = #1 field in UAE (M4) | UNESCO data via EducationFair (2026-05) | ✅ | PASS |
| F5 | Federal enrolment 182,083 (2024), +33% vs 2020 (M5) | Federal stats via Ken Research (2025) | ✅ | PASS |
| F6 | 153 licensed institutions (M6) | MoHESR licence list via EduPrime (2026-07) | ✅ (agency-cited official list; number consistent with 2024-25 licensing news) | PASS |
| F7 | Education 2033 → 50% intl share by 2033 (M7) | Education 2033 strategy | ✅ | PASS |
| F8 | Middlesex Dubai 6,400+ students, +39% intl 2025 (M8) | The National (2025-11-13) | ✅ | PASS |
| F9 | 400+ UAE bachelors listings (297 BSc / 91 BA / 46 BBA / 46 BEng); Sharjah 40 (11 online) (M9) | bachelorsportal (2026); educations.com (2026-09 live) | ✅ | PASS |
| F10 | Campus band AED 35k–65k/yr; premium AED 33k–110k; S P Jain BBA online AED 100k/4yr (M10) | Fateh (2026-01); coursetakers fee table (2026-04); educations.com listing JSON-LD | ✅ (three independent listings) | PASS |
| F11 | MoE online recognition since 2023, excl. Eng/Med/Law (R1) | vitalconsular; globoprime (2025-07) | ✅ two independent commentary sources | PASS |
| F12 | **MoHESR 10-Mar-2025 policy**: conditional recognition online/distance/open/correspondence; Dh100/150/200 fees; Dataflow/QuadraBay; ~30 working days; 3-month appeal (R2) | Gulf News (2025-03-10); The Arabian Stories (2025-03-10) | ✅ two press sources, same policy text | PASS — **now the page's primary regulation fact** (was 2023-only in SOP; upgraded) |
| F13 | 2025-H2 MoHESR update eased online-credit caps (R3) | airtics (2026-07-27) | ⚠ single industry-review source → copy rule: "under the current 2025 framework" — **no credit-cap number may be quoted** until client verifies with MoHESR | PASS with guardrail |
| F14 | Private-sector: accreditation + verification usually sufficient; Certificate of Recognition needed for govt/regulated/some visa categories (R4) | airtics (2026-07); onlinetranslation.ae MOHRE guide (2026-07) | ✅ | PASS (phrasing locked in SOP §3.1) |
| F15 | MOHRE accepts accredited online degrees; bachelor's expected for AED 4k–8k/month professional bands (R5) | onlinetranslation.ae MOHRE guide (2026-07-05) | ✅ | PASS |
| F16 | RBS: EduQua + IACBE + QS Stars 5 (teaching & online), QS Global Ranking 2025; dual cert with Maverick London (already live) (partner table) | RBS's own site (rushfordswissmba.ch) + collegevidya | ✅ for EduQua/IACBE/QS via own site; **ACBSP/AACSB membership appears only on agent pages → EXCLUDED from page copy** | PASS (1 claim excluded) |
| F17 | GAU: YÖDAK + YÖK, IACBE, ECBE, ASIIN, UNWTO TedQual, NARIC-UK (partner table) | free-apply, turkeyuniversity, grokipedia (multi-source) | ✅ | PASS |
| F18 | Competitor prices: King's 12-mo BBA; Global Learners 11 online bachelors/2-yr dual-qual; Ascencia 1-yr "WES approved"; Study Wink AED 7,999; Sama AED 3,500; Tamkeen AED 22,500/12mo (Tier B/C) | educations.com + coursetakers.ae listings, 2026-09 live | ✅ all observed directly in SERP | PASS |
| F19 | bradfordia 3-page K2/K5/K9 cluster (May–Jun 2026) (Tier D) | bradfordia.org live pages | ✅ | PASS |
| F20 | Course fee anchors for §11/§12 comparison (02 doc §5) | same as F10/F18 | ✅ | PASS |

**Excluded during verification:** EduPrime's "237,000+ international students" (inconsistent with F1/F2 official figures — agency marketing claim, dropped from M-set); the Mar-2025 "18 credit hours/semester" cap (superseded per F13 — never to be quoted); RBS AACSB/ACBSP membership (F16).

## 3. Internal consistency matrix

| Check | Result |
|---|---|
| Plan stages cover every SOP phase (0→11) + all three client-approval stop-points | ✅ exact match (plan §2 vs SOP §11 timeline) |
| Plan client-dependency list = SOP Phase-0 blanks = research §3 flags (durations, fees, testimonials, photos, logos, intake, top-up body, URL) | ✅ 8/8 aligned (plan §3 is the consolidated list) |
| Personas (research §1/§6) ↔ keywords (02 §1) ↔ programme routes (listing.pdf) ↔ SOP block 6 cards | ✅ P1→K1/K2+parent-rail→BBA/BSc/BA Hons; P2→K2/K9→BBA/BSc online; P3→K8→Top-up. Each K maps to ≥1 persona + ≥1 block |
| Keyword K-refs used in research §5 intent map all exist in 02 doc | ✅ K1–K8 present |
| SOP block 4 (recognition strip) content = research R-pack + partner table | ✅ **fixed during verification** (was "MoE 2023" → now "MoHESR 2025 policy") |
| SOP brief products = listing.pdf ground truth (BBA 7 / BSc 10 / BA Hons 1 / Top-up) | ✅ **fixed during verification** (GAU BSc 13→10) |
| Competitor tier structure identical in 02 doc §5 and research §4 | ✅ same anchors, same dates |
| Do-not-promise list consistent across SOP §6 (claims whitelist), research §6.8, plan risk register | ✅ |
| "One duration per family" rule present in SOP §6 + research §3 flag + plan risk register | ✅ |
| No placeholder ([FEE]/[MONTH]/[Student Name]) appears in any *published-facing* text of plan/research/keywords | ✅ (placeholders exist only in SOP gate rules, which is correct) |

## 4. Issues found & fixed (verification log)

| # | Sev | Issue | Fix | Location |
|---|---|---|---|---|
| V1 | **P1** | GAU BSc count stated as **13** (SOP brief + SOP block 7 + research ×2) — listing.pdf ground truth = **10** | All occurrences corrected to 10; SOP PDF regenerated | `gen_sop.py` (2) → `01-bachelors-lp-sop.pdf`; `03-research…md` §3 + §6 (2) |
| V2 | **P1** | Regulation framing outdated: SOP + keywords cited **MoE 2023** as the headline fact; the operative policy is **MoHESR 2025** (conditional recognition, Mar-2025 + H2-2025 easing) | SOP §3.1 bullet, SOP block 4, SOP claims-whitelist reference, keyword K2 win-angle all updated to MoHESR-2025-primary; research R2/R3 now cited as the page's trust spine | `gen_sop.py` (3) → SOP PDF; `02-keyword…md` K2 row |
| V3 | P2 | RBS AACSB/ACBSP membership claims (agent pages only) | Excluded from page copy; only EduQua + IACBE + QS Stars allowed (SOP claims whitelist governs) | research §3 flag (documented) |
| V4 | P2 | 18-credit/semester online cap (Mar-2025 policy) may be superseded | Guardrail: no credit-cap number on page; phrasing = "current 2025 MoHESR framework — confirm with advisor" | research R3 (documented) |
| V5 | P2 | UWS programme-level details + top-up awarding body (UoG/UCLan) unverified | Carried as client-confirmation items (Approval #1/#2) — not asserted anywhere | research §3 flags + plan §3 items 7–8 |
| V6 | P3 | eduprime "237,000+ international students" inconsistent with official figures | Dropped from M-set entirely | research §1 |

## 5. Gate status

| Gate | Status |
|---|---|
| Gate 0 (brief) | ✅ PASS — filled, fee/claims blanks owned by client list |
| Gate 1 (research: sourced facts, personas, competitor matrix, search behaviour, implications) | ✅ PASS — 20 external facts PASS (3 excluded), 10/10 consistency checks PASS after V1/V2 fixes |
| Gate 1b (every keyword mapped to a page location) | ✅ PASS — 02 doc §1 placement column, unchanged by verification |

**Sign-off:** Plan + Research verified and reconciled. **Next action: build and send `05-client-approval-pack.pdf` (Approval #1). Pipeline is at the client-approval stop-point.**
— QA & Review Agent (Agent 11), 2026-09-11

# 09 · Masters Content QA Report (GCC/UAE)
**Role:** T10 (independent) · **Stage:** S7 · **Date:** 2026-09-15 · **Artifact under review:** `08-masters-landing-content-gcc-uae.md`
**Companion files:** `05` keyword map · `06` verification · `07` page plan · `../ai-team/guidelines/G02-humanization-protocol.md` · `../ai-team/guidelines/G07-production-readiness-checklist.md`

---

## 1. External fact-check (re-checked from `06`)

All 24 facts in `06` §1 re-opened on 2026-09-15. Result: 22 PASS, 0 FAIL, 2 PASS-guarded
(F6 direction-only agent sources; F15 terminology phrasing). Client-authored figures (F22,
F23, F24) carried into `08` as class-c with confirmation flags in §7 below.

## 2. Consistency matrix

| Check | Result |
|---|---|
| Brief (03) ↔ research (04) ↔ keywords (05) ↔ claims (06) ↔ plan (07) ↔ content (08) | OK, section map identical across 07 and 08 |
| Regulation framing = R-pack | OK. R2 dated 10 March 2025, Dataflow/QuadraBay, ~30 working days. R4 locked sentence verbatim in Section 2 |
| Personas ↔ keywords ↔ sections | OK. P2 primary throughout; P4 served by EMBA tab; GCC blended in hero, overview, why, journey, fees, compare |
| Durations: one per family | OK. MBA 10–15 months · EMBA 12–18 months · MSc 8–18 months, stated once each in the fees table; hero carries no duration |
| Placeholders confined to tracked blanks | OK. Zero bracket placeholders anywhere in 08 |
| Design fit | OK. All 18 content sections map to existing settings classes; the only additions are `journey` and `compare`, both existing commented-out partials |

## 3. Sweeps (run 2026-09-15, output kept below)

| Sweep | Command / method | Result |
|---|---|---|
| Em dash (—) count | `grep -o "—" 08-*.md \| wc -l` | 0 |
| En dash as sentence dash | `grep -c " – "` | 0 |
| Banned-pattern grep | G02 §4 full pattern | 0 hits |
| Placeholder grep | G02 §4 pattern incl. [your/[client/[insert | 0 hits |
| Instruction residue | "note to / todo / add here / replace this / instructions:" | 0 hits |
| Exclamation marks | `grep -c "!"` | 0 |
| Verbatim sentence repeats | sorted duplicate lines ≥ 40 chars | 0 |
| Masters-draft trap list | "programmed" (noun), "memes", programmes/programs mixed | 0 hits |
| "Whether you" openings | count | 1 (limit 1) |
| "subject to programme availability" hedge | count | 0 (replaced by the single written-confirmation note) |
| Meta lengths | title 54 ≤ 60 · description 151 ≤ 155 | pass |
| Keyword coverage | 16/16 client phrases present, one home each | pass |

## 4. Humanization scorecard (T9, attached)

| # | Check | Result |
|---|---|---|
| 1 | Banned-word/phrase grep | 0 hits |
| 2 | Rhythm | varied: five-word lines follow evidence sentences; no three same-shape paragraphs in a row |
| 3 | Concrete density | every paragraph carries a name, number, city or instruction |
| 4 | Connectors | no repeated connector family, no hedge stacks |
| 5 | Rule of three | ≤ 1 trio per section |
| 6 | Em dash / en dash | 0 of each |
| 7 | Exclamation + emoji | 0 of each |
| 8 | Openings/closings | no summary-then-restate; sections end on facts and CTAs |
| 9 | Spoken grammar | contractions used where speech uses them |
| 10 | Read-aloud test | attested, no stumbles |
| 11 | Counsellor-desk test | attested: could be said unchanged to a family at Robot Park Tower |

Score: 11/11. Facts untouched by the humanizer (claims map identical since Gate 4).

## 5. SEO / AEO check

- H1 benefit and keyword-led; exact K1 in the hero; H2s question- and phrase-shaped.
- 12 FAQs, each answer 30–60 words, direct first line, self-contained; FAQPage schema 1:1 via
  the existing Blade generation.
- Answer capsules present in the first 200 words of: trust (recognition), fees (price), why
  (working-fit), journey (intake), partners (recognised-MBA checklist), compare (fast-track).
- Cities: Dubai, Sharjah, Abu Dhabi present in headings or early copy; GCC ≥ 2×; Saudi Arabia,
  Qatar, Oman, Bahrain, Kuwait named as a group (hero + why chapter 5).
- AED in all fee copy; one INR parenthetical in the Indian-applicant FAQ.
- Internal link rails: MBA-in-UAE guide cluster, programme pages, accreditations (real routes).

## 6. Claims map (100% coverage of factual lines in 08)

| Claim (where) | Class | Source + date |
|---|---|---|
| Rushford MBA ×12, MSc ×9; GAU MBA ×6, EMBA ×16, MSc ×4; UCA + RBS Global MBA; Wolverhampton LLM; all programme names verbatim (Sections 6, 7) | a | uploads/listing.pdf |
| RBS EduQua/IACBE/QS Stars; GAU YÖDAK+YÖK/IACBE/ECBE/ASIIN/TedQual/NARIC-UK (Sections 2, 7, 13) | a | KB §4, verified 2026-09 |
| London HQ since 2012; Robot Park Tower, 2nd Floor, Sharjah (Sections 1, 2, 4) | a | client facts, KB §1 |
| MoHESR 10 March 2025 recognition route; Dataflow/QuadraBay; ~30 working days (Sections 2, 13, FAQ Q6) | b | EducationMiddleEast 2025-03; KB R2 |
| MOHRE private-sector acceptance (Section 2) | b | KB R5 |
| Birmingham Dubai AED 87,255/yr; Middlesex AED 84,872; Amity ~AED 66,000 (Section 10) | b | birmingham.ac.uk 2026; studyfromuae 2026-05; ardentoverseas 2026-07 |
| Fee range AED 16,000–40,000; durations 10–15 / 12–18 / 8–18 months; stats 4,500+ / 1,500+ / 10+ / 120+ and 979 / 11.2 yrs / 77 countries / 98.70% / 33.7 | c | client sample PDF, flagged §7 |
| Rotational intakes incl. September 2026 (Sections 5, 18, FAQ Q5) | c | client keyword input + house glossary; intake calendar confirmation open |

Zero unmapped claims. Zero banned promises (no best/top/#1, no salary figures, no visa or job
guarantees, no invented testimonials).

## 7. Client confirmations open before publish (tracked, not placeholders)

| # | Item | Current handling in copy | Needed |
|---|---|---|---|
| 1 | Durations per family (10–15 / 12–18 / 8–18) | client-authored wording used, single statement per family | written confirmation |
| 2 | Fee range AED 16,000–40,000 | client-authored wording used | written confirmation |
| 3 | Trust + class-profile stats (4,500+ · 1,500+ · 10+ · 120+ · 979 · 11.2 · 77 · 98.70% · 33.7) | client-authored figures used | written verification + date stamp |
| 4 | Testimonial quotes (Section 15) | section intro only; items load from DB | permissioned, named quotes |
| 5 | Career story cards (Section 11) | outcomes copy only; story slots in settings | permissioned stories |
| 6 | Intake calendar incl. September 2026 | rotational-intake wording | intake dates confirmed |
| 7 | Scholarship/offer positions | honest "ask your advisor" wording | confirmed offers, if any |
| 8 | "No GMAT" style entry-test claims | not used; FAQ says requirements vary by route | per-programme confirmation if ever claimed |

None of these blocks Approval #2 of the copy itself; each has safe wording in place today.

## 8. Gate status & verdict

| Gate | Status |
|---|---|
| Gate 0 brief (03) | PASS |
| Gate 1 research (04) | PASS |
| Gate 1b keywords (05) | PASS: 15 supplied + 1 variant, one home each |
| Gate 2 verification (06) | PASS: 0 P0 |
| Gate 3 strategy (07) | PASS |
| Gate 4 draft | PASS |
| Gate 5 humanization | PASS: 11/11, zero dashes, zero banned patterns |
| Gate 6 optimization | PASS: meta, capsules, FAQ↔schema 1:1, cities, currency |
| Gate 7 QA & production | PASS: all sweeps 0, claims map 100% |

**Verdict line: APPROVED (0×P0, 0×P1, 8 tracked client confirmations per §7).**

Content is ready for Client Approval #2 as the internal pipeline holds it: nothing ships to
the public page until the settings are updated and the §7 confirmations land in writing.

**Sign-off:** T10, 2026-09-15

## Addendum 2026-09-16: v2 trim + client pack

**Why:** client asked for a shareable DOCX + PDF of the page content, capped at 3,000 to 3,500 words, with keywords and sections highlighted and NEW sections flagged.

**v2 trim:** 08 cut from the v1 draft to 3,091 content words (verified by the generator). All 16 tracked keyword phrases retained, 12 FAQs kept, GCC/UAE blend and recognition story intact.

**Re-run sweeps on v2:** em dash 0, en dash 0, banned AI words 0, banned phrases 0, placeholders 0, exclamation marks 0, "not just" 0, "Whether you" 1 (allowed once). 16/16 keyword phrases present.

**Copyright verification:** distinctive v2 phrases spot-searched on the web (0 external matches) and grepped against the live site and other landing docs (0 internal matches outside gulf-masters). All claims trace to the dated sources already logged in 04/06. Verdict: original content.

**Client pack:** `10-masters-landing-content-client-gcc-uae.docx` + `.pdf` generated by `scripts/gen_masters_content_client.py` from 08 (sections 1 to 18 only, META block excluded). Renders: yellow highlight on every keyword phrase, navy shaded heading per section, red [NEW SECTION] badge on 5. Journey and 16. Compare, tables for fees/compare/metrics/partners/stories/testimonials/stats. Generator self-checks word count (3,000 to 3,500), keyword coverage, and dash/placeholder sweeps before writing files.

**Verdict:** APPROVED. v1 verdict stands; v2 supersedes v1 copy.

## Addendum 2026-09-16 (b): Overview lists replaced per client input

Client supplied the base themes: "A Strong Learning Community with Powerful Networking, ASK Quotient Development, Skills- and Knowledge-Based MBA Learning, and Real-World Case Studies." Overview section 3 now carries exactly 5 lists derived from that base (Skills- and Knowledge-Based split into two items), rewritten as unique SEO-friendly copy:

1. A strong learning community with powerful networking
2. ASK Quotient development (Attitude, Skills, Knowledge; ASK model verified against education literature)
3. Skills-based MBA learning
4. Knowledge-based MBA learning
5. Real-world case studies

Keyword homes kept inside the section: K2 "MBA in Dubai" (heading), K6 "Dubai for working professionals" (intro). Heading, intro, and both CTAs updated to match the new lists; all other sections untouched. META internal-links spec: removed the now-dead "Top up degrees" anchor (its copy left the page with the old lists). Sweeps re-run: 0 dashes, 0 banned, 0 placeholders; content words 3,111 (cap 3,000 to 3,500); 16/16 keywords. DOCX and PDF regenerated from the same source.

## Addendum 2026-09-16 (c): programmes and universities aligned to uploads/listing.pdf

Client flagged that programme and university names did not match the official listing. 08 rebuilt against uploads/listing.pdf as the single source of truth:

- Rushford Business School (Switzerland): 12 MBAs + 9 MSc, exact catalogue names
- Girne American University (North Cyprus): 6 MBAs + 16 Executive MBAs + 4 MSc with Thesis
- University for the Creative Arts (UK) with Rushford: Global MBA
- University of Wolverhampton (UK): Master of Laws
- Removed from content: Cardiff Metropolitan, IIM Kozhikode, University of Worcester, "Cleveland US" for Rushford, Master of Education, invented EMBA and Global MBA specializations. Nothing added beyond the listing.
- Fees table restructured to catalogue families; per-programme fees "confirmed per programme" (client sample's own approach), overall AED 16,000 to 40,000 range kept as client-authored indicative range; durations 10 to 15 / 12 to 18 / 8 to 18 months per family.
- Awarding-body consistency: blanket UK or British degree claims replaced with awarding-university wording (hero, trust quote, Why block, final CTA, FAQ 12), since partners span Switzerland, North Cyprus, and UK.
- Partner accreditation lines updated to verified KB facts: RBS EduQua and IACBE; GAU YÖDAK and YÖK plus IACBE; UCA and UOW as UK public universities.
- Verification: all 49 catalogue master's programmes present verbatim; zero non-catalogue university or programme names; sweeps all 0; 16/16 keywords; content words 3,126. DOCX, PDF, and the client report regenerated.

## Addendum 2026-09-16 (d): UWS MBA added per client instruction

Client instructed one addition only: University of the West of Scotland, UK (UWS) with programme "MBA in International Business". Added in: section 6 (new MBA tab + intro count four to five), section 10 fees table (one row, fees and duration confirmed per programme), section 13 partners (one row, UK public university). No other content changed. Word count 3,189 (cap 3,000 to 3,500); sweeps all 0; 16/16 keywords. DOCX, PDF, and client report regenerated.

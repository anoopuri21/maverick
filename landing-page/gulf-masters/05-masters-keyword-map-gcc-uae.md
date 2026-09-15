# 05 · Masters Keyword Map (client list, validated)
**Role:** T4 · **Date:** 2026-09-15 · **Source:** 15 keywords supplied by the client with the task brief
**Companion files:** `03` (brief) · `04` (research) · `../ai-team/guidelines/G03-seo-playbook.md` (rules) · `../ai-team/03-content-pipeline.md` §8 (method)

Rule applied: the client's list is the spine. Each phrase is validated against intent and the
live SERP picture, then given exactly one home. Near-variants share a family home and appear
once each, naturally. No phrase is forced, and no phrase repeats its block verbatim anywhere.

---

## 1. The 15 client keywords, validated and placed

| # | Client keyword | Intent | Validation note (2026-09) | One home on the page |
|---|---|---|---|---|
| 1 | MBA in UAE | Commercial head | The market's head term; Maverick already ranks the cluster via the Jan-2026 guide (MG10) | Hero: headline + subheading |
| 2 | MBA in Dubai | Commercial head, city | Highest city-level volume; SERP mixes campus schools and agents | Overview section heading |
| 3 | MBA for working professionals in UAE | Commercial, persona-led | The proven Maverick cluster (01 §1, K2); strongest fit for our delivery model | Why section: heading and "made for working professionals" chapter |
| 4 | Online MBA in UAE / Flexible MBA in UAE | Commercial, delivery-led | Supplied as one dual phrase; both forms live in the hero paragraph, treated as one home | Hero paragraph |
| 5 | MBA fees in UAE | Price | Price-first searchers; lands where the numbers are | Fees section heading |
| 6 | MBA in Dubai for working professionals | Variant of #3 | Same family; woven once into the overview intro, not repeated elsewhere | Overview intro (family variant) |
| 7 | Affordable MBA in UAE | Price, value | Value comparison intent; belongs with the campus price anchors | Fees: "what affordable means" block |
| 8 | Part-time MBA in UAE | Delivery | Premium schools own "part time" headlines (Birmingham 2-yr part-time); we own the behaviour | Compare section: part-time row + FAQ answer |
| 9 | MBA with flexible payment plan UAE | Price, payment | Instalment intent; our AED-instalment rail answers it | Fees: payment-plan block |
| 10 | MBA September 2026 intake | Timing | The market's reference intake (MG9: Birmingham closes 10 Aug 2026). Our rotational intakes answer it honestly | Journey section: intake step + FAQ |
| 11 | MBA scholarship UAE | Offer | Offer rails run client-confirmed only (internal strategy D1); no invented aid | Fees: scholarship block, honest wording |
| 12 | MBA specializations in UAE | Catalogue | RBS MBA ×12 + GAU MBA ×6 + EMBA ×16 give a real, countable answer | MBA families section intro |
| 13 | Fast-track MBA UAE | Objection | The 12-month mill category; answer with the R2 recognition-risk frame, category only | Compare section: fast-track note + FAQ |
| 14 | International MBA UAE | Credibility | Gulf buyers filter for international awards; our partners are the proof | Partners section heading area |
| 15 | MBA admission requirements UAE | Process | Entry-question intent; feeds the journey + FAQ | FAQ lead question + journey step 2 |

Coverage check against the journey chain (G03 §1): discover (1, 2) · verify (14, recognition copy)
· narrow (12, 13) · localise (2, Sharjah/GCC lines) · de-risk on price (5, 7, 9, 11) · compare (8, 13) ·
timing (10) · process (15). Complete.

## 2. Handling of near-duplicates

- #3 and #6 are one family (working-professional MBA). #3 headlines the why section; #6 appears
  once in prose in the overview. Nowhere else.
- #4 arrives as "Online MBA in UAE / Flexible MBA in UAE". Both forms are used once, in the hero
  paragraph, as natural phrasing, not as a slash-separated string.
- #5, #7, #9, #11 are the price family. Each gets its own block inside the fees section so the
  section reads as four short answers, not one keyword dump.

## 3. Secondary phrases (weave, never headline)

weekend-friendly classes · Saturday classes · rotational intakes · no student visa · AED
instalments · Sharjah office · Robot Park Tower · Dubai time zone support · Riyadh · Doha ·
Muscat · Manama · Kuwait City · online MSc Dubai · Master's degree in UAE online · 1-year MBA
route · executive MBA · Global MBA · LLM · Ofqual · QUALIFI · IACBE · EduQua · YÖK · YÖDAK ·
Dataflow · QuadraBay · Recognition Report · degree attestation · UK apostille · no GMAT
(verify per programme before publish) · MBA for Indians in UAE.

## 4. Meta (candidates, within limits)

**URL:** keep `/online-mba-masters-uae` (existing route, already indexed; upgrade in place).

**Title (≤ 60 chars):**
1. `Online MBA in UAE & Dubai for Professionals | Maverick` (55) · recommended
2. `MBA in UAE: Online, Flexible, AED Plans | Maverick` (51)
3. `Online MBA & Master's in UAE | No Visa | Maverick` (49)

**Description (≤ 155 chars):**
1. `University-awarded online MBA and Master's degrees for UAE and GCC professionals. No student visa, AED instalments, Sharjah office. Start your enquiry.` (153) · recommended
2. `Study an online MBA in Dubai or the UAE while you work. UK and international awards, flexible payment plans, rotational intakes. Speak to an advisor.` (149)

## 5. Schema request (handed to T5 / build team)

- **FAQPage:** the 12 FAQs in `08` §17, 1:1, complete Q + A text (already generated by the
  blade from `MbaMastersFaqSettings`).
- **Course:** one per family once durations are client-confirmed: MBA (provider Rushford
  Business School), EMBA (provider Girne American University), MSc (provider per route),
  Global MBA (provider University for the Creative Arts), LLM (provider University of
  Wolverhampton). courseMode: Online. No duration or price fields until confirmed.
- **EducationalOrganization:** Maverick Business Academy, Sharjah address primary (Robot Park
  Tower, 2nd Floor), London (Ruislip) secondary; areaServed: UAE, SA, QA, OM, KW, BH, IN.
- **BreadcrumbList:** Home → Online MBA & Master's UAE.

## 6. Local SEO inputs

- GBP at Robot Park Tower: monthly post linking this page; category unchanged.
- NAP identical everywhere (G03 §5).
- Internal links: to the live `/mba-in-uae-complete-guide…` cluster anchor (MG10), programme
  pages, `/accreditations`. Real routes only, checked against `routes/web.php`.
- Cities: Dubai, Sharjah, Abu Dhabi each appear in an H2 or the first 100 words of their
  sections; GCC appears ≥ 2×; Saudi Arabia, Qatar, Oman, Bahrain, Kuwait named at least once
  as a group.

## 7. Anti-stuffing contract

- 15 client phrases, one home each, ≈ 1 keyword phrase per 250+ words of finished copy.
- No sentence may open with "Whether you are looking for…" more than once in the whole page.
- No "subject to programme availability" hedge more than once (fees note only).
- No verbatim block repeats between sections. T10 greps all three in `09`.

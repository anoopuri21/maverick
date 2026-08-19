# 🏁 Education FAQ Project — Final Summary

**Completed:** 2026-08-19 · **Status:** All 7 providers approved by owner
**Agent:** `.claude/agents/education-faq-specialist.md` (SME + SEO + Writer + Editor/QA persona)

---

## 1. Delivered

| # | Provider | Category Sets | FAQs | Programmes | Approved File |
|---|---------|---------------|------|-----------|---------------|
| 1 | Rushford Business School (RBS) | 4 (BBA · MBA · MSc · Doctoral) | 30 | 41 | `approved/rushford-business-school.md` |
| 2 | Girne American University (GAU) | 5 (BSc · MBA · EMBA · MSc Thesis · PhD) | 32 | 43 | `approved/girne-american-university.md` |
| 3 | University of the West of Scotland (UWS) | 1 (BA Hons Global Business) | 9 | 1 | `approved/university-west-scotland.md` |
| 4 | University for the Creative Arts (UCA) | 1 (Global MBA, dual with RBS) | 9 | 1 | `approved/university-creative-arts.md` |
| 5 | University of Wolverhampton (UOW) | 1 (Master of Laws — LLM) | 9 | 1 | `approved/university-wolverhampton.md` |
| 6 | Gatehouse Diplomas | 1 (Level 7 Diplomas) | 9 | 4 | `approved/gatehouse-diplomas.md` |
| 7 | Qualifi Diplomas | 3 (L3 · L5 Extended · L7) | 19 | 45 | `approved/qualifi-diplomas.md` |
| | **TOTAL** | **16** | **117** | **136** | + 7 selection/ranking reports in `reports/` |

*(FAQ count = visible published questions; every question carries an HTML-comment layer with
target keyword + source reference, invisible after upload.)*

## 2. Rules enforced across all files

1. **Category-generic** — one FAQ set valid for every programme in its category; no
   single-programme fact generalised without a VERIFY flag.
2. **Strict country-neutral** — zero country/city/region names in content (verified by grep
   QA each cycle); institutions and regulators referenced by proper name/acronym only
   (IACBE, Ofqual, RQF, ECTS, CHF).
3. **Data integrity** — no invented fees, durations, rankings or volumes; every unverified
   item carries an inline `[VERIFY]` tag plus a row in that file's Facts-to-Verify table.
4. **Compliance blacklist** — "WES approved" claims excluded twice (UCA, UOW sources);
   "equivalent to a master's degree" over-claim avoided (Gatehouse/Qualifi); LLM
   practice-rights disclaimer added (UOW); no job/placement guarantees anywhere.
5. **Cannibalisation control** — unique query targets across all 7 files (e.g. "PhD vs DBA"
   only in RBS; "MBA vs EMBA" only in GAU; L7-definitional only in Gatehouse; ladder angle
   only in Qualifi).

## 3. ⚠️ Verification backlog — UPDATED after deep research (2026-08-19)

### ✅ Top 4 publish-blockers — RESOLVED & FIXED IN CONTENT

| # | Blocker | Resolution (source) | Fixed in |
|---|---------|--------------------|----------|
| 1 | **Durations** | RBS MBA = **16 months / 90 ECTS**; RBS MSc = **60/90/120 ECTS → ~12/18/24-month routes**; RBS BBA = **180 ECTS / 6 semesters / ~36 months** (all official rushford.ch); GAU MBA = **~1.5–2 years / up to 4 semesters** (corroborated multi-source). *(Old GAU BSc 20–24-month figure exists only in the repo seeder, not in FAQ content — seeder fix is a separate dev task.)* | RBS + GAU files |
| 2 | **Accreditation wording** | Gatehouse Awards = **Ofqual-recognised AO** (confirmed; Edu Leadership QAN 610/7539/5); Qualifi = **Ofqual-approved & regulated, ref RN5160**, QANs on RQF; GAU YÖDAK/YÖK = phrased country-neutrally as "higher-education authorities" (acronyms only); UWS/UOW/UCA heritage = decision to keep neutral phrasing, no dates | Gatehouse, Qualifi, GAU, UWS, UOW, UCA files |
| 3 | **UWS top-up structure** | **Confirmed top-up route, ~12 months, online** — consistent across 3 independent partner institutions; entry = completed HND/equivalent or relevant work experience (subject to approval) | UWS file |
| 4 | **UCA entry-criteria conflict** | **Resolved by source hierarchy:** our offer is the Rushford-delivered route → official apply.rushford.ch criteria apply (**bachelor's, any discipline**); the stricter criteria belong to a different delivery partner's route | UCA file |

**Bonus finding:** RBS MBA specialisations also carry an **Ofqual-regulated Level 7
Diploma award by default** (official portal) — added to content as a value point (WES
wording remained excluded).

### Still open (non-blocking — need partner offer sheets, not public research)

1. **All fee amounts + currencies** (only UCA has portal-published figures: CHF 9,900 /
   1,800 + 6×1,400 — reconfirm for our intake).
2. **English test thresholds** per provider/level (official levels vs third-party figures).
3. **Qualifi L7 Law → UOW LLM feeder** + L5 → degree top-up receiving programmes in OUR
   portfolio (funnel unlockers).
4. **Delivery mode per GAU category** (esp. Psychology/EMBA/PhD) + entry thresholds
   (GAU ~70% BSc, RBS ~55%, EMBA experience years, EPD full form).
5. Gatehouse QANs for the 3 remaining tracks.

> Har file ke andar ab "Still open" vs "✅ Resolved" ka split table hai — jo bacha hai wo
> sab partner offer-sheet level ka data hai, public research se aage clear nahi ho sakta.

## 4. Recommended next steps

1. **Resolve the verification backlog** with each partner's official offer sheet; the agent
   will patch answers and remove VERIFY tags file by file.
2. **FAQPage JSON-LD schema** generation per file at publish time (all questions; Tier 1
   first). The agent can generate these on request.
3. **Wire into the site** — the repo already has a polymorphic `Faq` model
   (`app/Models/Faq.php`) + `ProgramCategory`; a seeder/Filament import can be generated
   from the approved markdown whenever you want DB-backed rendering.
4. **Internal-linking pass** once programme page URLs are final (hub questions → programme
   pages; comparison questions → both categories; ladder questions → cross-provider).
5. **GSC validation loop** — 30–60 days post-publish, update each report's estimated tiers
   with actual impression/query data.

## 5. Pipeline artefacts

```
content/faqs/
├── README.md                 # pipeline docs
├── TRACKER.md                # status board (all ✅)
├── PROJECT-SUMMARY.md        # this file
├── inputs/listing.md         # normalized programme list (source of truth)
├── _templates/               # output template
├── approved/                 # 7 upload-ready FAQ files
└── reports/                  # 7 selection & global-ranking reports
```

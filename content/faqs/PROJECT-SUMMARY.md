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

## 3. ⚠️ Consolidated verification backlog (before publishing)

**Publish-blockers (resolve first):**
1. **RBS/GAU durations** — third-party figures used (MBA 12–24 mo / MSc 18–24 mo / GAU MBA
   1.5–2 yr); GAU BSc duration conflict (20–24 months vs 4-year curriculum) still open from
   the seeder.
2. **Accreditation/regulation wording** — GAU (YÖK/YÖDAK under country-neutral rule),
   Gatehouse & Qualifi (Ofqual-regulated status), UWS/UOW/UCA heritage phrasing.
3. **UWS top-up structure** — 3 answers depend on the ~1-year top-up assumption.
4. **UCA entry-criteria conflict** — official portal (bachelor's, any discipline) vs
   alternate route (age 21 + 3 yrs experience + higher English scores).

**High-value confirmations (unlock funnel content):**
5. **Qualifi L7 Law → UOW LLM feeder** — completes the "diploma → master's" ladder story;
   add bidirectional links on confirmation.
6. **Qualifi L5 → degree top-up receiving programmes** (e.g. business degree routes).
7. **All fee amounts + currencies** (only UCA has portal-published figures: CHF 9,900 /
   1,800 + 6×1,400 — reconfirm for our intake).
8. **Delivery mode per category** (esp. GAU Psychology/EMBA/PhD).

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

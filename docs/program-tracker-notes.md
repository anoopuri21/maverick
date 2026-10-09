# Program Tracker — Parse Notes & Flag Register
**Source:** `docs/listing.pdf` (5 pages) · **Tracker:** `docs/program-tracker.csv` · Parsed 2026-10-05
**Scope:** Doctorate + Diploma only. Bachelors / Masters / MBA / EMBA / MSc / LLM excluded by instruction.

---

## 1. Headline counts

| | Count |
|---|---|
| **Doctorate programs** | **20** |
| **Diploma programs** | **49** |
| **Total in tracker** | **69** |
| **Unique awarding entities** | **4** (2 universities + 2 awarding bodies) |
| Rows in full PDF (all levels) | 136 |
| Rows excluded (Bachelors/Masters/etc.) | 67 |

### Doctorate breakdown (20)

| Source block in PDF | Awarding entity | Rows |
|---|---|---|
| `Doctoral Research Topic Specializations (PhD/DBA/EPD)` | Rushford Business School (RBS), Switzerland | 13 |
| `PhD Programs` | Girne American University (GAU), North Cyprus | 7 |

### Diploma breakdown (49)

| Source block in PDF | Awarding entity | Rows |
|---|---|---|
| `Gatehouse Level 7 Diploma Programs` | Gatehouse | 4 |
| `Level 3 Qualifi Diploma Specializations` | Qualifi | 8 |
| `Level 5 Qualifi Diploma Specializations` | Qualifi | 14 |
| `Level 7 Qualifi Diploma Specializations` | Qualifi | 23 |

### Unique awarding entities (exactly as written in the listing)

| # | Name as in listing | Type | Programs in scope |
|---|---|---|---|
| 1 | `Rushford Business School (RBS), Switzerland` | University (numbered heading `1.`) | 13 Doctorate |
| 2 | `Girne American University (GAU), North Cyprus` | University (numbered heading `2.`) | 7 Doctorate |
| 3 | `Gatehouse` | Awarding body — **no parent university in the listing** | 4 Diploma |
| 4 | `Qualifi` | Awarding body — **no parent university in the listing** | 45 Diploma |

---

## 2. ⚠️ Correction to the Phase 1 estimate

Phase 1 reported **13 Doctorate** and **~14 EMBA**. A full re-parse of page 3 shows the earlier read was truncated. Corrected figures:

| Item | Phase 1 said | Actual |
|---|---|---|
| Doctorate | 13 | **20** (13 Rushford topics + **7 GAU PhD Programs**) |
| Executive MBA (GAU) | 14 | 16 |
| GAU `MSc Programs (with Thesis)` | missed | 4 |
| Total PDF rows | 123 | **136** |

Diploma count (49) is unchanged. **The tracker uses the corrected numbers.**

---

## 3. Flag register

Every flag below is already written into the `Notes/Flags` column of the CSV, per row.

### 🔴 BLOCKER — must be resolved before research starts

| ID | Rows | Issue |
|---|---|---|
| **AMB-DOC-01** | S.No 1–13 | Rushford's 13 entries are **research topics, not programme titles** — the heading reads `Doctoral Research Topic Specializations (PhD/DBA/EPD)`. No award type is attached to any individual row. Decision needed: 13 topic pages, 39 pages (topic × PhD/DBA/EPD), or 3 parent award pages listing the 13 topics as specialisations. **This single decision changes scope from 13 to 39 pages.** |
| **GAP-SCHEMA-03** | S.No 21–69 (all 49 Diplomas) | Gatehouse and Qualifi appear only as section headings — **no parent university is listed**. They are awarding bodies, not universities, and no matching `UniversityPartner` record exists. The DB currently expects a university link on every program. |
| **AMB-ENT-01** | S.No 21–24 | `Gatehouse` is written without a legal entity name in the listing. Exact registered name must be verified by the Research Analyst before a partner record is created — do not assume. |

### 🟠 NAMING / NORMALISATION

| ID | Rows | Issue |
|---|---|---|
| **NAME-01** | S.No 25–46 vs 47–69 | Level 7 Qualifi titles carry the `Qualifi` prefix **inside the title** (`Qualifi Level 7 Diploma in …`); Level 3 and Level 5 titles do not. Inconsistent in the source. Normalisation decision needed before slugs are generated. |
| **NAME-02** | S.No 58 | `Qualifi Level 7 International Diploma in Occupational Health and Safety Management` — the title **wraps across two lines** in the PDF. Reconstructed as shown; verify against the Qualifi register. |
| **NAME-03** | S.No 65 | Abbreviated as `IT` while L3/L5 use `Information Technology`. Confirm the official title. |
| **NAME-04** | S.No 3 | `Operational Management` (not "Operations Management"). Kept verbatim — do not normalise without approval. |
| **NAME-05** | S.No 27, 58, 59 | `Integrated Diploma` (S.No 27) and `International Diploma` (S.No 58, 59) are distinct from plain `Diploma`. Confirm each is a separate qualification, not a typo. |

### 🟡 DUPLICATES — same subject, different award/level/body

Not errors, but **slug collisions** are guaranteed if titles alone are used. The existing codebase pattern for this is `{title}-{university}` (see `mba-in-marketing-rushford-business-school`).

**Cross-awarding-body near-identical pairs (strongest flags):**

| Rows | Pair |
|---|---|
| 21 ↔ 51 | Gatehouse `Level 7 Diploma in Strategic Leadership & Management` ↔ Qualifi `… Strategic Management and Leadership` |
| 22 ↔ 69 | Gatehouse `Level 7 Diploma in Educational Leadership & Management` ↔ Qualifi `… Educational Management and Leadership` |
| 23 ↔ 35 ↔ 56 | Psychology diploma at Gatehouse L7, Qualifi L5, Qualifi L7 — **three entries, two bodies** |

**Cross-university doctorate subject overlaps:**

| Rows | Pair |
|---|---|
| 4 ↔ 15 | Rushford `Marketing` ↔ GAU `PhD in Marketing` |
| 9 ↔ 18 | Rushford `Educational Management & Leadership` ↔ GAU `PhD in Educational Administration` |
| 11 ↔ 20 | Rushford `Hospitality, Travel, & Tourism` ↔ GAU `PhD in Tourism & Hospitality` |
| 1 ↔ 12 | Rushford `Supply Chain Management` ↔ Rushford `Logistics & Supply Chain Management` (**same list, internal overlap**) |

**Same subject across Qualifi levels (L3 / L5 / L7):**
Business Management (25, 33) · Health and Social Care (29, 34, 55) · Hospitality and Tourism Management (30, 36, 60) · Accounting and Finance (31, 37, 62) · Information Technology / IT (32, 39, 65) · Law (38, 63) · Cyber Security (43, 67) · Occupational Health and Safety (45, 57, 58)

**Internal Qualifi L5 IT family (39–44):** `Information Technology` plus `IT - Networking`, `IT - Web Design`, `IT - E-commerce`, `Cyber Security`, `Networking and Cyber Security` overlap heavily. Confirm whether these are six separate qualifications or pathways of one.

**Internal Qualifi L7 strategic-management cluster (49, 50, 51):** `Executive Management`, `Strategic Management and Innovation`, `Strategic Management and Leadership` are closely overlapping.

---

## 4. Parse audit — what was excluded and why

| PDF block | Rows | Excluded as |
|---|---|---|
| Rushford `Undergraduate Courses` (BBA) | 7 | Bachelors |
| Rushford `Postgraduate Courses - MBA` | 12 | Masters |
| Rushford `Postgraduate Courses - MSc` | 9 | Masters |
| GAU `BSc Programs` | 10 | Bachelors |
| GAU `MBA Programs` | 6 | Masters |
| GAU `Executive MBA (EMBA) Programs` | 16 | Masters |
| GAU `MSc Programs (with Thesis)` | 4 | Masters |
| `3. University of the West of Scotland, UK (UWS)` → BA (Hons) in Global Business | 1 | Bachelors |
| `4. University for the Creative Arts, UK (UCA)` → Global MBA + Rushford Business School, Switzerland | 1 | Masters |
| `5. University of Wolverhampton, UK (UOW)` → Master of Laws | 1 | Masters |
| **Total excluded** | **67** | |

67 excluded + 69 in tracker = **136 total rows**. ✅ Reconciles.

**No "Certificate" block exists in the PDF** — nothing was excluded under that label.

---

## 5. Decisions needed before Phase 4 (Research Analyst) starts

1. **AMB-DOC-01** — Doctorate granularity: 13 / 39 / 3 pages? *(scope-defining)*
2. **GAP-SCHEMA-03** — Create `Gatehouse` + `Qualifi` as `UniversityPartner` records, or make the university link nullable for diplomas?
3. **NAME-01** — Strip the `Qualifi` prefix from L7 titles for consistency, or keep verbatim?
4. **Category taxonomy** — one `Doctorate` + one `Diploma` category, or split by level (`Level 3 Diploma`, `Level 5 Diploma`, `Level 7 Diploma`) / by award (`PhD`, `DBA`, `EPD`)?
5. **Duplicate policy** — one page per listing row (69 pages, slug-disambiguated), or merge the near-identical cross-body pairs?

---

*Tracker columns `Research Status` and `Content Status` are initialised to `Pending` for all 69 rows and are updated by the File Organizer Agent as programmes move through the pipeline defined in `docs/team-charter.md`.*

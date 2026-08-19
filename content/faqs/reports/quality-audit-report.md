# 🔍 FAQ Quality Audit Report + Phase 2 Improvement Plan

**Audit date:** 2026-08-20 · **Scope:** All 7 approved FAQ files (117 questions, 136 programmes)
**Method:** Programmatic checks (coverage cross-match, duplicate detection, answer-length analysis,
category counts) + plagiarism spot-checks (quoted web searches) + qualitative SEO/AEO review.

---

## 1. Scorecard (ek nazar me)

| # | Check | Result | Grade |
|---|-------|--------|-------|
| 1 | Programme coverage (136/136) | Substantively complete; 3 cosmetic naming inconsistencies | 🟢 PASS* |
| 2 | Duplicate questions / cannibalisation | 1 real cross-file duplicate + 2 intra-file pattern repeats | 🟡 FIX NEEDED |
| 3 | Snippet compliance (first-sentence 40–60 words) | 8 answers over-length (mostly hub/list answers) | 🟡 IMPROVE |
| 4 | Thin content (<30-word answers) | 10 thin answers (mostly fees/flexibility) | 🟡 IMPROVE |
| 5 | Category counts (5–10 per category) | All 16 categories in range | 🟢 PASS |
| 6 | SEO fundamentals (unique targets, keywords, structure) | 114 unique query targets; keyword layer intact | 🟢 PASS |
| 7 | AI Overview (AEO) readiness | Strong foundation; schema + entity-lead gaps | 🟡 IMPROVE |
| 8 | Copyright/originality (<5% similarity target) | Spot-checks: zero verbatim matches; tool-certification pending | 🟢 PASS* |
| 9 | Country-neutral + compliance blacklist | Re-verified: zero violations | 🟢 PASS |

**Overall: PUBLISH-QUALITY with a defined improvement list.** Koi bhi issue "blocker" level ka
nahi hai — sab Phase 2 me systematically fix ho sakta hai.

---

## 2. Detailed Findings

### Finding 1 — Coverage: 136/136 covered, par 3 naming inconsistencies (LOW severity)

Cross-match script ne har programme ko listing vs FAQ files me check kiya. Sab programmes
substantively covered hain, lekin 3 jagah naam ka format official listing se alag hai:

| File | Issue | SEO impact |
|---|---|---|
| `qualifi-diplomas.md` | "International OHS Management" abbreviated — full name "Qualifi Level 7 International Diploma in Occupational Health and Safety Management" nahi likha | Students exact programme name search karte hain — abbreviation match nahi karega |
| `qualifi-diplomas.md` | IT tracks "IT-Networking" (no spaces) vs listing "IT - Networking" | Minor — cosmetic |
| `rushford-business-school.md` | "Hospitality, Travel & Tourism" vs listing "Hospitality, Travel, & Tourism" | Negligible — punctuation only |

**Fix:** Applies-to lines me official full names use karna (P0, 10 min ka kaam).

### Finding 2 — Duplicates: 1 REAL cross-file duplicate (MEDIUM severity) ⚠️

Sabse important catch:

- **"How are MSc fees structured, and are scholarships available?"** — **RBS aur GAU dono
  files me EXACT same question hai.** Ye QA checklist ke "questions unique across project"
  rule ka violation hai jo pichle QA me slip ho gaya. Dono pages Google me is query ke liye
  compete karenge. **Fix:** ek ko rephrase karna (e.g. GAU: "What do the thesis-based MSc
  programmes cost, and is scholarship support available?").
- Qualifi file ke andar hub questions ("Which Qualifi Level X Diplomas...") aur fees
  questions ("How are Level X fees structured") teeno levels par same pattern hai — same
  page par hai isliye cannibalisation nahi, par PAA variety ke liye differentiate karna
  better hai (P1).

### Finding 3 — Snippet compliance: 8 over-length first sentences (MEDIUM)

Rule tha: pehla sentence 40–60 words snippet-ready. 8 answers me pehla sentence 45+ words
ka hai — mostly hub/list answers (specialisation lists) aur entry-requirements answers jahan
routes ek hi sentence me likh diye. **Fix:** in 8 me short lead-sentence add karna (e.g.
"Rushford offers seven BBA specialisations." — fir list), taaki snippet extraction clean ho.

Affected: RBS (BBA hub, MBA hub, doctoral entry), GAU (PhD entry), Gatehouse (hub),
Qualifi (L7 progression), UWS (entry), UOW (entry).

### Finding 4 — Thin answers: 10 answers <30 words (MEDIUM)

Mostly fees answers (amounts available nahi the isliye short rah gaye) aur flexibility
answers. Ye Google ke "helpful content" standard me weak lagte hain aur AI Overviews inhe
skip kar sakta hai. **Fix:** Phase 2 me enrich — fees answers me payment-process detail,
scholarship-review process, offer-validity concept add karo (bina amounts invent kiye);
flexibility answers me study-hours guidance, support systems detail.

### Finding 5 — SEO fundamentals: PASS

- 114 unique query targets (117 questions me 3 collisions — Finding 2)
- Har question ke saath target keyword + source (HTML comment layer) intact
- Branded + generic mix balanced (18 Tier-1, 58 Tier-2)
- UK spelling consistent, mobile-friendly formatting (bold facts, bullets)

### Finding 6 — AI Overview (AEO) readiness: strong base, 4 gaps

**Jo already sahi hai (AI Overview citations ke liye):** direct answer-first structure ✅,
self-contained answers (context ke bina samajh aate hain) ✅, entities named (IACBE, Ofqual,
ECTS, QANs) ✅, no fluff/marketing filler ✅, Q&A format (AI Overviews ka favourite) ✅.

**Gaps:**
1. **FAQPage JSON-LD schema abhi generate nahi hua** — AI Overviews aur PAA दोनों ke liye
   sabse bada lever. (Planned, publish par.)
2. Kuch answers ke first sentence me entity name nahi hai ("Yes. The programme is..." —
   better: "Yes — the GAU BSc programmes..."). ~15 answers me entity-lead improve ho sakta hai.
3. **Verifiable data points kam hain** — AI Overviews numbers ko cite karna pasand karta
   hai. Jahan official figures mile (16 months, 90 ECTS, 240 credits, QANs) wahan strong
   hain; fees/thresholds aane par aur strong hoga.
4. Comparison questions me **table format** nahi hai (MBA vs MSc, PhD vs DBA) — structured
   comparison tables AI extraction ke liye best format hai.

### Finding 7 — Copyright/Originality: spot-checks clean, certification pending

**Method:** 4 distinctive phrases quoted-search kiye web par:
- "convert prior learning into a full degree award" → koi exact match nahi
- "postgraduate-level capability in months, not years" → koi exact match nahi
- "Every specialisation builds the same core business foundation" → koi exact match nahi
- "two MBA awards and a regulated Level 7 diploma" → koi exact match nahi

Content synthesis-based likha gaya tha (facts sources se, sentences original), aur seeder
text Maverick ka apna hai. **Estimated similarity 5% se kaafi neeche.**

**Limitation (imandaari se):** Ye spot-check hai, certified scan nahi. Sandbox me Copyscape/
Originality.ai jaisa tool available nahi. **Phase 2 recommendation:** publish se pehle ek
baar tool-based scan (Copyscape ~$0.03/page ya Originality.ai) karke certificate le lo —
client ko dikhane ke liye bhi acha hai.

### Finding 8 — Boost-topic gaps: 12 high-impact topics abhi covered NAHI hain

Ye wo topics hain jo education FAQ me traffic + conversion boost karte hain aur abhi kisi
file me nahi hain:

| Topic | Query family | Impact |
|---|---|---|
| **How to apply (step-by-step)** | "how to apply for X" | 🔴 High — action-intent |
| **Documents required** | "documents needed for admission" | 🔴 High — application-stage |
| **Intakes & deadlines** | "admission deadline X" | 🔴 High — urgency driver |
| **Is an online degree respected by employers?** | "is online MBA taken seriously" | 🔴 High — biggest unspoken objection |
| **Degree verification process** | "how to verify X degree" | 🟠 Med — trust builder |
| **RPL / credit transfer** | "credit transfer online degree" | 🟠 Med — experienced audience |
| **Refund/withdrawal policy** | "refund policy online course" | 🟠 Med — risk-reversal |
| **Student support & mentorship detail** | "online degree student support" | 🟠 Med |
| **Sample certificate / what the degree looks like** | "X university certificate sample" | 🟠 Med — high search, low competition |
| **Time commitment per week** | "study hours online MBA" | 🟠 Med (sirf UCA me hai abhi) |
| **Alumni outcomes / success stories** | "X university reviews graduates" | 🟡 Low-Med — E-E-A-T |
| **Corporate/group enrolment** | "corporate MBA sponsorship" | 🟡 Low — B2B niche |

---

## 3. Phase 2 Plan — "FAQ 2.0"

### 3A. Fix backlog (priority order)

| Priority | Task | Effort | Files |
|---|---|---|---|
| **P0** | MSc-fees duplicate question rephrase (GAU) | 10 min | GAU |
| **P0** | Naming consistency (Qualifi OHS full names, IT tracks, RBS punctuation) | 15 min | Qualifi, RBS |
| **P1** | 8 over-length first sentences — short lead-sentence add | 1 hr | 6 files |
| **P1** | 10 thin answers enrich (fees-process detail, support detail) | 2 hrs | 4 files |
| **P1** | ~15 answers me entity-lead first sentence | 1.5 hrs | All |
| **P1** | Qualifi intra-file phrasing differentiation (hub + fees per level) | 30 min | Qualifi |
| **P2** | Comparison answers ko table format me upgrade (MBA vs MSc, PhD vs DBA, MBA vs EMBA, L7 vs master's) | 1 hr | 4 files |
| **P2** | Boost-topic FAQs add — per provider +3 se +5 questions (Section 2, Finding 8 list se; categories 5–10 cap maintain rahega ya "General/Application" naya bucket) | 4–6 hrs | All |
| **P2** | FAQPage JSON-LD generation per provider | 1 hr | New |
| **P2** | Tool-based plagiarism certificate (Copyscape/Originality.ai) | Owner action | — |

### 3B. Naye roles (team expansion recommendation)

| Role | Kyun chahiye | Kab |
|---|---|---|
| **AEO / AI-Search Specialist** | AI Overviews, ChatGPT/Perplexity citations ke liye optimize karna ab alag skill hai — schema depth, entity optimization, answer-format engineering | Phase 2 start |
| **Originality Auditor (tool-backed)** | <5% similarity ka certified proof — spot-check se aage | Publish se pehle |
| **Conversion Copywriter** | FAQ answers ke baad CTA layer ("Check your eligibility →") — abhi content purely informational hai | Publish ke saath |
| **Data Analyst (GSC loop owner)** | 30–60 din ka Search Console data → tier validation → re-prioritisation | Publish + 30 din |

### 3C. Deep research backlog (Phase 2 inputs)

1. **Partner offer sheets** — fees, English thresholds, intake calendars (isse 10 thin
   answers automatically enrich ho jayenge)
2. **Progression agreements** — Qualifi L7 Law → UOW LLM; L5 → UWS BA top-up (ladder
   content unlock)
3. **Gatehouse 3 remaining QANs** + Qualifi per-track QANs (trust-signal data points)
4. **Student-question mining** — actual admissions-team se pichle 6 mahine ke common
   questions (real student psychology data > assumptions)
5. **Competitor FAQ audit** — top 3 competitor education sites ke PAA footprints

### 3D. Sequence recommendation

```
Week 1: P0 + P1 fixes → PDFs regenerate → client re-approval
Week 2: Partner data collection (owner) + boost-topic drafting (approval-gated, per provider)
Week 3: Comparison tables + schema generation + originality certificate
Publish → Day 30-60: GSC validation → tier re-ranking → iteration
```

---

*Audit performed by the Education FAQ Specialist agent (SME + SEO + Writer + QA roles).
Programmatic checks are reproducible; plagiarism verification via quoted-search spot-checks
(4/4 clean) pending tool-based certification.*

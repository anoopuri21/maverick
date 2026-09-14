# Bachelors LP — Work Plan (Pipeline Map)
**Project:** Maverick Business Academy — Bachelors Landing Page (GCC/UAE)
**Date:** 2026-09-11 (updated 2026-09-14) · **Owner:** Project Orchestrator · **Companion:** `01-bachelors-lp-sop-v2.2.md` (current process rules; supersedes v2.1 pdf), `02-keyword-research-bachelors-gcc-uae.md` (final keywords)

---

## 1. Goal
Ship a conversion-ready Bachelors LP (BBA / BSc / BA (Hons) / Top-up routes) for UAE/GCC
prospects — UAE MoE-recognised online routes, no student visa, weekend-tolerant, AED
pricing, Sharjah office. **KPI:** qualified enquiries (form + WhatsApp), CPL, call
conversions. Primary persona: fresh 12th-pass + parent; secondary: working professional;
flank: top-up candidate.

## 2. Pipeline stages (plan → work → verify → next, with hard stop-points)

| Stage | Work | Deliverable | Gate / Stop-point | Status |
|---|---|---|---|---|
| **0 — Kickoff** | SOP + keyword research (client-facing keywords) | `01-bachelors-lp-sop.pdf`, `02-keyword-research…md` | Gate 0 + Gate 1b (every K mapped to a page location) | ✅ done |
| **1 — Plan** | This pipeline map: stages, gates, dependencies, risks | `00-work-plan.md` | Plan verified in Stage 2 | ✅ this turn |
| **2 — Research** | Market stats (sourced), 3 personas deep, competitor refresh, search behaviour, regulatory facts | `03-research-bachelors-gcc.md` | Gate 1 (research) | ✅ this turn |
| **3 — Verify (quality re-check)** | Fact-check every external claim (re-search), cross-check consistency: plan ↔ research ↔ SOP ↔ keywords ↔ listing.pdf; fix issues | `04-plan-research-verification.md` | **Verification PASS required before client pack** | ✅ this turn |
| **4 — Client Approval #1** | Plain-language page plan + keywords sent to client (no approval mechanics inside the doc); the §3 decision list stays tracked here | `05-page-plan-keywords.pdf` | **HARD STOP — pipeline halts until client signs** | → ready to send |
| **5 — Content** | Full 15-block copy per SOP §5 (both keyword tiers) + final keyword homes + client inputs (fees/durations/photos/testimonials) | `06-bachelors-landing-content-gcc-uae.md` | Gate 3 (content) + claims whitelist audit | blocked on Approval #1 |
| **6 — Client Approval #2** | Content sign-off (resolves all placeholders: [FEE], [MONTH], [Student Name]) | signed content doc | **HARD STOP** | — |
| **7 — Design plan** | Visual direction per SOP §8 + content: imagery, recognition strip spec, component map, wireframe visual | `08-visual-direction-bachelors.md` + mockup frames | Gate 5 | — |
| **8 — Client Approval #3** | Design plan sign-off | signed design doc | **HARD STOP** | — |
| **9 — Build** | Laravel route + Blade content partials (existing site nav/footer reused), Course/FAQPage/Org/Breadcrumb JSON-LD, form wiring (Zapier/Zoho), cross-links, guide articles drafted | PR with preview URL | Gate 6 (build) | — |
| **10 — QA audit** | Gate 8 checklist (structure, GCC basis, mechanical trap-list, copyright, claims, mobile, LHS ≥ 90) — PDF report, same format as masters audit | `09-qa-audit-report.pdf` | Gate 8a–d | — |
| **11 — Fix + re-audit + ship + reach rollout** | P0 fixes → re-audit → APPROVED FOR BUILD → live + GA4/GSC events → reach rollout (Google Business Profile, directory listings, guides live — SOP §10) | Live URL | Gate 8e sign-off | — |

## 3. Client dependencies (must arrive by Approval #1 or #2)
| # | Item | Needed by |
|---|---|---|
| 1 | Durations per family (BBA / BSc / BA Hons / Top-up) | Approval #2 (content) — draft writes delivery model until then |
| 2 | AED fee bands + instalment plan | Approval #2 |
| 3 | 2–3 permissioned bachelors testimonials (names, quotes) | Approval #2 |
| 4 | Sharjah office + team photos | Approval #2 (design needs by Approval #3) |
| 5 | University logo-use permissions (UWS / RBS / GAU / UoG) | Approval #3 |
| 6 | Intake calendar (rotational dates) | Approval #2 |
| 7 | Top-up awarding-body confirmation (UoG / UCLan) | Approval #2 |
| 8 | URL slug choice: `/bachelors-dubai` (preferred, K1-exact) vs `/bachelors-dubai-uae` | Approval #1 |

## 4. Risk register
| Risk | Mitigation |
|---|---|
| Workspace git resets (happened twice this project) | Every deliverable committed + pushed immediately; remote tree verified after each push |
| [FEE]/[Student Name] placeholders ship | Gate 8b grep = P0 reject; comparison table uses competitor-verified anchors only |
| Unverified stats / claims | Claims whitelist (SOP §6); every stat needs source + date; MoE/MOHRE phrasing fixed in SOP §3.1 |
| University logo misuse | Text-only names until permission confirmed (SOP §8, risk 5) |
| Keyword stuffing (masters-draft failure) | One keyword home per family; verbatim-block rule = 0 (SOP §6) |
| 12-month-mill price war | Don't compete on price — compete on recognition + accountability (SOP §4 pillars) |
| Duration contradictions (masters failure) | One duration per family, only from client-confirmed table |

## 5. Definition of done (per stage)
- **Research:** every external number has a named source + date; personas map 1:1 to keyword + programme route + SOP block.
- **Verification:** fact table 100% PASS or fixed; consistency matrix complete; issues log closed.
- **Content:** 16/16 blocks, keyword homes per 02 §4, zero placeholders, claims whitelist 100%, two-audience tone pass.
- **Design:** design-system compliant, recognition strip spec, imagery permission state noted per asset.
- **Build:** preview URL, schema valid, form events firing, LHS mobile ≥ 90.
- **QA:** Gate 8 report with P0/P1/P2 grading + sign-off line.

## 6. Current position
Stages 0–3 complete; SOP v2.2 issued and **with the client for review (2026-09-14)**.
Page plan + keywords (05) ready to follow the SOP approval. Pipeline holds at the
Approval #1 stop-point for client-facing sign-off, but internal pre-work runs in parallel
per §7 (nothing client-facing ships until approvals).

## 7. Next-steps plan (decided 2026-09-14, during client review)

**Decision: content first, design pre-work in parallel.** Design-final waits for content
sign-off. Reasons (from this project's own history):

1. The Masters LP audit showed content failures force rework; if design had gone first, the
   rework would have doubled. Content is the variable; the design system is already fixed
   (`docs/mlp-design-system.md`), so design is application, not invention.
2. Design inputs that depend on client confirmations (fee bars, testimonial portraits, intake
   dates, logo permissions) only land with content approval. Designing first means designing
   with fake data and redoing it.
3. AEO/GEO architecture (answer capsules after H2, FAQ 1:1 schema, comparison tables) decides
   which components the page needs. Content architecture must exist before component mapping
   is final.

### Track 1: content (starts now, internal drafts)
| Step | Role | Deliverable |
|---|---|---|
| C1 messaging detail | T7 | Section-level tone notes + CTA ladder, two-audience pass per block |
| C2 answer plan | T5 | Question → home → 30–60 word capsule list; schema request |
| C3 draft | T8 | `06-bachelors-landing-content-gcc-uae.md` (15 blocks, claim IDs, keyword homes) |
| C4 humanize | T9 | Naturalized draft + scorecard ≥ 9/10 |
| C5 optimize + QA | T4/T5/T10 | Gates 6–7; package held for Approval #2 |

### Track 2: design pre-work (parallel, content-independent)
| Item | What is decided now | Waits for content |
|---|---|---|
| Block-to-component map (below) | Component choice per block from the mlp motif map | Final copy lengths |
| Chart components | Pie (KHDA, catalogue mix) + fee bar, brand colours, source-line style | Client-confirmed fee bar |
| New components | Filter chips, offer rail, event rail, pathway checker (v2.2 specs) | Real offer/event data |
| Imagery tracker | Permission checklist per asset (logos, office, portraits) | Client files |
| Wireframe skeleton | 15-block order + chrome rule (existing nav/footer) | Final headings |

### Block-to-component map (pre-work, per docs/mlp-design-system.md)
| Block | Component / motif |
|---|---|
| 1 Announcement (+ offer rail) | mlp-meta strip on navy; offer line red-on-paper |
| 2 Hero | Reused cinematic-hero; 4 trust points as typographic strip with hairline dividers (no pill chips) |
| 3 Recognition strip | Typographic strip, text-only names until logo permission |
| 4 Problem | Numbered horizontal rails (fear → answer) + pie chart |
| 5 Who it's for | Three split rails (not an equal card grid) |
| 6 Programme overview | Family split chapters + catalogue-mix pie |
| 7 Programme grid | Hairline table/grid + filter chips (field, delivery) |
| 8 How it works | Drawn spine with pulsing nodes + intake calendar strip |
| 9 Recognition & careers | Editorial quote rail + numbered attestation steps |
| 10 Fees | Bar chart + instalment rows (AED) |
| 11 Comparison | Three-column hairline table |
| 12 Proof | Cinematic chapter, fixed 112x140 portraits, Previous → Now cascade |
| 13 People & office | Split with map pin + event rail card |
| 14 FAQ | Full-bleed dark accordion rows |
| 15 Final CTA | Glass enquiry panel on veil + WhatsApp deep-link |

### Sequence after client responses
- Client changes to SOP: apply, re-issue, re-verify (same-day turnaround).
- SOP approved: content pack (Approval #1: page plan + keywords) goes out with the draft
  timeline; Track 1 output moves to Approval #2.
- Content approved: final design plan (visual direction doc + mockup frames) for Approval #3,
  then build per SOP §9.


# Bachelors LP — Work Plan (Pipeline Map)
**Project:** Maverick Business Academy — Bachelors Landing Page (GCC/UAE)
**Date:** 2026-09-11 · **Owner:** Project Orchestrator · **Companion:** `01-bachelors-lp-sop.pdf` (process rules), `02-keyword-research-bachelors-gcc-uae.md` (final keywords)

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
| **4 — Client Approval #1** | SOP summary + final keywords + client decision list (fees, durations, proof assets) | `05-client-approval-pack.pdf` | **HARD STOP — pipeline halts until client signs** | → ready to send |
| **5 — Content** | Full 16-block copy per SOP §5 + final keyword homes + client inputs (fees/durations/photos/testimonials) | `06-bachelors-landing-content-gcc-uae.md` | Gate 3 (content) + claims whitelist audit | blocked on Approval #1 |
| **6 — Client Approval #2** | Content sign-off (resolves all placeholders: [FEE], [MONTH], [Student Name]) | signed content doc | **HARD STOP** | — |
| **7 — Design plan** | Visual direction per SOP §8 + content: imagery, recognition strip spec, component map, wireframe visual | `08-visual-direction-bachelors.md` + mockup frames | Gate 5 | — |
| **8 — Client Approval #3** | Design plan sign-off | signed design doc | **HARD STOP** | — |
| **9 — Build** | Laravel route + Blade partials (global-bachelors-pathway pattern), Course/FAQPage/Org/Breadcrumb JSON-LD, form wiring (Zapier/Zoho), cross-links | PR with preview URL | Gate 6 (build) | — |
| **10 — QA audit** | Gate 8 checklist (structure, GCC basis, mechanical trap-list, copyright, claims, mobile, LHS ≥ 90) — PDF report, same format as masters audit | `09-qa-audit-report.pdf` | Gate 8a–d | — |
| **11 — Fix + re-audit + ship** | P0 fixes → re-audit → APPROVED FOR BUILD → live + GA4 events | Live URL | Gate 8e sign-off | — |

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
Stages 0–3 complete this turn. **Next action = send `05-client-approval-pack.pdf` to the client.**
Pipeline is on the Approval #1 stop-point until sign-off.

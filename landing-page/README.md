# Landing Page Package — Maverick Business Academy London

Research-backed, section-wise, conversion-optimised landing page package + reusable
**Landing Page Design SOP** for all future website pages.

Built 2026-09-09 by an 11-agent pipeline: Research → Strategy → Creation →
Documentation → Review → Compilation. QA status: **approved for design handoff**
(publish gates: client fee confirmation + 4 verification items — see `10` §5).

## Read in this order

| # | File | What it is |
|---|---|---|
| 1 | `11-master-deliverable.md` | Executive summary + deliverable index + next steps |
| 2 | `06-landing-page-content.md` | **The page, section-wise, design-ready copy** |
| 3 | `05-wireframe-blueprint.md` | Section blueprint with rationale + CTA architecture |
| 4 | `09-landing-page-design-sop.md` | **Reusable SOP** — how to build any future page (open at Gate 0) |
| 5 | `03-seo-keyword-map.md` | Keywords, meta, schema, on-page checklist |
| 6 | `04-brand-messaging-framework.md` | Positioning, UVP, voice, taglines, CTA language |
| 7 | `01-audience-research.md` | Market context + 4 personas + pain→desire map |
| 8 | `02-competitor-analysis.md` | 7 competitors, fee data, gaps & opportunities |
| 9 | `07-cro-recommendations.md` | CTA/form/urgency strategy, A/B roadmap, instrumentation |
| 10 | `08-visual-direction-guide.md` | Mood, tokens, per-section imagery, motion, components |
| 11 | `10-qa-review-report.md` | Consistency matrix, compliance controls, launch blockers |

## Gulf Masters LP (GCC/UAE) — second page in the package — **ON HOLD**

> **Status (2026-09-11):** build on hold — the client's content writer is finalising the
> draft against the audit's P0 list (`content-audit-report.pdf`). Resume at Gate 3a
> (re-audit) once the revision lands.

`gulf-masters/` — Master's landing page for GCC/UAE prospects, keyword-driven:
| File | What it is |
|---|---|
| `gulf-masters/01-keyword-research-gcc-uae.md` | 6 high-demand keywords (online MBA in Dubai · MBA in UAE for working professionals · UK online MBA · online MSc Dubai · MBA in Sharjah · no-visa/relocation) + meta + schema + price anchors |
| `gulf-masters/02-masters-landing-content-gcc-uae.md` | **Full design-ready page content** built on those 6 keywords (AED-first, WhatsApp-first, 16-block spine) |
| `gulf-masters/content-audit-report.pdf` | **QA audit of the writer's draft** (`uploads/master-landing-page-content.pdf`) — 10-page report: structure, GCC-basis, SEO/keywords, word quality, copyright, compliance, CRO + P0/P1/P2 improvement plan. Verdict: revise before build (5.0/10) |

## Bachelors LP (GCC/UAE) — third page in the package

`bachelors/` — Bachelors landing page (BBA / BSc / BA (Hons) / Top-up) for GCC/UAE
prospects. Built start-to-finish from the SOP; Masters LP is **on hold** while the
client's writer revises per the audit above. Pipeline position: **at Client
Approval #1** (send `05-client-approval-pack.pdf`).

| File | What it is |
|---|---|
| `bachelors/00-work-plan.md` | Pipeline map: stages, gates, client-approval stop-points, dependencies, risk register |
| `bachelors/01-bachelors-lp-sop.pdf` | **Bachelors SOP v2.0 (GCC/UAE edition)** — 11-agent pipeline, 9 phases with gates, filled Phase-0 brief, 16-block blueprint, CRO/visual/build specs, QA gate checklist (carries the Masters-audit lessons as hard rules) |
| `bachelors/02-keyword-research-bachelors-gcc-uae.md` | **8 top-tier keywords** (K1 bachelor's degree in Dubai · K2 online bachelor's UAE · K3 BBA · K4 top-universities intercept · K5 Sharjah · K6 no-visa · K7 affordable/fees · K8 top-up) + LSI cluster, meta candidates, schema, on-page rules, price anchors |
| `bachelors/03-research-bachelors-gcc.md` | Market stats (sourced/dated), regulatory facts pack (MoE 2023 + MoHESR 2025), partner accreditation checks, competitor refresh, intent map, page implications |
| `bachelors/04-plan-research-verification.md` | **QA re-check of plan + research**: 20-fact check table, 10-point consistency matrix, issues log (2× P1 found & fixed: GAU BSc count 13→10, regulation framing → MoHESR 2025) |
| `bachelors/05-client-approval-pack.pdf` | **CLIENT-FACING approval pack #1** — what we're building, process with approval gates, the 8 keywords, 10 client decisions, timeline, sign-off block. Ready to send. |

## Prototype

`index.html` (with `assets/`) is a **static, design-ready reference build** of the
page — this content, in this visual system (navy `#071444`, red `#b20202`, warm
paper `#f5f0eb`, cinematic-editorial language per the site's MLP design system).
Open it directly or serve it: `python3 -m http.server 8080`.

> Production note: the real build should follow the site's existing convention —
> Laravel route + settings-driven section classes + Blade partials + the existing
> enquiry endpoint / Zapier / Zoho pipeline (see `11` §4).

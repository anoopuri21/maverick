# 09 — Landing Page Design SOP (Reusable Master Template)
**Agent 10 — SOP Documentation Agent**
**Project:** Maverick Business Academy — Standard Operating Procedure for building any landing/website page
**Version:** 1.0 · **Date:** 2026-09-09 · **Owner:** Project Orchestrator
**Audience:** any web designer (human or AI agent) who must ship a conversion page to this brand's standard.

---

## Purpose & Scope

This SOP converts the full research→ship process used for the **Maverick Business Academy London landing page** into a repeatable pipeline. Follow it phase-by-phase; each phase has a **gate** — do not advance until its checklist is signed. The 11-agent role map (below) is how the work is divided; a small team can fold roles together, but the *phases* are not optional.

### Role map (use these hats)
| # | Role | Owns |
|---|---|---|
| 1 | Project Orchestrator | Coordination, review, final package |
| 2 | Market & Audience Research | Personas, pains, market context |
| 3 | Competitor Analysis | Competitor matrix, gaps/opportunities |
| 4 | SEO & Keyword Research | Keyword map, meta, schema, on-page checklist |
| 5 | Brand Positioning & Messaging | UVP, pillars, tone, tagline, CTA language |
| 6 | Structure / UX | Wireframe blueprint, section rationale, CTA architecture |
| 7 | Content Strategy & Copywriting | Full section-wise copy |
| 8 | CRO | CTA/form/urgency strategy, A/B roadmap, instrumentation |
| 9 | Visual & Creative Direction | Mood, color, type, imagery, motion, component spec |
| 10 | SOP Documentation | Process capture, template maintenance |
| 11 | QA & Review | Proofing, consistency, SEO verification, compliance |

---

## PHASE 0 — Brief (input contract)

**Gate 0 checklist:** fill the brief form below *before* any research.

```
PAGE BRIEF v1
──────────────────────────────────────────────
Page purpose (one sentence, conversion-specific):
Primary conversion goal + funnel node (form endpoint / wa.me / tel: / external):
Secondary goal:  Tertiary goal:
Primary persona (pick ONE to lead; list others):
Product/service(s) the page sells (names, duration, delivery, entry, price state: public | placeholder):
Existing proof assets (testimonials w/ permission, accreditations, numbers, photos):
Competitors (3–7, with URLs):
Existing brand assets (design system doc, logo, colors, fonts, site to stay coherent with):
URL + where it lives in the sitemap; what it must link out to:
Fee/price state: CONFIRMED (list) | PLACEHOLDER (rule: never publish [FEE] placeholders)
Deadline + who signs off copy/fees/legal:
──────────────────────────────────────────────
```

---

## PHASE 1 — RESEARCH (Agents 2, 3, 4)

### 1.1 Market & audience (Agent 2)
- [ ] Market size/growth for the category + region (3–5 sourced stats; name sources)
- [ ] 3–4 personas: profile, income, goal, trigger, budget, channels, **biggest fear**, winning message
- [ ] Pain→Desire map: ≥ 8 pains, each with a *landing-page job* (which section must answer it)
- [ ] Search behaviour: high-intent queries, objection queries, comparison behaviour, local/intl rails
- [ ] Implications section: message hierarchy, tone, what the page must and must not promise
- **Gate 1a:** pains list has ≥ 8 rows; each persona has a "biggest fear"; message hierarchy is one line.

### 1.2 Competitors (Agent 3)
- [ ] Matrix of 5–7 competitors: offer vs ours · delivery · **verified fees (with source + date)** · entry bar · page strategy
- [ ] Pattern audit table: ≥ 10 conversion patterns scored per competitor (headline type, fee transparency, start dates, no-visa line, stigma handling, schema, conversational rail…)
- [ ] ≥ 6 gaps & opportunities, each phrased as something *we* can do
- [ ] Positioning one-liner + **claims discipline list** (what we may/may not claim; rankings; "accredited" vs "recognised")
- **Gate 1b:** every fee has a source; at least 2 "we are uniquely good at X" findings; claims discipline exists.

### 1.3 SEO (Agent 4)
- [ ] Keyword hierarchy: 1–3 primary · 8–12 secondary · 8–12 long-tail/objection · LSI cluster
- [ ] Intent mapped to *page sections* (not just pages)
- [ ] Meta title (≤60ch) + description (≤155ch) candidates with char counts
- [ ] JSON-LD plan: EducationalOrganization (or org type) + Course/Product + FAQPage + Breadcrumb
- [ ] On-page checklist + performance budget (LCP/CLS/JS)
- **Gate 1c:** meta candidates are length-legal; every objection long-tail has a home section or FAQ slot.

---

## PHASE 2 — STRATEGY (Agents 5, 6)

### 2.1 Messaging (Agent 5)
- [ ] Positioning statement (For / Who / is / Unlike / Because)
- [ ] **One** primary UVP + 4–5 support pillars, each with a *named proof point*
- [ ] Voice description (4 adjectives) + tone dials + Do/Don't table with *real example sentences*
- [ ] Tagline options (≥ 4) with verdict + recommended use
- [ ] CTA language system: labels for primary/secondary/conversational + ban list ("Submit", "Apply now" as first touch)
- [ ] Proof-point inventory with **exact approved phrasing** + claims discipline carried in
- **Gate 2a:** UVP is one sentence; every pillar has a verifiable proof point; no invented rankings/awards.

### 2.2 Structure (Agent 6)
- [ ] Page contract: goals, message order, layout doctrine, mobile doctrine
- [ ] Section-by-section blueprint — **each section gets:** purpose · elements · rationale · conversion role
- [ ] CTA architecture map: every CTA location → label → destination; rule: one primary action per viewport, no dead ends
- [ ] Mobile rules (sticky bar, anchor targets, tap targets)
- **Gate 2b:** section count is justified (typically 14–18 blocks incl. header/footer/announcement); every pain from 1.1 has a home; CTA map has zero dead ends.

**Section library (reuse; select & justify — default spine in bold):**
| Block | Job | When to drop |
|---|---|---|
| **Announcement bar** | Context + honest urgency | never (28px, keep) |
| **Sticky header w/ CTA** | Persistent primary action | never |
| **Hero (statement + form or CTAs)** | Capture hottest intent | downsize form to CTAs-only if A/B says so |
| **Trust bar (named recognition)** | Kill "is this real?" in 2s | never for education/health/finance |
| **Problem/pain** | Mirror pains, own them | only if audience fully self-qualified |
| **About/identity** | Who we are + scale | merge into hero for thin products |
| **Offer/programmes** | What's bought, as a *ladder or family* | never |
| **Why us (USP tiles)** | Scannable proof | if hero+about already carry proof |
| **People (founder/faculty/team)** | Human accountability | B2C-only pages may skip |
| **Success stories (before→after)** | Depth of outcome proof | if none with permission → use testimonials only |
| **Testimonials** | Breadth of proof | never for high-ticket |
| **How it works (steps + timeline)** | Reduce perceived effort | low-consideration purchases |
| **Pricing/fees** | On-page price framing | only if fees truly must stay gated (rare) |
| **FAQ** | Objection floor + schema | never |
| **Lead capture (full)** | High-intent conversion surface | never |
| **Final CTA + urgency** | Close the scroll | never |
| **Footer (contact + legal)** | Grounding, local SEO, compliance | never |

---

## PHASE 3 — CREATION (Agents 7, 8, 9)

### 3.1 Content (Agent 7)
Write **section-wise, design-ready** copy. For every section: label/eyebrow · H1/H2 · lead · body/bullets · CTA labels · microcopy. Plus: meta block, A/B seed variants for H1 + primary CTA, form copy (fields, inline errors, submit, success state, microcopy, privacy line), FAQ with 8–12 Q&As (each = a real query), footer copy.
**Voice rules:** short sentences, then evidence, then CTA. Name things (accreditations, people, routes). First-person CTAs. No brochure-speak ("world-class solutions", "passionate team"). Fees as `[FEE]` placeholders until Gate 5.
**Gate 3a:** every wireframe section has copy; every pain answered on-page; H1 ≤ 8 words or a tight two-liner; claims match 2.1 inventory verbatim; A/B seeds exist.

### 3.2 CRO (Agent 8)
- [ ] CTA placement map + label hierarchy (one primary per viewport)
- [ ] Form spec: field count ≤ 6 + optional, validation style, microcopy beats, success state with next action, endpoint (reuse existing endpoints/throttling/CRM pipeline), anti-spam without visible CAPTCHA
- [ ] Urgency mechanics — **honest only**: real intake dates, real fee-review dates; ban fake countdowns/seat counts
- [ ] Trust-signal placement map (each signal → section)
- [ ] A/B roadmap ≥ 5 tests in priority order with hypotheses + metrics + sample guidance
- [ ] **Instrumentation spec must ship with v1:** form view/start/submit/success with `section` property; CTA clicks with entry section; scroll 25/50/75/100; WhatsApp clicks
- **Gate 3b:** no urgency mechanic without a real-world mechanism; instrumentation list is a ticket, not a wish.

### 3.3 Visual (Agent 9)
- [ ] Mood line + 3–5 references (direction, not copy)
- [ ] Color tokens + usage ratios + WCAG contrast checks (state the pairs)
- [ ] Type scale (display/body/meta) + hierarchy rules
- [ ] **Per-section imagery table:** subject · mood/treatment · specs (dimensions, format, weight) · real-asset-first sourcing rule
- [ ] Motion primitives (reuse the site's; reduced-motion mandatory)
- [ ] Component spec: buttons, inputs, tables, cards, sticky bar, spacing rhythm, container/measure
- [ ] Performance budget: LCP/CLS/JS/image weights
- **Gate 3c:** imagery table covers every section (or "type-only" is stated); ban list honoured; reduced-motion path defined.

---

## PHASE 4 — DESIGN HANDOFF (Agent 6 + 9 → dev)

- [ ] Single handoff doc or annotated prototype containing: blueprint + copy (locked v1) + visual spec + tokens + component spec + motion + asset checklist (who supplies each asset, fallback for each)
- [ ] Dev checklist: route registration · layout inheritance (site header/footer) · settings/CMS fields for admin-editable copy · SEO partial (meta from DB/settings, not hard-coded) · JSON-LD block · canonical + sitemap entry · robots check · image pipeline (CDN + fallbacks) · form endpoint + throttle + CRM webhook · analytics events
- [ ] Staging URL + who reviews + feedback channel (one thread, numbered notes)
- **Gate 4:** handoff doc is self-sufficient (a new dev can build from it alone); asset table has no owner-less rows.

---

## PHASE 5 — QA (Agent 11)

Run every checklist. Sign-off is binary per item; "looks fine" is not a signature.

**Content & brand**
- [ ] No placeholder text, `[FEE]`, or lorem in production build
- [ ] Tone pass: read aloud; no banned phrases; CTA labels match 2.1
- [ ] All claims verbatim-match proof inventory; claims discipline honoured (no invented rankings; "recognised" vs "awarded" used correctly)
- [ ] Names/titles/testimonials have current written permission
- [ ] Contact details, registration number, addresses correct & consistent with live site

**SEO**
- [ ] Title ≤ 60ch, description ≤ 155ch, correct on the page (view-source, not just CMS field)
- [ ] Single H1; keyword present in title + first 100 words + ≥ 2 H2s
- [ ] Internal links 3–5 contextual; no orphan sections; all anchors resolve (incl. `#enquire`)
- [ ] JSON-LD validates (Rich Results Test); FAQ matches visible FAQ; images have descriptive alts
- [ ] Canonical present; page not noindex'd; sitemap entry

**CRO & function**
- [ ] Every CTA destination verified (form, `#enquire`, programme page, `wa.me`, `tel:`)
- [ ] Form: all fields validate, inline copy correct, success state reachable, lead lands in CRM (test lead sent + received), throttle works, spam honeypot intact, GDPR privacy line present
- [ ] Sticky bar behaviour < 768px; tap targets ≥ 48px; scroll targets land cleanly (no 20px offset drift)
- [ ] Urgency elements show *real* dates; no banned mechanics
- [ ] Analytics: fire test events in devtools for form view/start/submit/success, CTA clicks (with `section`), scroll milestones

**Visual & technical**
- [ ] Design-system ban list: no white shadowed form card on hero, no pills/eyebrow-chips, no emoji icons, no rounded-full CTAs, no clip-path gimmicks
- [ ] Contrast AA on all text pairs (spot-check dark-on-void, red-on-paper, inputs)
- [ ] `prefers-reduced-motion` respected; keyboard: tab order, focus rings, accordion operable
- [ ] Lighthouse mobile: Performance ≥ 80 (target LCP < 2.5s), A11y ≥ 95, SEO 100, BP ≥ 90
- [ ] Responsive: 360 / 768 / 1024 / 1440 — no overflow, no clipped type, hero image preloaded
- [ ] 404/500 states unaffected; existing site pages not regressed (spot-test 5 pages)
- [ ] Legal: privacy policy covers form data + WhatsApp; cookie banner consistent with site

**Gate 5 (launch):** 100% of items above signed by QA *and* client sign-off on copy + fees. **Anything unsigned = do not ship.**

---

## PHASE 6 — POST-LAUNCH (Agent 1 + 8)

- [ ] Day 1: verify live analytics events, send a real test lead end-to-end, check GA4/Zoho delivery
- [ ] Week 1: watch exit rates by section (scroll heatmap), fix any > 40% section exit before first A/B
- [ ] A/B #1 from the roadmap (start with the highest-traffic element: usually hero form vs CTAs)
- [ ] Monthly: refresh intake date (announcement bar + final CTA), review testimonial permissions, check fee state
- [ ] Quarterly: re-run competitor fee scan (fees drift), refresh one FAQ answer, re-test mobile 4G LCP
- [ ] Feed learnings back into this SOP (version it — the SOP is a living doc)

---

## Appendix A — Section Spec Sheet (fill one per section)

```
SECTION [n]: [Name]
Purpose:            (one sentence)
Message order slot: (recognition / flexibility / speed / price / other: ___)
Elements:           (list, in visual order)
Copy:               (paste locked v1)
CTA:                (label → destination)
Visual:             (surface: paper|void · imagery row from 3.3 · motion: primitive)
Conversion role:    (what it does to the funnel)
SEO:                (keyword slot(s), schema involvement)
Assets:             (each asset → owner → fallback)
```

## Appendix B — Gate Sign-off Sheet

| Gate | Owner | Signed (date) | Notes |
|---|---|---|---|
| 0 Brief | Orchestrator | | |
| 1a Audience | Research | | |
| 1b Competitors | Research | | |
| 1c SEO | SEO | | |
| 2a Messaging | Positioning | | |
| 2b Structure | UX | | |
| 3a Content | Copywriter | | |
| 3b CRO | CRO | | |
| 3c Visual | Design | | |
| 4 Handoff | Orchestrator | | |
| 5 Launch | QA + Client | | |

## Appendix C — Ban list (brand-constant, from the design system — ship none of these)
White shadowed form card on hero · pill/chip eyebrows · soft gray-blue "premium" gradients · uniform card grids everywhere · clip-path gimmicks · playful/rounded UI language · emoji icons · fake countdowns/seat scarcity · invented rankings or awards · "[FEE]" placeholders in production · CAPTCHA walls.

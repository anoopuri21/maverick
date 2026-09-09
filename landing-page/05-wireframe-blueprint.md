# 05 — Landing Page Wireframe Blueprint
**Agent 6 — Landing Page Structure / UX Agent**
**Project:** Maverick Business Academy London — Conversion Landing Page
**Date:** 2026-09-09

---

## 0. Page Contract

| Item | Decision |
|---|---|
| **Primary conversion goal** | Enquiry form completion ("Start my enquiry") |
| **Secondary goal** | WhatsApp click (`wa.me` — unique rail vs all university competitors) |
| **Tertiary goal** | Prospectus download / programme-page click (nurturing) |
| **Primary audience** | Persona A (Ambitious Operator), then C (Founder), B (Switcher), D (Global Aspirer) |
| **Message order (fixed)** | Recognition → Flexibility → Speed → Price |
| **Layout doctrine** | One column, single narrative spine; **no white SaaS card on hero** (site ban list); alternate light "paper" bands and dark "void" bands (MLP rhythm); sticky mobile action bar (WhatsApp + Apply) |
| **Mobile doctrine** | Sticky bar always visible < 768px; form reachable from every CTA via `#enquire` anchor; hero form collapses to CTA-only on mobile |

## 1. Section-by-Section Blueprint

> Format: **Name → Purpose → Elements → Rationale → Conversion role.** Copy lives in `06-landing-page-content.md`.

### §0 Pre-header announcement bar (28px, navy)
**Purpose:** Set urgency + context before the fold.
**Elements:** "Rotational intakes open 2026 · Next cohort starts in [month] · London & Dubai admissions" + right-aligned "Call / WhatsApp".
**Rationale:** Premium competitors (Henley) use time-based hooks; rotational intakes are Maverick's version — a *permanent* urgency mechanic that never expires.
**Conversion role:** Frames the whole page as time-sensitive without a fake countdown.

### §1 Sticky header (72px)
**Elements:** Logo (left) · nav anchors (Programmes, Why Maverick, Fees, Stories, FAQ) · phone · primary button "Start my enquiry".
**Rationale:** Anchors keep a long page navigable; header CTA is the #1 clicked element after the hero on long-form LPs. Mobile: logo + hamburger + compact CTA.
**Conversion role:** Constant primary CTA presence (reduces scroll-abandonment of intent).

### §2 Hero — "The Prospectus Cover" (dark, cinematic)
**Purpose:** State the outcome in ≤ 8 words, de-risk, capture intent.
**Elements:** Eyebrow (brandline), H1 (benefit-led, 2 lines with red accent line), subhead (containing primary SEO keyword), **glass enquiry panel** (name, phone/WhatsApp, email, programme-of-interest select, optional message) OR 2 CTAs on mobile, microcopy under form ("Free admissions advice · No obligation · Reply within 1 business day"), trust chips under CTAs (Ofqual-recognised · Rotational intakes · Since 2012).
**Rationale:** The form lives *in* the hero (existing MLP pattern; the user is hottest at second 10). No floating white card — glass panel on cinematic photo (site design system).
**Conversion role:** The page's main conversion event.

### §3 Trust bar — "The Recognition Strip" (light, typographic)
**Purpose:** Kill objection P4 ("online = cheap") within 2 seconds of scrolling.
**Elements:** Hairline-divided typographic strip: `Ofqual-recognised (QUALIFI)` · `University-awarded MBAs & MScs` · `IACBE · YÖK · YÖDAK partner accreditation` · `England & Wales registered` · `Delivering since 2012`. Large figures + hairlines, **not** white logo tiles (ban list).
**Rationale:** Named recognition beats generic "trusted by" (Agent 3 G7).
**Conversion role:** First trust deposit; enables the next section's pain-mirroring.

### §4 Problem / Pain section — "You're not lazy. The system is slow." (light paper)
**Purpose:** Mirror the 4 core pains in the visitor's own words; own them before competitors do.
**Elements:** Section label + H2 ("The real cost of 'when I have time'") · 4 numbered pain rails (fee of premium MBAs · 24–36 month clocks & September-only intakes · rigid entry walls · the "online degree" stigma) · closing transition line pointing at Maverick as the answer.
**Rationale:** Problem-agitation before solution (classic LP spine); numbered rails follow the approved "numbered horizontal benefit rails" motif.
**Conversion role:** Increases self-identification → attention budget for §5.

### §5 About — "What is Maverick" (dark void, cinematic)
**Purpose:** One-sentence identity + scale + credibility.
**Elements:** 3–5 statement lines (editorial reveal): who, what (Level 3→DBA ladder), where (London ⇄ Dubai), how (online/hybrid/rotational), heritage (since 2012, HALI). Stat strip: 58 programmes · 10–18 month runs · 2 continents · 2012.
**Rationale:** Answers "who are you" with the ladder story (G1), not a corporate history dump.
**Conversion role:** Establishes the academy, not just a course seller.

### §6 Programmes — "The Ladder" (light paper)
**Purpose:** Show the 3–4 tiers as a *path*, each with: name, duration, mode, entry hint, CTA "View programme".
**Elements:**
- **Rung 1 — Diplomas & Certificates** (Level 3–8, Ofqual-recognised via QUALIFI; CPD-acc. short courses; Mini MBA) — "Start here. Get recognised fast."
- **Rung 2 — Degrees** (BSc Business Management, BA programmes) — "The foundation degree, without the freshers' week."
- **Rung 3 — Masters** (Global MBA, U of Gloucestershire MBA, MBA specialisations, MSc Accounting & Finance, MSc HRM, MSc Social/Counselling Psych) — "The promotion, formalised."
- **Rung 4 — Doctorate** (DBA, PhD, EPD-EU) — "For leaders who'll be asked to teach the next cohort."
Plus **entry-routes table** (3 routes, one row each) — the single most conversion-relevant table on the page (G2).
**Rationale:** Catalogue as ladder (P8); entry table pre-empts the #1 objection query.
**Conversion role:** Secondary CTA rail (programme-page clicks); entry table converts switchers/founders.

### §7 Why Maverick — "The 6 Advantages" (light, alternating)
**Purpose:** Compress UVP pillars into scannable tiles.
**Elements:** 6 tiles (Recognition · Flexible Entry · Speed & rotational intakes · The whole ladder · London ⇄ Dubai · Human admissions) — title + 1–2 line description, hairline grid, **not** equal white cards (ban list); each tile carries one specific proof noun.
**Rationale:** Scannable proof for the 25% who never read body copy.
**Conversion role:** Re-reinforces message order (recognition→flexibility→speed→price).

### §8 Founder / Faculty — "The people who'll answer your name" (light paper, split)
**Elements:** CEO/Founder portrait + 2-line intro + signature quote; secondary strip: "Your admission is handled by a person" — admissions team card, faculty line (e.g., Saturday classes, named professor), response-time promise.
**Rationale:** Competitor pages are institutional; named humans are Maverick's warmth edge (G8).
**Conversion role:** Accountability → lowers friction before the form re-appears in §12/§14.

### §9 Success stories — "Before → After" (dark void, cinematic)
**Purpose:** Outcome proof, 2–3 case stories with structure: *Previous → Now*, one metric, one quote.
**Elements:** Portrait frames (fixed 112×140 per design system) + name, programme, "Previous: X → Now: Y", one-sentence quote. (Names: confirmed alumni from the live site; permission re-check required.)
**Rationale:** Persona A/B/C all need "someone like me did this".
**Conversion role:** Emotional proof peak — positioned just before fees (reduces price recoil).

### §10 Testimonials & social proof (light)
**Purpose:** Volume + variety proof.
**Elements:** 3–6 short testimonial blocks (named, programme-named, city/market where possible: UK, UAE, Asia), star rating if verifiable, optional video testimonial embeds (existing site pattern).
**Rationale:** Stories = depth (3 rich), testimonials = breadth (6 short). Different jobs.
**Conversion role:** Social proof saturation before the ask.

### §11 How it works — "4 steps, rotational intake" (light paper)
**Purpose:** Demystify process; answer "how long / how does it work" queries on-page.
**Elements:** 4 steps (1 Enquire & eligibility call → 2 Matched to programme + fee plan → 3 Enrol into next rotational intake → 4 Study online/hybrid with named support) + timeline strip (10–18 months typical) + "no-visa online routes" note for intls.
**Rationale:** Reduces perceived effort — the silent killer of form fills; answers objection queries (FAQPage fodder).
**Conversion role:** Lowers decision cost before pricing.

### §12 Fees & payment — "The honest price section" (light)
**Purpose:** Surface price *before* the user leaves to compare (G4).
**Elements:** Fee-band table (Diplomas / Mini MBA / Degrees / MBA / MSc / DBA — **placeholder bands until client confirms**), "or from £X/month" anchor once confirmed, what's included (materials, support, recognition), payment-plan line, CTA "Get my fee plan" → form.
**Rationale:** Aston's £875/month line proves payment-flexibility language converts; hiding fees in this market loses the click to a comparison site.
**Conversion role:** Qualifies + accelerates (price-shock moved on-page where it can be reframed).

### §13 FAQ — "The objection floor" (dark, full-bleed accordion)
**Purpose:** On-page objection handling + FAQPage schema.
**Elements:** 8–10 Q&As from the LSI list: recognition by employers? entry without a 2:1? duration? dissertation? work alongside? intakes? fees/instalments? international recognition/no-visa? diploma→degree progression? support model?
**Rationale:** Each question is a real search query; accordion keeps the page tight (existing MLP pattern: full-bleed dark rows).
**Conversion role:** Kills last objections adjacent to the form.

### §14 Lead capture — "The enquiry" (light, glass panel on soft band)
**Purpose:** The second, fuller conversion surface (hero form is the fast path).
**Elements:** H2 "Talk to admissions — a person, not a portal", form (same fields as hero + programme select), trust bullets beside form (reply ≤ 1 business day · no cost to enquire · your details never shared), WhatsApp alternative CTA, phone fallback.
**Rationale:** People who read 14 sections convert at higher rates when the ask is *re-presented in context*.
**Conversion role:** Highest-intent form on the page.

### §15 Final CTA — "The close" (dark void)
**Purpose:** One last, singular ask with urgency.
**Elements:** H2 "Your next intake is already in motion." · subline (rotational intake + next start month) · primary button "Reserve my place" (→ §14) · secondary "WhatsApp admissions" · phone line.
**Rationale:** End on motion, not logo. Existing MLP ends on void CTA — keep site coherence.
**Conversion role:** Captures the scroll-through majority.

### §16 Footer — "The grounding" (darkest navy)
**Elements:** Logo + masterline ("Transforming learners into global leaders.") · quick links (Programmes, Accreditations, Student success, News, Contact) · **London address (Ruislip) + phone + email + WhatsApp** · Dubai office line · socials · legal (Privacy, Terms, England & Wales registration number) · "Regulated qualifications: Ofqual / QUALIFI" line.
**Rationale:** Local address in footer is both E-E-A-T and local-SEO; legal + registration number = compliance trust.
**Conversion role:** Tertiary contact rail; credibility close.

## 2. CTA Architecture (map)

| Location | CTA | Target |
|---|---|---|
| Announcement bar | Call / WhatsApp | `tel:` / `wa.me` |
| Header (persistent) | Start my enquiry | `#enquire` (§14) |
| Hero form | Submit (form) | POST `/enquire` |
| Hero (mobile) | Start my enquiry / WhatsApp | `#enquire` / `wa.me` |
| Programmes (per rung) | View programme | `/programs/{slug}` |
| Fees | Get my fee plan | `#enquire` |
| §14 form | Submit | POST `/enquire` |
| Final | Reserve my place / WhatsApp | `#enquire` / `wa.me` |
| Sticky mobile bar (persistent) | WhatsApp / Apply Now | `wa.me` / `#enquire` |

**Rule:** one primary action per viewport; every CTA lands on the same funnel node (`#enquire` or a programme page) — no dead ends.

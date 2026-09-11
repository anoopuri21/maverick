# 07 — CRO Recommendations
**Agent 8 — Conversion Rate Optimization Agent**
**Project:** Maverick Business Academy London — Conversion Landing Page
**Date:** 2026-09-09

---

## 1. CTA Placement Strategy

| Principle | Implementation |
|---|---|
| **Hottest intent = first 10 seconds** | Form *inside* the hero (§2), not a button that scrolls. Desktop: split layout (statement left, glass form right). Mobile: statement + full-width CTA + WhatsApp; form at `#enquire`. |
| **Persistent primary rail** | Header button "Start my enquiry" → `#enquire` (all viewports ≥ 1024px); sticky mobile bar (WhatsApp + Apply Now) at all < 768px — matches the production MLP pattern users already know. |
| **Contextual re-asks** | After §6 (entry table → "Check my eligibility"), §12 (fees → "Get my fee plan"), §15 (final → "Reserve my place"). Each re-ask uses *different* copy for the same funnel node — no copy fatigue, same destination. |
| **Conversational rail (differentiator)** | WhatsApp is a *first-class* CTA, not a floating gimmick: hero secondary, fee section, form alternative, final CTA, sticky bar. University competitors don't have it; this is where Persona D and price-sensitive UK buyers convert. |
| **No dead ends** | Every CTA lands on `#enquire`, a programme page, or `wa.me`/`tel:`. Programme-page CTAs must link *back* to the LP form (retargeting loop). |

**Label hierarchy (fixed):** Primary = form submission / "Start my enquiry" · Secondary = "WhatsApp admissions" / "Download prospectus" · Never "Submit", "Apply now" (first touch), or "Contact us".

## 2. Form Optimization

| Decision | Spec | Rationale |
|---|---|---|
| Field count | 5 fields + 1 optional (name, phone/WhatsApp, email, programme select, preferred-contact) | Each removed field raises completion; 5 is the floor for sales-qualified leads. "Preferred contact" prevents the classic "we called, they use WhatsApp" waste. |
| Phone field | Single "Phone / WhatsApp" field, accepts `+` and UK `0` formats, validated loosely client-side | UK & GCC users both live on WhatsApp; one field removes format anxiety. |
| Select option "Not sure yet" | Present, and *valuable* (routes to advisor call, not auto-drop) | Persona B arrives without knowing the answer; a wall of programme names kills them. |
| Validation | Inline, on-blur, human copy ("That email doesn't look complete") — no red walls | Form-error rage is the #1 abandonment cause after field count. |
| Submit microcopy | "Free admissions advice · No obligation · Reply within 1 business day · Details never shared" (one line, 4 beats) | Answer the 4 trust questions before the click. |
| Success state | Confirmation + WhatsApp deep-link + expected response time + interim content (prospectus link) | The lead is hot for 10 minutes; hand them the next step *inside* the page. |
| Privacy line | One sentence + link to policy, never a legal wall | GDPR-required, CRO-hostile if oversized. |
| Endpoint | Same pattern as production: POST to `/…/enquire` with `throttle:5,1`, Zapier/Zoho delivery (already built in codebase) | Zero new plumbing; leads land in the existing CRM flow. |
| Anti-spam | Honeypot + throttling (existing) — no visible CAPTCHA unless abuse appears | CAPTCHA costs 5–10% completion; throttle:5,1 + honeypot is enough for LP volumes. |

## 3. Urgency & Scarcity (ethical mechanics only)

| Mechanic | Use | Why it's honest |
|---|---|---|
| **Rotational-intake clock** | Announcement bar + final CTA: "Next cohort starts in [MONTH]" (driven by a real intake date, updated monthly) | Real mechanic — intakes genuinely roll; never a fake countdown. |
| **Cohort-size honesty** | Only if true: "Cohorts are capped so faculty can name every student" | Verify with client; if unprovable, delete the claim (QA gate). |
| **Fee-plan timing** | "Current fee plan runs to [DATE]" only if the client confirms a real fee-review date | No manufactured deadlines. |
| **Never** | Fake countdown timers, "3 seats left", stock-bait | Damages the trust product is selling; also ASA exposure. |

## 4. Trust Signals (placement map)

| Signal | Where | Form |
|---|---|---|
| Ofqual/QUALIFI, IACBE/YÖK/YÖDAK, UoG | §3 strip + FAQ #1 + footer | Typographic, named, verifiable |
| England & Wales registration + address | Footer + form area (small) | "Registered company no. [X] · Ruislip, London" |
| Named humans (founder, faculty, "named advisor") | §8, §14, form microcopy | Faces > logos |
| Alumni names + programmes | §9/§10 | Specific, verifiable |
| Response-time promise ("1 business day") | Form microcopy + §14 + sticky bar tooltip | Operational promise — set the backend to honour it |
| WhatsApp "live" cue | Sticky bar | "Typically replies same day" |

## 5. A/B Test Roadmap (priority order)

| # | Element | H0 | H1 | Success metric | Min. sample guidance |
|---|---|---|---|---|---|
| 1 | Hero form vs hero CTAs-only (desktop) | Split form in hero | Statement + 2 CTAs, form below | Enquiry completion rate | ~500 sessions/variant for 20% lift detection |
| 2 | H1 (copy seeds) | A: recognition-led | B: freedom-led | Scroll-depth + form CTR | Same |
| 3 | CTA label | "Start my enquiry" | "Book my free admissions call" | Form CTR | Faster read (high CTR event) |
| 4 | Fees visibility | Band table | "From £X/month" anchor | Form CTR from §12, exit rate | Confirm fee with client first |
| 5 | Final CTA label | "Reserve my place" | "Start my enquiry" | Form submissions from §15 | — |
| 6 | WhatsApp prominence | Standard secondary | Hero + sticky only | WhatsApp click rate | — |

**Instrumentation (must ship with v1):**
- Enquiry form: view / start (first field focus) / submit / success — events with `section` property (hero vs §14)
- WhatsApp: click + entry section
- Programme CTAs: click + slug
- Scroll milestones: 25/50/75/100% + section dwell
- GA4 (or equivalent) + server-side event to Zoho (existing pipeline) — attribute lead → section → variant

## 6. Mobile-Specific CRO Rules

1. Sticky bar ≤ 768px: WhatsApp (ghost) + Apply Now (solid red) — glass navy, not chunky blocks (design system).
2. Hero form never blocks the headline on mobile: statement → CTAs → chips; form via `#enquire`.
3. Tap targets ≥ 48px; select fields use native `<select>` (mobile keyboard + OS list beats custom dropdowns).
4. LCP < 2.5s: preload hero image `fetchpriority=high`, WebP/AVIF, no render-blocking JS (all LP JS `defer`, one file).
5. Test with 4G + 250ms latency throttle on a mid-range Android — the Persona D baseline.

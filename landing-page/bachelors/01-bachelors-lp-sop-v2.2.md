# Bachelors Landing Page: Standard Operating Procedure
**GCC / UAE edition, v2.2 · 2026-09-14 · Owner: Project Orchestrator · Maverick Business Academy (London) · UAE office: Robot Park Tower, Sharjah**
**Companions:** `02-keyword-research-bachelors-gcc-uae.md` · `uploads/listing.pdf` · `docs/mlp-design-system.md`
**Supersedes:** v2.1 (`01-bachelors-lp-sop.pdf`). Read this file as the current SOP.

## READ FIRST: project status and v2.2 changes

Scope: this SOP governs the full research-to-ship pipeline for the UAE/GCC Bachelors landing
page (BBA, BSc, BA (Hons), top-up routes). The Masters LP stays on hold while the client's
writer revises per the audit in `landing-page/gulf-masters/content-audit-report.pdf`.

Changes in v2.2: an offer rail and an event rail join the blueprint; a nurture sequence is
specced for the enquiry flow; the programme grid gets browse filters; entry-route lines and a
top-up pathway checker join the build spec; the progression ladder now uses catalogue counts;
QA gains four new checks. Everything else from v2.1 carries over unchanged.

## 1. Purpose and role map

Each phase has a GATE. Do not advance until its checklist is signed. The 11-role map is
unchanged; small teams may fold roles, but the phases are not optional.

| # | Role | Owns (bachelors context) |
|---|---|---|
| 1 | Project Orchestrator | Coordination, gate sign-off, final package |
| 2 | Market & Audience Research | 3 personas, pains, GCC market context |
| 3 | Competitor Analysis | Campus premium vs agents vs fast-track mills, price-anchored matrix |
| 4 | SEO & Keyword Research | Two-tier keyword map, meta, schema, on-page checklist |
| 5 | Brand Positioning & Messaging | UVP, pillars, two-audience tone, CTA language |
| 6 | Structure / UX | 15-block blueprint, CTA architecture |
| 7 | Content Strategy & Copywriting | Section-wise copy, keyword homes, claims whitelist |
| 8 | CRO | Form, WhatsApp rail, nurture, A/B roadmap |
| 9 | Visual & Creative Direction | Design-system compliance, imagery direction |
| 10 | SOP Documentation | Process capture, template maintenance |
| 11 | QA & Review | Gate 8 checklist: mechanical, copyright, claims, structure, mobile |

Site chrome rule: the page reuses the website's existing navigation bar and footer. The
blueprint covers the content area only (15 blocks).

## 2. PHASE 0: brief (input contract)

Gate 0: the brief is filled for this project. Any blank is resolved before research starts.
Fee state, offers and entry criteria are client-owned blanks; everything else is internal.

```
PAGE BRIEF v2 (Bachelors, GCC/UAE)
Page purpose: convert UAE/GCC prospects into qualified bachelors enquiries
(form + WhatsApp) for UK-awarded and partner-university undergraduate routes.
Primary conversion: hero 5-field form, counsellor call within 1 business day.
Secondary: WhatsApp click. Tertiary: fee-plan PDF download.
Personas: P1 fresh 12th-pass + parent (lead) · P2 working professional · P3 top-up candidate.
Products: BBA (RBS, 7) · BSc (GAU, 10) · BA (Hons) Global Business (UWS) ·
top-up (QUALIFI L5/L7 to UK honours; awarding body client-confirmed).
Durations: PLACEHOLDER until client confirms. Fees: PLACEHOLDER.
Offers / scholarships rail: PLACEHOLDER. Fill only with client-confirmed offers.
Entry routes per family ("who can apply"): PLACEHOLDER, client-confirmed.
Proof: listing.pdf · Sharjah office · London HQ since 2012 · [testimonials: NEEDED].
URL: /bachelors-dubai preferred (client confirms). Deadline: T+10 working days to QA.
Sign-off: Orchestrator + client (fees, claims, offers, entry criteria).
```

## 3. PHASE 1: research (Agents 2, 3, 4)

Market frame (sourced, dated): 57,035 new UAE HE students in 2024-25 (+13%, decade high);
Dubai private HE +20%, international +29% to 35%; Indians about 42% of Dubai's international
intake; business and economics the most-pursued field; campus band AED 35k-65k/yr; 400+ listed
bachelors programmes in the UAE. Supply is deep; trust is scarce. Full fact pack with sources:
`03-research-bachelors-gcc.md`.

Regulatory spine: the MoHESR 10 March 2025 conditional-recognition policy for online degrees
(primary fact, always sourced and dated), MoE since 2023 as background, MOHRE private-sector
practice, attestation route. Locked phrasings live in the claims whitelist; never "visa
guaranteed", never "recognised for all purposes".

Catalogue precision (new in v2.2, class-a facts from listing.pdf): the progression ladder is
now stated with real counts: 12 MBA specialisations and 9 MSc at Rushford, 6 MBAs, 16 EMBAs,
4 thesis MScs at GAU, plus Ofqual Level 7 diplomas. QUALIFI Level 5 spans 14 specialisations
(including Law, IT, Cyber Security), so the top-up rail speaks to non-business diplomas too.
Programme names appear verbatim from the catalogue (BBA "Hospitality & Tourism Management",
not a shortened form).

SEO (Agent 4): deliverable `02-keyword-research-bachelors-gcc-uae.md` (Tier 1 K1-K8, Tier 2
R1-R8, LSI cluster, meta, schema, on-page rules, price anchors). Gate 1b: every keyword maps
to a home before copy starts.

## 4. PHASE 2: positioning and messaging (Agent 5)

UVP: "A real undergraduate degree, from named UK and partner universities, that fits a Gulf
life: no student visa, weekend-tolerant delivery, AED pricing with instalments, and a human
admissions team in Sharjah who knows attestation, MOHESR and your career move."

Five pillars, in page order: (1) recognition, (2) no visa / stay put, (3) flexible by design,
(4) affordable and honest, (5) local accountability. New in v2.2: pillar 1 and the progression
FAQ carry the catalogue ladder counts from §3 as proof.

Two-audience tone rule: every major section passes both ears. The student hears ambition and
flexibility; the parent hears recognition, safety and cost control. No slang, no hype, no
unquantified "world-class".

## 5. PHASE 3: the 15-block blueprint (Agent 6)

Order is conversion logic: trust before fees, recognition before claims, proof before the ask.
Blocks 2-3 mandatory after the hero. v2.2 additions are marked NEW.

| # | Block | Content spec | CTA |
|---|---|---|---|
| 1 | Announcement bar | Next intake [MONTH, client date] + "No student visa needed for online routes". NEW: offer rail slot: one client-confirmed offer line when available; empty otherwise. | none |
| 2 | Hero | H1 benefit-led (K1 lands in subhead); 4 trust chips: UK-awarded, no student visa, weekend-tolerant, office in Sharjah; benefit sub for both audiences. NEW: source-matched subline variants enter the A/B roadmap (parent-led vs student-led). | Form + WhatsApp |
| 3 | Recognition strip | Named universities + reg. bodies + MoHESR 2025 policy + London HQ + Sharjah office + since 2012 | none |
| 4 | Problem section | Parent fears + student fears, fear-to-answer pairs + demand pie (KHDA) | none |
| 5 | Who it's for | 3 persona cards routing to real routes | Card CTA |
| 6 | Programme overview | Family-level: BBA (RBS, 7), BSc (GAU, 10), BA (Hons) (UWS), top-up. NEW: progression line with catalogue counts; top-up card gains the pathway checker (NEW, §9). One duration per family. | none |
| 7 | Programme grid | Full catalogue names with in-demand flags. NEW: filter chips by field and by delivery (build spec §9). | Enquire |
| 8 | How it works | 4 steps + intake calendar + 1-business-day call promise. NEW: "who can apply" entry-route line per family, client-confirmed table only. | Form |
| 9 | Recognition & careers | MoHESR/MOHRE/attestation explained honestly; careers per family (roles, not salaries) | none |
| 10 | Fees & funding | AED + instalments (client figures); comparison table vs campus and vs 12-month mills. NEW: offer rail detail slot, client-confirmed only. | Fee plan |
| 11 | Comparison table | Flexible online vs campus vs 12-month fast-track, six rows | none |
| 12 | Proof | 2-3 named permissioned bachelors stories + verified stats + office photos | none |
| 13 | People & office | Team names + photos, Robot Park Tower map pin, London HQ, since 2012, GCC city strip. NEW: event rail: monthly "meet your counsellor" session at the Sharjah office, booked by WhatsApp. | WhatsApp |
| 14 | FAQ | 10 Q&As 1:1 with FAQPage schema; progression FAQ carries ladder counts | none |
| 15 | Final CTA + form | 5 fields; 4-beat microcopy; success state with WhatsApp deep-link + fee-plan download. NEW: success state triggers the nurture sequence (§7). | Form |

## 6. PHASE 4: content and copy (Agent 7)

Hard gates (carried from v2.1): one keyword home per phrase per tier; no verbatim sentence
template across sections; one duration per family from the client table; claims whitelist only
(client catalogue, public sourced facts with dates, client-verified stats); placeholders loud
and draft-only; UK spellings; bachelor's (noun) vs bachelors (attributive); Dubai, Sharjah,
Abu Dhabi each named in H2s or first 100 words; GCC at least twice; AED in fees; INR once,
parenthetical, dated; mechanical pass before review (the "programmed" trap list).

New in v2.2:
- Offer copy rule: an offer line states only what the client confirmed, with its terms and
  its end date. No invented discounts, no percentage headlines without a signed offer.
- Entry-route copy rule: "who can apply" lines come only from the client-confirmed table;
  until then the draft describes the audience, not the criteria.
- Review copy rule: ratings or review quotes appear only with a real, checkable source
  (platform + date). Nothing manufactured, nothing aggregated by hand.
- Nurture copy: day-3 recognition PDF and day-7 parent FAQ use the same whitelist and tone
  rules as the page. They are deliverables of this pipeline, not ad-hoc notes.

Deliverable format unchanged: markdown source with 15 numbered sections, each carrying keyword
homes, claim IDs, CTA and placeholder tags. That format is what QA audits block-by-block.

## 7. PHASE 5: CRO (Agent 8)

Form: 5 fields max (name, phone/WhatsApp, email, programme, country). First-touch CTA
"Start my enquiry" / "Get my programme & fee plan"; never "Apply Now" pre-call. NEW:
"Request a call" micro-CTA joins the A/B seeds.

WhatsApp rail: sticky mobile bar + hero secondary + 3 mid-page mentions + final CTA link.

Nurture sequence (NEW, Zapier-driven on the existing pipeline):
| Step | When | What |
|---|---|---|
| WhatsApp acknowledgement | within minutes of submit | received + what happens next |
| Counsellor call | within 1 business day | the promise, kept |
| Recognition explainer PDF | day 3 | sourced 2023/2025 facts, parent-safe |
| Parent FAQ note | day 7 | the 5 parent questions, plain answers |
| Intake reminder | at intake-calendar change | real dates only |

Urgency: real intake dates only. No fake countdowns.

A/B seeds (post-launch): H1 benefit vs K1-exact · 5-field vs 4-field · form-first vs
WhatsApp-first hero · parent-led vs student-led subline · "Start my enquiry" vs "Request a
call". Instrumentation: GA4 events form_start, form_submit, wa_click, tel_click,
pdf_download, plus nurture-step events; UTM pass-through from ads.

## 8. PHASE 6: visual and creative (Agent 9)

Design system `docs/mlp-design-system.md` is the single source. Cinematic-hero component
reused from /global-bachelors-pathway; restyle, do not reinvent. Existing nav + footer stay.

Imagery: Gulf student life, real office photos (client supplies), partner campus shots only
with permission. Recognition strip: text-only names until logo permission lands.

NEW design tokens for the v2.2 additions: filter chips use the meta-label style with hairline
borders (no pill cliché); offer rail uses the announcement bar pattern with red text on paper;
event rail uses the people-block card style with a real date and a WhatsApp booking link.
Motion per `docs/mlp-scroll-reveal-fix.md`; Lighthouse mobile 90 or above is a gate.

## 9. PHASE 7: build (integration)

Stack: Laravel 11 + Blade, same pattern as /global-bachelors-pathway; route + Filament-managed
SEO fields; partials in resources/views/pages/bachelors-dubai/. Route: /bachelors-dubai
(client confirms).

Schema (JSON-LD): Course per family; FAQPage 10 Q&As 1:1; EducationalOrganization with Sharjah
+ London addresses and areaServed; BreadcrumbList.

NEW build items:
- Programme grid filters: client-side filter by field and by delivery on the block 7 grid;
  no page reload; filtered state does not change the canonical URL.
- Pathway checker: on the top-up card, a 3-choice control (diploma / HND / credits) that shows
  the matching route line and links to the counsellor call. Pure front-end, catalogue-sourced
  answers only.
- Nurture hooks: form submit posts to the existing admissions endpoint; Zapier fires the
  sequence in §7. No new backend.
- Event rail: one settings-driven block (date + WhatsApp link), admin-editable per month.

Cross-links: /programs/{slug} per family; /global-bachelors-pathway ("prefer to relocate?");
/online-mba-masters-uae once un-holded; /pathway-programs and site footer link in.
Assets via Cloudinary, 200KB webp or under, lazy below the fold.

## 10. REACH AND DISTRIBUTION

On-page search foundation, local SEO (GBP at Robot Park Tower, one master NAP sheet),
directory profiles (educations.com, coursetakers.ae, bachelorsportal + 2-3 comparables),
guide articles ("Bachelors in UAE: a complete guide", "Is an online bachelor's degree valid
in the UAE?", "Campus vs online vs 12-month bachelors"), social and paid post-launch,
weekly one-page measurement. All carried from v2.1.

NEW in v2.2:
- Event rail distribution: the monthly counsellor session gets a GBP post, a WhatsApp
  broadcast and one guide-article mention. One session per month, real dates only.
- Review loop (post-launch): permission-based requests for Google Reviews after successful
  enrolments; directory profiles collect their own reviews; the page pulls a rating line only
  once a real, checkable score exists (§6 review copy rule).
- Nurture measurement: ack-to-call time, call-to-enrolment rate, PDF open rate; reviewed in
  the weekly summary.

## 11. PHASE 8: QA gates (Agent 11)

Gate 8a structure & GCC basis: 15/15 blocks in order; recognition strip at 3; hero and
programme overview UAE/GCC-based; persona cards route to real catalogue programmes;
NEW: entry-route lines match the client-confirmed table or are absent.

Gate 8b mechanical: the v2.1 trap list (grep "programmed", placeholder sweep, duration audit,
verbatim-repeat check, copyright spot-checks). NEW: offer-rail grep: no percentage or discount
claim on the page unless the claims map shows a client-confirmed offer ID.

Gate 8c claims & compliance: whitelist 100%; regulation phrasing exact; no logo without
permission; two-audience tone pass signed. NEW: every rating or review quote carries platform
+ date in the claims map; nurture PDF and parent note audited with the same rules.

Gate 8d technical: form 5 fields validated; WhatsApp/tel links live; mobile 360px pass;
Lighthouse mobile 90 or above; meta within limits; JSON-LD valid. NEW: filters and pathway
checker tested on mobile; nurture events firing in staging; event rail renders from settings.

Gate 8e sign-off: APPROVED FOR BUILD, then live + GSC/GA4 + reach rollout.

**Sign-off chain:** Orchestrator, client (fees, claims, offers, entry criteria), QA re-audit.

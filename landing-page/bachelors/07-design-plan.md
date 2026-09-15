# Bachelors LP: HTML Prototype Design Plan
**Companion to `07-lp-prototype.html` (open it, or the live preview) · 2026-09-15**
**Source of truth for tokens and motifs:** `docs/mlp-design-system.md` · **Copy source:** `06-bachelors-landing-content-gcc-uae.md`
**Type:** PP Neue Montreal (display; prototype substitutes Archivo) + Poppins (body) · **Palette:** navy #071444, blue #0f2983, red #b20202, paper #f5f0eb · **Corners:** 0–2px · **Buttons:** min 48px, sharp.

## Rhythm
Void and paper chapters alternate (hero void → recognition paper → problem paper-soft → steps void → fees soft → proof void → FAQ void → final void). Ghost index numerals (01–04) anchor the long chapters. Every motion follows the mlp primitives: reveal (opacity + y, 0.6–1.0s, power3-style ease, once), count-up, subtle parallax/Ken Burns; reduced-motion shows everything instantly.

## Section-by-section design decisions

| # | Block | Surface | Design-system motif used | Creative element in the prototype |
|---|---|---|---|---|
| 1 | Top bar | pure | announcement pattern | Pulsing red "live" dot, hairline border, single factual line |
| 2 | Hero | void | cinematic photo plate + monumental H1 | Ken Burns slow-pan plate under a navy veil; H1 rises line-by-line through masked rows; trust strip as typographic hairline dividers (no pill chips); enquiry as frosted glass panel with underline inputs (banned white card avoided) |
| 3 | Recognition strip | pure | logo marquee (p5) | Text "logos" with accreditation sub-labels in an infinite marquee, pause on hover, edge fades; dated MoHESR policy as editorial quote rail with red rule |
| 4 | Problem | paper | numbered horizontal rails (p3 bars) | Fear-quote left, answer right, red index numerals; animated SVG pie 35/65 draws on scroll with oversized centre numeral |
| 5 | Audience | soft | split rails, not equal cards | Three editorial rows with outlined index numerals; hover expands the row and shifts padding; each row carries its own red CTA |
| 6 | Routes | pure | big campus plate + numbered catalog | Family chapters with red meta tags and accreditation lines; programme names as hairline-underlined list items with red "in demand" micro-tags; pathway checker as sharp tab control with fading answer panes; progression ladder as count-up typographic strip |
| 7 | Grid | paper | typographic data (p4 charts) | Live filter chips (sharp meta-label style, hairline borders) filtering an 18-row hairline table; red top rule on the header; row hover lifts to white |
| 8 | Steps | void | drawn spine with pulsing nodes (p4 career) | SVG spine draws itself on scroll; four pulsing nodes; "who can apply" as a hairline 3-cell grid |
| 9 | Recognition explained | pure | editorial quote rail + sticky split | Sticky left chapter title + red-ruled promise; right column plain-words explainer; attestation as a 3-cell Verify → Apostille → Stamp strip; careers as scene rows |
| 10 | Fees | soft | typographic bar data (p4 charts) | Three bars grow on scroll (campus / Dubai school / fast-track), source line under the axis; positioning statement as an oversized display quote with red accent |
| 11 | Compare | pure | comparison table | Maverick column lifted on paper with red top rule and soft depth shadow; other columns stay flat |
| 12 | Proof | void | typographic stat strip (p3) | Count-up oversized numerals with hairline dividers (57,035 · 29% · 400+ · 14); story/photo slots shown as dashed 112×140 frames until permissions land |
| 13 | Office | paper | diagonal media plane + people block | Office plate with hover zoom and pulsing map pin labelled "Robot Park Tower, Sharjah"; monthly event rail as red date-block card; GCC city marquee strip |
| 14 | FAQ | void | full-bleed dark accordion rows (p1) | Hairline accordion rows, rotating plus mark, one open by default, 0.6s ease |
| 15 | Final CTA | void | glass panel on veil | Four-beat reassurance list with red indices; glass form; success state swaps in with a fade (WhatsApp + fee plan next steps) |
| — | Chrome | — | existing nav/footer reuse | Slim blurred navy nav with red CTA; sticky mobile glass bar with WhatsApp + enquiry |

## Ban-list compliance (checked in the prototype)
No white hero form card (glass instead) · no equal white stat tiles (typographic strips) · no pill/chip eyebrows (meta labels + sharp chips) · no soft gray-blue gradients (navy veil + radial blue only) · no uniform card grids (split rails, chapters, rows) · no rounded-full (radius ≤ 2px) · no emoji · no clip-path gimmicks.

## Motion inventory
mlpReveal on every chapter (staggered children on strips/stats/beats) · mlpCount on ladder + proof stats · Ken Burns hero parallax · marquee ×2 · spine draw · pie draw · bar grow · accordion ease · checker pane fade · reduced-motion disables all and shows content.

## Assets
`prototype-assets/hero-plate.jpg`, `prototype-assets/office-plate.jpg` (generated stand-ins; replaced by client's real office/team photos and permissioned portraits at build).

## Next after approval
Build into Laravel Blade per SOP §9 using the same tokens; partials in `resources/views/pages/bachelors-dubai/`; charts as inline SVG; filters and pathway checker as vanilla JS; GA4 events wired per SOP §7.

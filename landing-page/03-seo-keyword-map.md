# 03 — SEO Keyword Map & Recommendations
**Agent 4 — SEO & Keyword Research Agent**
**Project:** Maverick Business Academy London — Conversion Landing Page
**Date:** 2026-09-09

> Volume figures are directional estimates for UK commercial intent (verified against live SERP composition, not a paid keyword tool). Re-run in Google Keyword Planner / Ahrefs before media spend; **structure** (intent → section) is the durable output.

---

## 1. Keyword Hierarchy

### Primary (page-level target — 1 per page)
| Keyword | Intent | Est. UK demand | Placement |
|---|---|---|---|
| `online mba uk` / `online mba london` | Commercial (high) | High | H1 support, meta title, first 100 words, pricing section |
| `business courses london` | Commercial | Medium-High | About/Programs, footer, H2 support |
| `flexible mba uk` | Commercial | Medium | "How it works", delivery messaging |

### Secondary (section-level)
| Keyword | Intent | Target section |
|---|---|---|
| `part time mba london` | Commercial | Programs (MBA card), FAQ |
| `mba without entry requirements uk` | Commercial-objection | Entry-routes table (Programs) + FAQ |
| `mba for working professionals` | Commercial | Hero subhead, Why section |
| `entrepreneurship course london` | Commercial | Programs (Diploma/Mini MBA cards) |
| `business management diploma uk` / `level 7 diploma management` | Commercial | Programs ladder |
| `msc accounting and finance online uk` / `msc human resource management` | Commercial | Programs (MSc) |
| `dba uk part time` / `doctor of business administration uk` | Commercial-low volume, high value | Programs (Doctorate card) |
| `uk accredited business qualifications` | Commercial-objection | Trust bar, FAQ |
| `study mba in london without moving to uk` / `online mba no visa required` | Commercial (int'l) | Global footprint element, FAQ |

### Long-tail / objection (FAQ + on-page copy — feeds FAQPage schema)
- `how long does an online mba take`
- `online mba without dissertation`
- `can i study an mba while working full time`
- `is an online mba recognised by employers`
- `mba entry requirements for experience holders`
- `difference between mba and masters in management`
- `level 3 to level 7 business diploma progression`
- `affordable mba uk`
- `rotational intake mba`
- `mini mba for business owners`

### LSI / semantic cluster
`Ofqual`, `QUALIFI`, `blended learning`, `hybrid learning`, `accredited partner university`, `IACBE`, `YÖK`, `rotational intake`, `180 credits`, `dissertation`, `case study assessment`, `career progression`, `leadership skills`, `strategic management`, `entrepreneurship`, `small business`, `London SME`, `Dubai / UAE office`, `England and Wales registered company`

---

## 2. Meta Recommendations

**URL:** `/` (homepage) or new route `/online-mba-london` if a dedicated LP is added (recommended for paid + organic separation).

**Meta title candidates (≤ 60 chars, front-loaded):**
1. `Online MBA London | Flexible, UK-Recognised | Maverick` (54) ✅ **recommended**
2. `Maverick Business Academy London | Online MBA & Courses` (55)
3. `Flexible Online MBA in London from £/mo | Maverick` (use once fee confirmed)

**Meta description candidates (≤ 155 chars):**
1. "Accredited online MBA, MSc & Level 3–8 business diplomas in London. Rotational intakes, flexible entry, 10–18 month programmes. Talk to admissions today." (152) ✅
2. "Earn a UK-recognised MBA or business diploma without pausing your career. London-based, online & hybrid, experience-based entry routes. Free admissions call."

**Open Graph / social:** OG title = candidate 1; OG description = candidate 1; OG image 1200×630, navy ground, headline + red CTA button (see Visual Guide).

---

## 3. Structured Data (JSON-LD) — ship all three

```json
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "Maverick Business Academy London",
  "alternateName": "MBA London",
  "description": "London-based business academy offering UK-recognised diplomas, university-awarded MBA and MSc programmes, and doctorates via partner universities.",
  "address": { "@type": "PostalAddress", "addressLocality": "Ruislip, London", "addressCountry": "GB" },
  "sameAs": ["[linkedin]", "[instagram]", "[facebook]", "[youtube]"]
}
```
- **Course** schema per featured programme (name, provider, description, credit, duration, offers with `price` only after client confirms fee — Bayes already publishes price in schema; once fees are public, do it).
- **FAQPage** schema from the live FAQ block (the existing `/online-mba-masters-uae` page already ships this — reuse the pattern).
- **BreadcrumbList** for sub-pages.

> Note: Google deprecated rich-result display for most self-serving FAQPage on commercial pages, but it remains valid schema and supports discovery in other engines; keep it.

---

## 4. On-Page SEO Checklist

- [ ] Single H1 containing primary keyword variant (`Online MBA in London` or benefit line — see QA note on H1 vs conversion headline)
- [ ] Primary keyword in first 100 words of body copy (not the hero headline if headline is benefit-led — put it in subhead)
- [ ] H2s use natural keyword variants (`Flexible Entry`, `Programmes`, `Fees & Payment Plans`, `Is an Online MBA Recognised?`)
- [ ] Internal links: 3–5 contextual links to programme pages (`/programs/...`), accreditations, student success, contact
- [ ] Image alt text = descriptive (`maverick-student-online-lecture-london`, never "image1.jpg")
- [ ] Page weight target: LCP < 2.5s (hero image preloaded `fetchpriority=high`, AVIF/WebP, CLS budget 0 — existing MLP already implements preload + `cached_asset` versioning; follow it)
- [ ] Canonical self-reference; no noindex on LP
- [ ] robots.txt + sitemap entry for new route
- [ ] `hreflang` only if GCC/Asia localised pages are added later (don't ship half-measures)

## 5. Content Flywheel (post-launch)

| Cluster | Formats | Feeds |
|---|---|---|
| "Online MBA in London" | Comparison pages: *Maverick vs university online MBA fees*; *What an online MBA costs in the UK (2026)* | Primary LP |
| Entry routes | *Can you get an MBA without a first degree? UK routes explained* | Persona B traffic |
| Founders | *Mini MBA vs full MBA for business owners*; *What a Level 7 diploma opens up* | Persona C traffic |
| International | *Studying a UK business degree from the UAE: visa, recognition, cost* | Persona D traffic |

Each post ends with a contextual CTA to the LP enquire form (Zapier/Zoho pipeline already exists in the codebase — wire LP form to the same `enquire` endpoint pattern with `throttle:5,1`).

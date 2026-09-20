# Program Detail Page Content

Master index for programme-page content. Organized as university folder → program category → program files.

```
program-detail/
└── rushford-business-school/
    ├── mba/      12 files · 01-11 are 16-month specialization MBAs (90 ECTS, capstone)
    │             12 is the accelerated general MBA (12 months, 60 ECTS, no capstone)
    └── msc/      09 files · all 12 months, 60 ECTS, 15 courses, asynchronous e-Campus
                  (90 and 120 ECTS versions noted on each page)
```

Structure per file follows the live demo design (hero, glance, overview, why, learning, careers, structure, support, GCC, fees) plus internal BUILD NOTES. Excluded sections on every page: recognition, accreditation, university block, success stories, reviews, network, faculty voice, FAQ, enquire, CTA. Voice per `landing-page/ai-team/prompts/content-writer-agent-prompt.md` + G02 §8.

## rushford-business-school/mba

| # | File | Programme | Status |
|---|------|-----------|--------|
| 01 | mba/01-mba-in-logistics-supply-chain-management.md | MBA in Logistics & Supply Chain Management | DONE · v5 |
| 02 | mba/02-mba-in-sustainability-energy-and-environment.md | MBA in Sustainability, Energy and Environment | DONE |
| 03 | mba/03-mba-in-strategic-management.md | MBA in Strategic Management | DONE |
| 04 | mba/04-mba-in-real-estate-management.md | MBA in Real Estate Management | DONE |
| 05 | mba/05-mba-in-human-resource-management.md | MBA in Human Resource Management | DONE |
| 06 | mba/06-mba-in-marketing.md | MBA in Marketing | DONE |
| 07 | mba/07-mba-in-healthcare-leadership.md | MBA in Healthcare Leadership | DONE |
| 08 | mba/08-mba-in-hospitality-and-tourism-management.md | MBA in Hospitality & Tourism Management | DONE |
| 09 | mba/09-mba-in-health-economics.md | MBA in Health Economics | DONE |
| 10 | mba/10-mba-in-entrepreneurship-and-innovation.md | MBA in Entrepreneurship and Innovation | DONE |
| 11 | mba/11-mba-in-finance.md | MBA in Finance | DONE |
| 12 | mba/12-master-of-business-administration-accelerated.md | Master of Business Administration (accelerated) | DONE |

## rushford-business-school/msc

| # | File | Programme | Status |
|---|------|-----------|--------|
| 01 | msc/01-msc-in-sustainability-and-environmental-management.md | MSc in Sustainability and Environmental Management | DONE · v5 |
| 02 | msc/02-msc-in-strategic-management.md | MSc in Strategic Management | DONE |
| 03 | msc/03-msc-in-operations-and-supply-chain-management.md | MSc in Operations and Supply Chain Management | DONE |
| 04 | msc/04-msc-in-international-business-management.md | MSc in International Business Management | DONE |
| 05 | msc/05-msc-in-marketing.md | MSc in Marketing | DONE |
| 06 | msc/06-msc-in-entrepreneurship-and-innovation.md | MSc in Entrepreneurship & Innovation | DONE |
| 07 | msc/07-msc-in-finance-and-investment.md | MSc in Finance and Investment | DONE |
| 08 | msc/08-msc-in-economics.md | MSc in Economics | DONE |
| 09 | msc/09-msc-in-business-management.md | MSc in Business Management | DONE |

## Detector note (2026-09-20)

Client detector testing returned 60%+ AI on v4 content. Response: v5 maximally-human pattern applied to `mba/01` and `msc/01` first as validation samples (larger sentence-length variance, parenthetical asides, broken parallelism, uneven card lengths, one honest-take marker per page, no repeated stock lines across files). If the client's detector confirms a low score on these two, the pattern rolls across the remaining 19 files.

## Per-file sign-off list (recurring)
- Exact fee per programme and intake
- September 2026 seat confirmation
- Scholarship rules
- Specialization module wording where the official listing is descriptive rather than titled
- Market figures if legal prefers softer language

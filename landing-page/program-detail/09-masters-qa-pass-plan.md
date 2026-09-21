# 09 · Masters Programme Pages · 7-Point QA Pass Plan

**Date:** 2026-09-21 · **Scope:** 50 masters md files + 50 docx companions (Rushford MBA 12, Rushford MSc 9, GAU MBA 6, GAU EMBA 16, GAU MSc 4, UCA 1, Wolverhampton 1, UWS 1). Bachelor files excluded from this pass.

---

## Verification findings

### Checks 1 to 5 (grammar, sentences, spelling, capitalisation, phrases)
- Em/en dashes: 0 across all 50 bodies.
- Banned G02 jargon: 1 hit only, `Navigate the policy frameworks` in GAU EMBA 05 learning outcomes. Fix: `Work through the policy frameworks`.
- Straight quotes: 1 file, GAU MBA 04 (one quoted sentence). Fix: convert to curly quotes.
- Title-case heading suspects: 0. Sentence-case rule holds.
- **Spelling consistency:** 43 files mix US forms (`programmes`, `organisation`, `specialisation`) with the UK register the pages are written in (`programme`, `centre`, `analyse`, `labour`). Fix: normalise to UK English in body copy.
- No lowercase sentence starts, no doubled words, no missing spaces found.

### Check 7 (only official-website proof and claims)
Every numbered market claim was audited. Classification:

| Claim family | Source type | Decision |
|---|---|---|
| Rushford/GAU/UWS/UCA/Wolverhampton programme facts (duration, credits, modules) | Official university pages | KEEP |
| EduQua, IACBE, QS Stars, YÖDAK/YÖK, Ofqual, QAA | Official accreditation bodies | KEEP |
| UAE net-zero AED 600B through 2050, 50,000 green jobs by 2030 | UAE government | KEEP |
| UAE bank assets above AED 4.1 trillion (2024) | UAE Central Bank data | KEEP |
| Dubai tourism: 19.59M visitors, 154,264 rooms/827 establishments, 80.7% occupancy, AED 579 ADR, AED 460 RevPAR | DET (Dubai government) | KEEP |
| MOHRE Emiratisation rules, national health insurance mandate Jan 2025 | UAE government | KEEP |
| Non-oil sector over 74% of GDP | Abu Dhabi Investment Office / UAE government | KEEP |
| UAE foreign trade AED 3 trillion, AED 4 trillion 2031 target, CEPA growth | UAE leadership announcements / Dubai Media Office | KEEP |
| UAE corporate tax 9%, Federal Decree-Law No. 47 of 2022 | UAE law | KEEP |
| Creative Dubai: AED 21.9B, 4.6% GDP, 175,000 jobs, 47,000 enterprises | Dubai Media Office tracker | KEEP |
| AED 16,000 to 40,000 fee band | Client-approved masters page | KEEP |
| Unicorn 30 programme (named initiative only) | Dubai official initiative | KEEP |
| Taggd "nine in ten employers" skills-gap stat | Private recruitment firm | REWRITE as qualitative demand statement |
| Mordor consulting USD 2.55B to 3.57B | Private research firm | REWRITE qualitative |
| Healthcare market USD 3.4B to 6.63B (MarkNtel/Mordor) | Private research firm | REWRITE qualitative |
| Digital advertising USD 2.29B to 5.35B (MarkNtel) | Private research firm | REWRITE qualitative |
| Logistics USD 57.6B to 96B (Mordor) | Private research firm | REWRITE qualitative |
| Health insurance USD 9.27B (Mordor) | Private research firm | REWRITE onto the official mandate fact |
| Labeeb banking review + AED 18,000 to 28,000 salary bands | Private data firm | REMOVE salary numbers, keep qualitative |
| Logistics Middle East mega-project review | Private trade publication | REWRITE qualitative (mega-project fact kept without attribution) |
| Startup funding USD 2B/218 deals/MENA 7.5B/Dubai 93% | Private venture-data firm | REWRITE qualitative |

### Check 6 (humanize style)
All 50 files are already in the v5 maximally-human pattern. This pass preserves rhythm while rewriting the claim sentences above, and re-runs the full banned-pattern sweep afterwards.

## Fix sequence

1. UK spelling normalisation (43 files).
2. Straight-quote fix (GAU MBA 04), banned-phrase fix (GAU EMBA 05).
3. Claim rewrites per the table above, sentence by sentence, in both body cards and GCC sections.
4. Update each touched file's header `Sources on file` line and append a dated BUILD NOTES line recording the removals.
5. Re-run the full sweep (dashes, jargon, frames, private-source markers, US spellings).
6. Regenerate all 50 DOCX with `md_to_docx.py`, re-verify DOCX text.
7. Commit and push to `arena/01a0a675-maverick`.

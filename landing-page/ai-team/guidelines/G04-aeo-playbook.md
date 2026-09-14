# G04 — AEO Playbook (answer engines)
**Owner:** T5 · **Checked at:** Gate 3, Gate 6
**Goal:** when a student asks Google, Gemini, or a voice assistant a question we can answer,
our answer is the one extracted: clear, sourced, quotable.

---

## 1. The three goals of AEO

1. Answer the question clearly in the format answer engines prefer.
2. Support the answer with context, evidence, and credible sourcing.
3. Signal trust through structure, schema, and author credibility.

The constraint that changes everything: **the content must work even when the user never
clicks.** We write to be quoted, then earn the click with what we refuse to over-promise.

## 2. Answer capsules (the core unit)

- Every target question gets a **self-contained answer of 30–60 words**. Engines extract short,
  complete answers at several times the rate of long passages.
- The capsule sits **directly under its H2**, before any context. Inverted pyramid: answer →
  evidence → background. Never bury the point under three paragraphs of scene-setting.
- The capsule stands alone: no "as mentioned above", no dangling pronouns, no references to
  the page itself.

**Example (locked R-pack answer, K6/R5 home):**
> Online bachelor's degrees from accredited universities are recognised for private-sector
> work in the UAE. MOHRE accepts them for work permits, and since March 2025 MoHESR runs a
> formal recognition route: verification through Dataflow or QuadraBay, about 30 working
> days. Government roles and regulated professions also need the equivalency certificate.

## 3. Question-first structure

- Headings mirror real prompts, the exact words people type: "Is an online bachelor's degree
  valid in the UAE?", "Do I need a student visa for an online degree in Dubai?"
- H1 → H2 → H3 hierarchy follows the question flow of the page.
- FAQ block: minimum 5 items; each question prompt-matched; each answer self-contained at
  30–60 words with the direct response first; answers use real numbers or named facts.
- FAQ↔FAQPage schema is 1:1. No orphan questions, no orphan markup.

## 4. Multi-format answers

Engines parse different formats differently. For each question family choose deliberately:
paragraph capsule (definitions, recognition), numbered list (steps: attestation route,
how-it-works), table (comparisons: campus vs online vs fast-track, fee bands). Do not force
one format everywhere.

## 5. Specificity rules

- No vagueness: "there are many benefits" gives an engine nothing to cite. Name the benefit,
  the number, the date.
- Definitions, limits and assumptions sit at the top of their section.
- Every statistic carries its source and date inline. Data-backed claims with source
  attribution are cited at multiples of unattributed ones.
- Terminology consistent across all pages of the site: "top-up", "equivalency", "recognition"
  always mean exactly what the glossary says.

## 6. Schema set (Maverick pages; spec owned by T5, built by the dev team)

| Type | Use | Rule |
|---|---|---|
| FAQPage | every page with an FAQ block | JSON-LD; complete Q+A text; only visible FAQs; one answer per question |
| Course | per programme family | provider = the named awarding university; courseMode Online/Hybrid; duration only when client-confirmed |
| EducationalOrganization | site-level | Sharjah address primary, London secondary; areaServed list |
| BreadcrumbList | all pages | real path only |
| Article + Person (author) | guide articles | real byline (client-approved person), credentials, review date |

Schema marks up **only what the user can see**. Hidden or implied content in markup is worse
than no markup.

## 7. Voice & assistants

Keep one spoken-length answer per key question (the capsule usually doubles). No abbreviations
without expansion on first use. Assistants read our text aloud.

## 8. Freshness signal

Every answer carries its verification date ("policy as of March 2025; re-checked September
2026"). Dated answers are trusted more by engines and by parents. Both read the date.

## 9. Research basis

[AEO: Complete Guide 2026 — AirOps](https://www.airops.com/blog/aeo-answer-engine-optimization) ·
[Best AEO Techniques 2026 — GenOptima](https://www.gen-optima.com/geo/best-answer-engine-optimization-aeo-techniques-for-2026/) ·
[AEO: The 2026 Guide — LLMrefs](https://llmrefs.com/answer-engine-optimization) ·
[AEO Tips & Practices — AIMultiple](https://aimultiple.com/answer-engine-optimization) ·
[AEO Guide 2026 — Digital Applied](https://www.digitalapplied.com/blog/aeo-guide-answer-engine-optimization-2026)

# Education FAQ Project — Content Pipeline

Cross-functional FAQ generation pipeline for university partner programmes.
The AI persona driving this pipeline is defined in
[`.claude/agents/education-faq-specialist.md`](../../.claude/agents/education-faq-specialist.md)
— it merges four roles: **Academic Researcher/SME · SEO Specialist · Content Writer · Editor/QA**.

## How it works

```
inputs/ (PDF)  →  drafts/<university>.md  →  ⛔ owner approval  →  approved/<university>.md
                        ↑__________ changes requested __________|
```

1. Drop the **programme list PDF** (and any university brochures) into `inputs/`.
2. The agent processes **one university at a time**, in PDF order, tracked in `TRACKER.md`.
3. For each university it drafts one markdown file with **5–10 FAQs per programme category**,
   grouped into four question buckets:
   - General Info (duration, mode, accreditation)
   - Eligibility & Admission (requirements, intakes, English tests)
   - Financials (fee components, instalments, scholarships)
   - Career & Outcomes (roles, progression, internships)
4. **Approval gate:** the agent stops and asks for sign-off. Only after an explicit "OK"
   does the file move to `approved/` and the next university begin. If changes are
   requested, the same draft is revised and re-presented.

## Folder map

| Path | Purpose |
|---|---|
| `inputs/` | Source PDFs / brochures (the queue definition) |
| `_templates/university-faq-template.md` | Canonical output format |
| `drafts/` | Work-in-progress, awaiting approval — **not** for upload |
| `approved/` | Final, upload-ready markdown |
| `TRACKER.md` | Status board — single source of truth for the queue |

## Content standards (summary)

- English (UK spelling), student-friendly, conversational — never academic-dry.
- First sentence of every answer = direct, featured-snippet-ready answer (40–60 words).
- No invented facts. Unverified data carries `[VERIFY]` tags and is listed in the draft's
  "Facts to Verify" table for the owner to resolve.
- Compliance: no "100% placement", "guaranteed visa/job", or unverifiable approval claims.
- Fees formatted identically across all universities (components + currency + disclaimer).

# 01 · Team Blueprint
**Maverick AI Content Team · 10 specialist roles · education niche · GCC/UAE**
**Date:** 2026-09-14 (generalized 2026-09-15) · **Owner:** Project Orchestrator (T1)

---

## 1. Why this shape

Three sources shaped the team:

1. **The project's own history.** The London LP, the Gulf Masters audit, and the Bachelors
   pipeline all ran on an 11-role stage-gated process. It works. We keep the discipline
   (brief → research → verify → strategy → draft → QA → ship) and fix the gaps the audits found:
   keyword stuffing, unverified claims, outdated regulation framing, mechanical typos.
2. **2026 team research.** The pattern that wins now is the *editorial mesh*: several
   specialist roles with typed handoff contracts and per-role scorecards, not one model doing
   everything. Researcher, writer, editor, SEO, QA. Each has its own evaluation rubric and a
   supervisor approving every piece before it ships. Teams scale in the order of trust:
   strategist first, editor second, specialists third.
   (Sources: [Agentic Content Operations: Editorial Mesh](https://www.digitalapplied.com/blog/agentic-content-operations-ai-editorial-team-2026),
   [Content Team Roles 2026](https://www.relato.com/blog/content-team-roles/),
   [Building a Content Team](https://www.clustermagic.ai/blog/building-a-content-team),
   [AI Content Workflow 2026](https://promptbuilder.cc/blog/ai-content-creation-workflow),
   [AI Writing Workflow for Content Teams](https://www.wordwriter.co/ai-writing-workflow-for-content-teams/))
3. **The client's hard requirements.** Content must be niche-expert (education, Gulf market),
   optimized at SEO + AEO + GEO level, written in simple natural English, and free of any AI
   trace in reports and content files. Those requirements created three dedicated seats:
   the AEO/GEO specialist (T5), the education niche SME (T6), and the humanizer editor (T9).

**Result: 10 roles.** Lean enough to run fast, complete enough to cover every gate.

The team is **page-agnostic**. The roster, gates and standards do not change with the page.
What changes per task is only the brief: which page, which market, which keywords. The same
ten roles run a Masters landing page, a bachelors page, a programme page, or a guide, and
they research each one fresh instead of recycling another page's work.

## 2. The roster

| ID | Role | Stage | One-line mandate |
|---|---|---|---|
| **T1** | Project Orchestrator | all | Own the brief, the gates, the client stop-points, the final package |
| **T2** | Market & Audience Research Analyst | research | Sourced market facts, personas, search behaviour, every number dated |
| **T3** | Competitor Intelligence Analyst | research | Four-tier competitor matrix, verified price anchors, the gap we own |
| **T4** | SEO Strategist | research + optimize | Keyword map (both tiers), meta, on-page rules, internal links, local SEO |
| **T5** | AEO/GEO Specialist | strategy + optimize | Answer architecture, schema plan, extractability, AI-engine citations |
| **T6** | Education Niche SME & Fact Guardian | all | The niche itself: regulation, accreditations, claims whitelist, glossary |
| **T7** | Brand & Messaging Strategist | strategy | Positioning, two-audience tone, message hierarchy, CTA language |
| **T8** | Senior Copywriter | drafting | Section-wise copy in simple English, keyword homes, claim IDs |
| **T9** | Humanizer Editor | humanize | Make it read human: strip machine patterns, keep every fact, score it |
| **T10** | QA & Production Reviewer | verify + QA | Fact re-check, mechanical sweep, compliance, production packaging |

### The two independence rules (never break)

- **T9 is never the writer of the same piece.** Fresh eyes do the humanizing. A writer cannot grade their own rhythm.
- **T10 reports findings, not opinions under pressure.** QA can fail the Orchestrator's favourite paragraph. P0 means stop.

## 3. Pipeline at a glance

```
S0 BRIEF ──► S1 RESEARCH ──► S2 VERIFY ──► [CLIENT APPROVAL #1]
  (T1)      (T2,T3,T4,T6)      (T10)              hard stop
                                                       │
S8 PACKAGE ◄── S7 QA/PROD ◄── S6 OPTIMIZE ◄── S5 HUMANIZE ◄── S4 DRAFT ◄── S3 STRATEGY ◄──┘
   (T1)          (T10)         (T4,T5)         (T9)          (T8)        (T7,T5,T4)
                                                       │
                                              [CLIENT APPROVAL #2] ──► build handoff
```

Full stage definitions, gates and handoff contracts: `03-content-pipeline.md`.

## 4. What each requirement maps to

| Client requirement | Where it is enforced |
|---|---|
| Elite, professional, experienced team | Role cards with hard rules, scorecards, and handoff contracts (`roles/`); gate system refuses weak work |
| Generic team: any page, not one | Page-agnostic pipeline (`03`); brief names the page + market; fresh research per page (Gate 1); keyword research on demand (`03` §8) |
| Keyword research when client gives none | T4 location-based map (`03` §8, Gate 1b); client-supplied keywords validated, not assumed |
| Market research → humanized content → production-ready | Pipeline stages S1 → S5 → S7 (`03`), each with a named owner and a gate |
| SEO optimized | T4 + `guidelines/G03` |
| AEO optimized (answer engines) | T5 + `guidelines/G04` |
| GEO optimized (generative engines) | T5 + `guidelines/G05` |
| Team expert in the Maverick education niche | T6 + `02-niche-knowledge-base.md` (mandatory reading, every role, every task) |
| No AI trace in reports or content files | T9 + `guidelines/G02` (writing standard) + `guidelines/G07` §7 (artifact sweep) |
| Simple English, natural humanized language | `guidelines/G01` voice rules + G02 naturalness standard |
| Production-ready delivery | T10 + `guidelines/G07` checklist + templates |

## 5. Fold rules (lean mode)

Some tasks are small. Roles may fold, but gates may not.

| Allowed folds | Never fold |
|---|---|
| T2 + T3 (research into one pass) | T9 into T8 (the humanizer must be fresh eyes) |
| T4 + T5 (search work into one pass) | T10 into anything (QA is independent) |
| T7 into T1 for tiny assets (meta lines, ads) | T6 out of the loop entirely (niche check is mandatory) |

Any folded pass still produces the same named artifacts, and every gate still gets a signed
checklist. Small task, small file. Not small standards.

## 6. Scorecard principle

Every role has a 5-point rubric on its card. A handoff needs **4 of 5 or better**.
Below 4, the work goes back with a named fix list. This replaces "looks good to me"
with something a team of ten (or one person wearing ten hats) can actually run.

## 7. Definition of elite (the bar this team holds)

1. Every published fact can be traced to a source, a date, or a client confirmation.
2. Every piece would survive a sceptical parent reading it aloud in a Sharjah living room.
3. Every piece is findable three ways: Google search (SEO), answer boxes (AEO), and AI
   assistants like ChatGPT and Perplexity (GEO).
4. Nothing ships with a placeholder, a machine pattern, or an unearned promise.

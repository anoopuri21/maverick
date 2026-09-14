# G02 — Humanization & Naturalness Protocol
**Owner:** T9 · **Enforced at:** Gate 5 (every deliverable, including reports and client packs)
**The standard:** every file we ship must read as if a senior education professional with ten
years in the Gulf wrote it: simple, concrete, unhurried, specific. No AI trace, ever.

---

## 1. How we meet the standard (the honest way)

We do not "trick detectors". Detectors chase yesterday's tells. We hold a writing standard
that human readers and machines both respect:

1. **Substance first.** Every paragraph carries a fact, a name, a number, or an instruction.
   Fluent-but-empty prose is the loudest machine tell there is.
2. **Specificity.** Real universities, real buildings, real dates, real prices. Machines
   generalise; experts name things.
3. **Uneven rhythm.** Sentence lengths vary on purpose: a five-word decision after a
   thirty-word proof. Monotone cadence is a fingerprint; burstiness is human.
4. **A point of view.** We take positions ("a 12-month bachelor's carries a recognition risk;
   here is the source") instead of hovering above the topic.
5. **Spoken grammar.** Contractions where speech uses them. A sentence may start with And or
   But when the turn deserves it. Fragments land a CTA.
6. **Honest experience.** We only claim what is true: the academy's real history since 2012,
   the real office, client-permissioned student stories. Never fabricated first-person
   anecdotes. "Humanized" means human-quality, never pretend-human.

## 2. The banned-pattern list (grep targets — zero tolerance in publish text)

**Banned words/phrases** (rewrite on sight):
delve · tapestry · landscape (of education) · leverage (verb) · robust · seamless · unlock ·
unleash · harness · foster · elevate · realm · testament to · underscore(s) · pivotal ·
navigate the complexities · embrace (innovation/the future) · game-changer · cutting-edge ·
state-of-the-art · in today's fast-paced world · in the ever-evolving world · it's not just
about X, it's about Y · whether you're a student or a professional · it is important to note ·
it is worth mentioning · it should be noted · in conclusion · in summary · furthermore ·
moreover (≤ 1 per piece) · additionally (≤ 1 per piece) · empowering students · shaping the
future · a journey of · at the heart of · when it comes to (≤ 1 per piece).

**Banned structures:**
- Summary-then-restate openings ("In this article we will explore…") and mirror conclusions.
- Every list being a trio. Rule of three: max once per section.
- Identical sentence skeletons in consecutive paragraphs (same length, same connector, same
  verb position).
- Bullet lists where prose belongs; every bullet = "Bold phrase: generic explanation".
- Rhetorical-question-then-answer more than once per piece.
- Hedge stacking: two or more of *important to note / worth mentioning / that said* in one
  section is a fail.

**Density limits:**
- Em dashes in prose: ≤ 5 per 1,000 words, and never two doing different jobs in one
  sentence. Exempt (structural, not prose): the document-title separator (the dash after the
  file number), gate/section labels, citation titles, and empty table-cell markers.
  When in doubt, use a comma, a colon, or a full stop.
- Exclamation marks: 0 (except inside a quoted real testimonial).
- Bold inside running prose: only for terms a parent must not miss, max 3 per 500 words.

## 3. The positive craft checklist (what T9 actually does)

- Replace abstract nouns with the concrete thing they hide.
- Break any sentence over ~30 words unless it genuinely needs the length.
- Kill connectors: most *moreovers* and *furthermores* become full stops.
- Give each paragraph a different shape: one is two lines, one is five, one is a table.
- Keep one metaphor per piece at most; usually zero. Facts do the work here.
- Read it aloud. Rewrite whatever makes you stumble.

## 4. Machine sweep commands (T10 runs these; output is kept)

```bash
# banned words (case-insensitive) — expect zero matches in publish text
grep -rniE "delve|tapestry|seamless|robust|unlock|unleash|harness|foster|elevate|realm|testament|pivotal|game-changer|cutting-edge|state-of-the-art|moreover|furthermore|in conclusion|in summary|it is important to note|worth mentioning|empower" {file}

# em-dash density
grep -o "—" {file} | wc -l     # divide by word count; must be ≤ 5 per 1,000 words

# verbatim sentence repeat across sections (sorted duplicate lines ≥ 40 chars)
grep -hE "^.{40,}" {file} | sort | uniq -d

# placeholder sweep
grep -rniE "\[fee\]|\[month\]|\[student name\]|\[add|\[tbd\]" {file}
```

Zero hits required for Gate 5 / Gate 7. Any hit = fix, re-run, attach the clean output.

## 5. The 10-point naturalness scorecard (≥ 9 to pass)

| # | Check | Pass condition |
|---|---|---|
| 1 | Banned-pattern grep | 0 hits (§4 command) |
| 2 | Rhythm | sentence lengths visibly varied; no 3 same-shape paragraphs in a row |
| 3 | Concrete density | every paragraph has fact/name/number/instruction |
| 4 | Connectors | no repeated connector family; hedge stacks gone |
| 5 | Rule of three | ≤ 1 trio per section |
| 6 | Em-dash density | within limit |
| 7 | Openings/closings | no summary-then-restate; no mirror endings |
| 8 | Spoken grammar | contractions and turns present where speech would use them |
| 9 | Read-aloud test | no stumbles (T9 attests) |
| 10 | Counsellor-desk test | could be said, unchanged, to a family in Sharjah (T9 attests) |

## 6. Scope — this protocol covers every artifact

Landing page copy, guide articles, reports, verification documents, client packs, emails,
WhatsApp scripts. Internal pipeline files (like this one) are held to the same writing
standard. We practice what we police. The only artifact-type differences: technical specs may
keep longer sentences; scorecards may stay in table form.

## 7. Research basis

- Machine tells and their thresholds: em-dash density, burstiness < 0.4, rule-of-three,
  hedge stacking, buzzword clusters:
  [Top 10 Signs of AI-Generated Text](https://www.explainx.ai/blog/top-10-signs-of-ai-generated-text-2026),
  [Signs of AI Writing — 12 Patterns with Thresholds](https://slopdetector.org/blog/signs-of-ai-writing),
  [The Telltale Signs of AI-Generated Content](https://www.ailocthinktank.com/post/the-telltale-signs-of-ai-generated-content),
  [27 Red Flags of AI Writing](https://vrid.ai/blog/signs-of-ai-writing),
  [Is This Copy Human?](https://www.laurenperna.com/laurens-latest-news/how-to-tell-if-content-is-ai-generated).
- The constructive standard (specificity, first-hand substance, E-E-A-T) beats detector
  cat-and-mouse:
  [Google E-E-A-T Guide 2026](https://linkbuilder.com/blog/google-eeat-guide),
  [YMYL Content Guidelines 2026](https://koanthic.com/en/ymyl-content-guidelines-complete-guide-for-2026/).

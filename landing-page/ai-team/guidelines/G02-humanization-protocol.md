# G02 · Humanization & Naturalness Protocol
**Owner:** T9 · **Enforced at:** Gate 5 (every deliverable, including reports and client packs)
**The standard:** every file we ship must read as if a senior education professional with ten
years in the Gulf wrote it: simple, concrete, unhurried, specific. No AI trace, ever.

The promise is absolute, not a density target. A reader, a client, or a detector should find
no fingerprint of machine generation in anything we deliver. When a pattern even *looks* like
a machine wrote it, it goes.

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

## 2. The banned-pattern list (grep targets, zero tolerance in publish text)

### 2a. Banned words (rewrite on sight)
delve · tapestry · leverage (verb) · robust · seamless · seamlessly · unlock · unleash ·
harness · foster · elevate · realm · testament · underscore · pivotal · embark · journey
(as a marketing noun) · transformative · revolutionize · revolutionary · groundbreaking ·
game-changer · cutting-edge · state-of-the-art · holistic · synergy · synergies · paradigm ·
streamline · empower · empowering · unparalleled · unrivalled · second-to-none · boasts ·
myriad · plethora · navigate (the landscape/complexities) · embrace (the future/innovation) ·
user-friendly · best-in-class · world-class.

### 2b. Banned phrases (rewrite on sight)
in today's fast-paced world · in the ever-evolving world · the ever-changing landscape ·
the landscape of education · navigate the complexities · it's not just about X, it's about Y ·
not only X but also Y · whether you're a student or a professional · it is important to note ·
it is worth mentioning · it is worth noting · it should be noted · it is essential to ·
it goes without saying · needless to say · at the end of the day · the bottom line ·
in a nutshell · in conclusion · in summary · in the world of · in a world where ·
gone are the days · take it to the next level · unlock the power of · look no further ·
without further ado · here's the thing · the good news is · the best part · but that's not all ·
and that's not all · shaping the future · empowering students · a journey of · at the heart of ·
when it comes to · plays a crucial role · stands as a · serves as a · a wide range of ·
designed to help you · tailored to your needs · let's explore · let's dive in.

Frequency caps where a phrase is not banned outright: moreover, additionally, furthermore
(max one of the three per piece, together).

### 2c. Banned structures
- Summary-then-restate openings ("In this article we will explore…") and mirror conclusions.
- Every list being a trio. Rule of three: max once per section.
- Identical sentence skeletons in consecutive paragraphs (same length, same connector, same
  verb position).
- Identical paragraph shape repeated three or more times in a row (all two-liners, all
  five-liners). Vary the block.
- Bullet lists where prose belongs; every bullet built as "Bold phrase: generic explanation".
- Rhetorical-question-then-answer more than once per piece.
- Hedge stacking: two or more of *important to note / worth mentioning / that said* in one
  section is a fail.
- Predictable skeleton: intro, three parallel points, neat conclusion. Real pieces change
  shape as they go.
- Every section ending on a punchy one-line closer. Land some sections on a fact instead.
- Parallel heading sets where each heading repeats the same grammatical formula.

### 2d. Banned punctuation and formatting (the hard zero list)
- **Em dash (—): zero.** Not one, anywhere in publish text, in prose or in headings. The em
  dash is the single most-recognised machine fingerprint. Replace it with a comma, a colon, a
  full stop, or parentheses. Two sentences are almost always better than a dash.
- **En dash used as a sentence dash (–): zero.** It reads the same as an em dash to a
  detector. Hyphens inside compound words (top-up, part-time) stay; they are not dashes.
- **Exclamation marks: zero**, except inside a quoted real testimonial.
- **Emoji: zero.**
- Bold inside running prose only for terms a parent must not miss, max 3 per 500 words.
- Semicolons sparingly. Two or more in a single paragraph is a smell; prefer full stops.
- Colon-then-bullet as a crutch in every section is a tell. Use prose where prose belongs.

The em dash character may appear only where a rule must literally name the banned character:
this rule, the detection command in §4, and the QA checklist lines in G07 and the handoff
template. It is never used as punctuation, in any deliverable or rulebook, in prose or in a
heading.

## 3. The positive craft checklist (what T9 actually does)

- Replace abstract nouns with the concrete thing they hide.
- Break any sentence over ~30 words unless it genuinely needs the length.
- Kill connectors: most *moreovers* and *furthermores* become full stops.
- Give each paragraph a different shape: one is two lines, one is five, one is a table.
- Keep one metaphor per piece at most; usually zero. Facts do the work here.
- Read it aloud. Rewrite whatever makes you stumble.
- Hunt every dash. If a sentence leans on one, split it or repunctuate it.

## 4. Machine sweep commands (T10 runs these; output is kept)

```bash
# banned words (case-insensitive), expect zero matches in publish text
grep -rniE "delve|tapestry|seamless|robust|unlock|unleash|harness|foster|elevate|realm|testament|pivotal|embark|transformative|revolutioni|game.changer|cutting.edge|state.of.the.art|holistic|synerg|paradigm|streamline|empower|myriad|plethora|boasts|moreover|furthermore|additionally|in conclusion|in summary|it is important to note|worth mentioning|worth noting|it should be noted|at the end of the day|in a nutshell|look no further|without further ado|when it comes to|at the heart of|shaping the future|let's (explore|dive)|not only .* but also" {file}

# em dash and en dash, must both return 0
grep -o "—" {file} | wc -l
grep -o "–" {file} | wc -l

# verbatim sentence repeat across sections (sorted duplicate lines >= 40 chars)
grep -hE "^.{40,}" {file} | sort | uniq -d

# placeholder sweep, must return 0 in anything the client reads
grep -rniE "\[fee\]|\[month\]|\[student name\]|\[add|\[tbd\]|\[verify\]|\[placeholder\]|\[insert|\[your|\[client" {file}

# instruction residue, must return 0 in client-facing files
grep -rniE "note to|writer'?s note|todo|tbd:|fill this|add here|replace this|instructions?:" {file}
```

Zero hits required for Gate 5 / Gate 7. Any hit = fix, re-run, attach the clean output.

## 5. The 11-point naturalness scorecard (>= 10 to pass)

| # | Check | Pass condition |
|---|---|---|
| 1 | Banned-word/phrase grep | 0 hits (§4 command) |
| 2 | Rhythm | sentence lengths visibly varied; no 3 same-shape paragraphs in a row |
| 3 | Concrete density | every paragraph has fact/name/number/instruction |
| 4 | Connectors | no repeated connector family; hedge stacks gone |
| 5 | Rule of three | ≤ 1 trio per section |
| 6 | Em dash / en dash | 0 of each, anywhere in the file |
| 7 | Exclamation + emoji | 0 of each |
| 8 | Openings/closings | no summary-then-restate; no mirror endings |
| 9 | Spoken grammar | contractions and turns present where speech would use them |
| 10 | Read-aloud test | no stumbles (T9 attests) |
| 11 | Counsellor-desk test | could be said, unchanged, to a family in Sharjah (T9 attests) |

A publishable file scores 10 or 11. A single em dash caps the score at 9 and fails the gate.

## 6. Scope, this protocol covers every artifact

Landing page copy, guide articles, reports, verification documents, client packs, emails,
WhatsApp scripts, and every PDF we hand to the client. Internal pipeline files (like this
one) are held to the same writing standard. We practice what we police. The only
artifact-type differences: technical specs may keep longer sentences; scorecards may stay in
table form.

Two artifact classes carry the strictest version of this rule, because the client holds them:

- **Client-facing documents and PDFs.** Zero placeholders, zero internal instructions, zero
  process notes, zero AI tells. See G07 §7 for the full client-document rule.
- **Published pages.** Same standard, plus the SEO/AEO/GEO checks in G03 to G05.

## 7. Research basis

- Machine tells and their thresholds: em-dash density, burstiness < 0.4, rule-of-three,
  hedge stacking, buzzword clusters:
  [Top 10 Signs of AI-Generated Text](https://www.explainx.ai/blog/top-10-signs-of-ai-generated-text-2026),
  [Signs of AI Writing, 12 Patterns with Thresholds](https://slopdetector.org/blog/signs-of-ai-writing),
  [The Telltale Signs of AI-Generated Content](https://www.ailocthinktank.com/post/the-telltale-signs-of-ai-generated-content),
  [27 Red Flags of AI Writing](https://vrid.ai/blog/signs-of-ai-writing),
  [Is This Copy Human?](https://www.laurenperna.com/laurens-latest-news/how-to-tell-if-content-is-ai-generated).
- The constructive standard (specificity, first-hand substance, E-E-A-T) beats detector
  cat-and-mouse:
  [Google E-E-A-T Guide 2026](https://linkbuilder.com/blog/google-eeat-guide),
  [YMYL Content Guidelines 2026](https://koanthic.com/en/ymyl-content-guidelines-complete-guide-for-2026/).

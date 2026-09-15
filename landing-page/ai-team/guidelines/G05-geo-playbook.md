# G05 · GEO Playbook (generative engines)
**Owner:** T5 · **Checked at:** Gate 6 · **Cycle:** freshness re-verification every 90–180 days
**Goal:** when someone asks ChatGPT, Perplexity, Gemini, or Google AI Overviews about
bachelors degrees in the Gulf, Maverick is in the answer: cited, quoted, recommended.

---

## 1. How GEO actually works

Generative engines retrieve **passages, not pages** (RAG), then synthesize and cite. So the
job is: be retrievable (rank well enough), then be the cleanest passage to quote. Google's own
position: there is no separate "AI optimization". AI Overviews cite pages that already rank.
**SEO is the entry ticket; extractability wins the citation.**

## 2. The extractability rules

1. **Front-load.** The complete answer to the section's question lives in the first ~200 words.
   Conclusion before explanation.
2. **Quotable lines.** Each section carries at least one sentence that could be lifted whole
   into someone else's answer: short, complete, factual. Example: *"Since March 2025, the UAE's
   MoHESR has run a formal recognition route for online degrees from accredited universities."*
3. **Clean boundaries.** Short paragraphs (2–4 sentences). Lists for steps and constraints.
   Tables for comparisons. No answer tangled in caveats.
4. **No promo tone in citable passages.** Promotional language measurably reduces AI citation
   rates (~26% in Semrush's analysis). State facts; the facts are the pitch.
5. **Entity clarity.** The page is unambiguous about who wrote it, who publishes it, and what
   organization it describes. Consistent entity name everywhere: "Maverick Business Academy".

## 3. The citation trio (Princeton GEO research: the three techniques that pay)

| Technique | Measured effect | Maverick application |
|---|---|---|
| **Add statistics** | ~ +37% visibility (Perplexity) | every citable section carries a sourced, dated number (the M-pack) |
| **Add citations/sources** | up to +115% for lower-authority sites | name sources inline: "MoHESR via Gulf News, 10 March 2025" |
| **Add quotations** | up to +41% overall | real voices: client-permissioned student quotes, official policy wording |

Plus: authoritative tone where the topic is regulatory (recognition, attestation: R-pack
language does this naturally). Keyword stuffing measures **worse** than baseline. The one-home
rule (G03) already protects us.

## 4. Original data, Maverick's unfair GEO asset

Engines preferentially cite what exists nowhere else. Maverick can publish:
- dated fee-band comparisons (campus vs fast-track vs ours) rebuilt each intake cycle;
- the recognition facts pack with the private-sector/government distinction (no competitor
  states it sourced and dated);
- intake calendar data; office-verified local facts (Sharjah walk-in reality).
T6 owns accuracy; T5 decides which tables get the "cite this" framing.

## 5. Third-party citation surface

AI engines learn about brands from the wider web, not just the site:
- Directory profiles (educations.com, coursetakers.ae, bachelorsportal). Keep facts identical
  to the site (NAP discipline, G03 §5).
- Google Business Profile content. Engines read it; keep posts monthly.
- Guide articles: the "Is an online bachelor's valid in the UAE?" guide is a citation magnet
  for the parent-verification question.
- Consistent phrasing across all surfaces: if the site says "weekend-tolerant" and the listing
  says "flexible schedule", engines hedge. Pick the phrase, use it everywhere.

## 6. Freshness protocol

- Publish/review dates visible on every page and guide.
- Pillar content re-verified every 90–180 days (Perplexity and AI Overviews favour recent
  content; Perplexity cites ~76% of sources updated within 30 days).
- Regulation changes trigger same-week updates (R-pack is the spine; it cannot be stale).

## 7. Technical checklist (handed to the build team)

- [ ] robots.txt allows `OAI-SearchBot` (ChatGPT search citations) and `PerplexityBot`;
      decide GPTBot policy consciously, never by accident.
- [ ] `llms.txt` at site root: who Maverick is, key pages, contact facts.
- [ ] Bing indexability (ChatGPT's browsing leans on Bing). Submit sitemap to Bing Webmaster.
- [ ] FAQPage/Course/Org schema per G04 §6.
- [ ] Fast, mobile-first, crawlable (inherits G03 §7).

## 8. Measurement (post-launch)

- Prompt tests, logged monthly: ask ChatGPT / Perplexity / Gemini / AI Overviews the K- and
  R-series questions; record who gets cited and with what wording.
- GSC + GA4 still track the click traffic; AI visibility is tracked as its own line in the
  weekly summary. Zero-click answers count as wins when they carry our name.

## 9. Research basis

[GEO Guide 2026 · ShareUHack (Princeton techniques, platform table)](https://www.shareuhack.com/en/posts/geo-generative-engine-optimization-guide-2026) ·
[GEO 2026 · TechTimes](https://www.techtimes.com/articles/318359/20260614/generative-engine-optimization-geo-2026-how-get-your-content-cited-chatgpt-ai-overviews.htm) ·
[GEO Guide · SEOCrawl](https://seocrawl.ai/blog/generative-engine-optimization) ·
[GEO 2026 Guide · Frase](https://www.frase.io/blog/what-is-generative-engine-optimization-geo) ·
[GEO: How to Get Cited · Kickads](https://www.kickads.co/en/generative-engine-optimization)

# G03 — SEO Playbook (classic search)
**Owner:** T4 · **Checked at:** Gate 1b, Gate 6, Gate 7
**Reference:** `../../bachelors/02-keyword-research-bachelors-gcc-uae.md`

Classic search is still the entry ticket; AI engines overwhelmingly cite pages that already
rank. This playbook is Maverick-specific; it inherits the rules proven on the London and
Bachelors pages and adds the 2026 layer.

---

## 1. Keyword architecture

- **Two tiers.** Tier 1 (K-series): headline keywords that shape H1/meta and carry sections.
  Tier 2 (R-series): broader reach phrases woven in, each with exactly one home.
- **One home per phrase.** A phrase may also headline one guide article; that is its home.
  Two homes = stuffing = P0.
- **Journey coverage.** The map must cover: discover → verify → narrow → localise → de-risk
  (price) → compare. Check the tier table against this chain before sign-off.
- **Evidence per keyword:** intent · dated SERP observation · who ranks today · the win angle.
  Volumes are directional until re-run in Keyword Planner. The doc says so.

## 2. On-page rules

1. H1 benefit-led; the primary exact phrase lands in the hero subhead.
2. Headings written the way people ask ("Is an online bachelor's degree valid in the UAE?").
3. City coverage: Dubai, Sharjah, Abu Dhabi each ≥ once in H2s or first 100 words; GCC ≥ 2×;
   AED in fees; INR dated-parenthetical only.
4. No verbatim sentence template across sections; no keyword block repeated anywhere.
5. Internal links: to programme pages, adjacent rails (Europe pathway, masters rail),
   accreditations. Real routes only, verified in `routes/web.php`.
6. URL slug exact-match to the head keyword (`/bachelors-dubai` pattern), confirmed with the
   client before build.

## 3. Meta & snippets

- Title ≤ 60 chars: keyword + differentiator + brand, honestly in that order.
- Description ≤ 155 chars: the promise + the proof + the action; no keyword soup.
- Featured-snippet readiness: definitions and answers in the first 100–200 words of the
  section; steps as numbered lists; comparisons as real tables. Snippets are the rehearsal
  space for AI search. Win one, and the engines notice.

## 4. Fan-out subqueries (2026 layer)

Answer engines break a user's question into smaller sub-queries and search each one. Every
page must contain, in findable form, the answers to its sub-queries: fees by route, duration
by family, recognition by sector, visa status by delivery mode, attestation steps. The FAQ
block and answer capsules (G04) are where fan-out gets fed.

## 5. Local SEO (Sharjah office asset)

- Google Business Profile at Robot Park Tower: category education consultant / higher
  education, real photos, monthly posts.
- One master NAP sheet (name/address/phone identical everywhere): GBP + every directory.
- "Near me" coverage (K5/R6) comes from GBP + local copy, not a separate page per city.

## 6. Directories & third-party presence

One canonical profile per route on educations.com, coursetakers.ae, bachelorsportal + 2–3
comparables. Each listing = referral traffic + backlink + a third-party surface AI engines
read when deciding who to cite (see G05 §5).

## 7. Technical requests (hand to build team)

Crawlable, fast, mobile-first (most Gulf searches are phones): Lighthouse mobile ≥ 90 ·
images ≤ 200KB webp, lazy below fold · clean heading hierarchy · canonical URLs · XML sitemap
inclusion · GSC property verified per page.

## 8. Freshness

Publish/review date visible on guides. Re-verify stats and competitor anchors on a 90–180 day
cycle; update the date line when content changes (same cycle serves GEO, G05 §6).

# G07 · Production Readiness Checklist
**Owner:** T10 · **Gate:** 7, the last gate before the client sees content for sign-off
"Production-ready" is a checklist, not a feeling. Every box below is checked per deliverable.

---

## 1. Content completeness

- [ ] All sections of the blueprint present, in order, jobs fulfilled per the messaging framework.
- [ ] Keyword homes match the map exactly (T4 sign-off attached).
- [ ] Answer capsules present for every mapped question; FAQ↔schema 1:1 (T5 sign-off attached).
- [ ] Claims map at 100%: every stat and factual line has class + source + date.
- [ ] Regulation phrasing word-exact against the R-pack.

## 2. Mechanical sweep (grep output kept with the report)

- [ ] Placeholders in publish text = 0: `[FEE] [MONTH] [STUDENT NAME] [ADD] [TBD] [VERIFY]`.
- [ ] Masters-draft trap list = 0: "programmed" (noun), "program memes", programmes/programs mixed.
- [ ] G02 banned-pattern grep = 0.
- [ ] Em dash (—) = 0 and en dash (–) = 0, everywhere in the file, headings included.
- [ ] Exclamation marks = 0; emoji = 0.
- [ ] Verbatim sentence repeat across sections = 0.
- [ ] Apostrophe rule applied: bachelor's (noun) / bachelors (attributive).
- [ ] Durations: one per family, client-confirmed source noted.

## 3. Humanization attestation

- [ ] T9 scorecard attached, ≥ 10/11.
- [ ] Read-aloud + counsellor-desk tests attested in change notes.
- [ ] Facts untouched by the humanizer (claims map unchanged since Gate 4).

## 4. SEO/AEO/GEO compliance

- [ ] Meta: title ≤ 60 chars, description ≤ 155, keyword + differentiator present.
- [ ] Slug proposed (exact-match to head keyword) and flagged for client confirmation.
- [ ] Internal links resolve to real routes; cross-rails present.
- [ ] Schema spec valid: FAQPage / Course / EducationalOrganization / BreadcrumbList (+ Article
      for guides); markup mirrors visible content only.
- [ ] Publish/review date set; freshness cycle entered in the tracker.

## 5. E-E-A-T signals

- [ ] Publisher named consistently ("Maverick Business Academy").
- [ ] Author/byline plan for guides: real, client-approved person with credentials.
- [ ] Office + contact facts present and identical to the NAP master sheet.
- [ ] Permission state noted for every image/logo (text-only until permission lands).

## 6. Package assembly (what "delivery" contains)

1. The content file (markdown, numbered per the page folder convention).
2. Claims map (inline table or companion file).
3. Keyword map snapshot (the pieces this asset uses).
4. Schema spec block (JSON-LD, ready for the build team).
5. Humanization scorecard + T9 change notes.
6. QA report with verdict line: **APPROVED** or **REVISE (counts)**.
7. Build notes: route, partial names, form/event wiring, GSC/GA4 tasks, GBP/directory tasks.

## 7. Client-facing artifact rule (no AI traces, no placeholders, no instructions)

Anything the client or the public reads (approval packs, reports, the published page) and
every PDF or Word file handed to the client contains **zero** of the following:

- No placeholders of any kind: `[FEE]`, `[MONTH]`, `[STUDENT NAME]`, `[ADD]`, `[TBD]`,
  `[VERIFY]`, `[PHOTO]`, "to be confirmed", "insert here", blank filler lines. If a fact is
  not confirmed, the sentence either uses the delivery-model wording or is left out. A
  placeholder never reaches the client, even as a courtesy marker.
- No internal instructions or process notes: no "note to writer", "add keyword here",
  "replace this", "TODO", sign-off mechanics, gate references, or any direction meant for
  the team. Instructions live in the internal tracker, never in the document.
- No agent labels ("Agent 7 wrote this"), no role IDs, no tool/model names.
- No prompt text, no "as an AI", no process meta-commentary.
- No machine patterns per G02, and an em dash never appears (the client pack itself passes
  the banned-pattern and em-dash greps).
- Plain English, short lines, real headings. It should read like a senior consultant's memo.

The PDF check is a separate pass: the text is extracted and run through the same greps,
because a clean markdown source can still hide a stale rendered PDF. Both must pass.

Internal pipeline files may reference roles and gates; they are never shipped outside the repo.

## 8. Handoff to build (Laravel site)

- [ ] Route + controller pattern confirmed against existing pages (e.g. `/global-bachelors-pathway`).
- [ ] Content partials named per site convention; shared sections reuse existing partials.
- [ ] Form uses the existing admissions endpoint (Zapier/Zoho); no new backend invented.
- [ ] GA4 events specified: form_start, form_submit, wa_click, tel_click, pdf_download.
- [ ] Images via Cloudinary per `docs/cloudinary-guide.md`, ≤ 200KB webp.

## 9. Ship gate

Verdict APPROVED + zero P0 + client Approval #2 signed → build may start.
After launch: GSC property check, GA4 event verification, GBP draft published, first monthly
prompt test logged (G05 §8). The team's job ends when the measurement loop starts.

# Masters Pathway — Phase 2 Task: Content Audit vs Source Doc

## Status: PENDING — blocked (awaiting source doc)

## Task
Compare every section of the **Master's Pathway Program page**
(`resources/views/pages/masters-pathway.blade.php`) against the client's
**source content document**, and add any missing content word-for-word.

Specifically flagged:
- The **`[data-testid="mp-overview"]`** section is missing content that exists
  in the source doc.

## What to do in Phase 2
1. Obtain the source Master's Pathway content document (client's source of truth).
2. For EACH section (hero, overview, how-it-works, destinations, why-choose,
   who-for, requirements, application process, academic notice, final CTA),
   diff the current page content against the doc.
3. Add any missing content to the correct section, exactly as written in the doc
   (same wording — do not rewrite, do not invent stats/claims).
4. Preserve existing design/structure; only insert the missing copy into the
   appropriate markup (e.g., the `mp-overview` right column).

## Blocker
No Master's Pathway doc file has been provided yet. Please share it to unblock.

# Local FAQ review exports — not deployed

These seven JSON-LD files contain the **144 provider questions and current visible
answers** from `content/faqs/approved/`. They are generated with the shared parser:

```bash
python3 content/faqs/tools/audit_faqs.py --write-schema
```

The audit checks exact question/order/answer parity, not just the question count. Internal
comments, verification tables, section headings and Markdown formatting do not leak into
answers. Comparison tables preserve their column relationships in plain text.

**Do not inject a whole provider export into each programme page.** These are review
artefacts, not a deployment design: the final website must use only the questions and
answers actually visible on the relevant page and must preserve category applicability.
Explicit website approval and a separate implementation review are still required.

Google documents the end of FAQ rich results from 7 May 2026. The existence of a
Schema.org FAQPage type does not imply Google feature support, a ranking improvement or
AI Overview inclusion. See [1](https://developers.google.com/search/updates#deprecating-the-faq-rich-result-feature).

Homepage and Edutainment drafts have not been exported or imported into the website.

The 9 September 2026 revision includes exactly two authorised US/UK immigration
clarifications (RBS and GAU). Their schema matches the approved-for-review wording and
includes the disclaimer. This scoped country exception does not approve deployment.

# Payment Page and Gateway Implementation Plan

- **Client:** Maverick Business Academy
- **Client address:** #201, 2nd Floor, Robot Park Tower, Sharjah, United Arab Emirates
- **Prepared by:** Anoop Puri
- **Contact:** puri.anoop21@gmail.com, +91 8178838292
- **Plan reviewed:** 23 October 2026
- **Proposal reference:** Payment proposal dated 17 November 2026
- **Reference file:** `storage/app/proposals/Maverick_Business_Academy_Payment_Proposal.pdf`
- **Status:** Working plan. Client decisions and technical checks listed as Unknown must be confirmed before related implementation begins.

This is an internal implementation plan based on the current client proposal. It does not add a delivery date or maintenance offer to the proposal. The proposed project fee remains AED 1,000, payable as one full payment.

## Proposal scope to preserve

- A mobile friendly student payment page for phones and computers.
- Show the payment purpose and amount before payment. Show installment amounts and due dates, paid and remaining amounts, and a clear next step after a failed card attempt.
- Use Academy approved university, programme, course and payment purpose choices. Apply client confirmed fields for new and existing students.
- Staff can set each student's fee and generate that student's installment schedule. The Academy schedule is separate from any card issuer payment plan.
- Initial UAE method list: Visa, Mastercard, Apple Pay, Google Pay and Link. Link is described as quick checkout with saved details where available. Display depends on merchant account approval, currency, customer eligibility and device or browser.
- American Express is not promised unless account eligibility is confirmed. PayPal, UPI and NetBanking are outside the initial UAE scope and can be reviewed separately.
- Monthly card payment attempts happen only after clear student consent. A failed attempt must be visible and require the appropriate student action.
- Keep full card numbers and card security codes out of the Academy application.
- After a verified payment, show paid and remaining installments, send the student a confirmation or receipt and copy Academy finance.
- Staff and finance can review paid, due, overdue and failed payments and export payment details for reconciliation.
- Connect to Shadobooks only where the Academy account and supported interface allow it. Otherwise provide a reconciliation export. Any custom connector or paid service is quoted separately before approval.
- Project fee: AED 1,000, payable in full as one payment. Processing, hosting, email or SMS, paid third party tools and applicable taxes are separate.
- Before work begins, confirm the student fields, university and course lists, payment purposes and fee rules. The Academy completes merchant account checks and shares approved technical access securely.
- The client proposal has no delivery timeline or ongoing maintenance offer. Do not add either to the client proposal without a scope change and client approval.

## Proposal claim traceability

| Claim in the client proposal | Where this plan covers it | Acceptance evidence |
|---|---|---|
| Maverick Business Academy, address, Anoop Puri, contact details and proposal date | Document details above | Compare with the approved proposal PDF |
| Student page works on phones and computers | Student page and one time checkout | Student UAT on supported phone and desktop browsers |
| Payment purpose and amount are clear before payment | Student data and staff plan controls; student page | Rendered page shows the approved purpose, amount and currency |
| Student installment dates, paid and remaining amounts are visible | Student page and one time checkout | Schedule total, each due date and remaining balance reconcile |
| Student can use listed card and wallet methods | Student page and one time checkout; decisions to confirm | Test each method enabled on the approved merchant account |
| Fee and installment schedule can be set per student | Student data and staff plan controls | Admin creates a plan and the schedule sums exactly to the fee |
| Monthly card attempts require consent and failures require student action | Consent and monthly card attempts | Tests prove no consent means no charge, and a failed attempt prompts the defined next step |
| Verified payment sends student confirmation and finance copy | Verified payments, receipts and finance copy | One settled payment produces one student message and one finance copy |
| Shadobooks sync or reconciliation export is provided | Shadobooks and reconciliation | Supported sync or export reconciles to the payment ledger |
| Project fee is AED 1,000, paid once; listed third party charges are separate | Proposal scope to preserve | Invoice and approval reflect the same terms |
| Client supplies approved access before work begins | Decisions to confirm and Release decision | Onboarding is complete and technical access is received securely |
| No delivery timeline or maintenance offer is included | Proposal scope to preserve | Final client proposal remains free of those offers |

## Review of the earlier plan

The earlier plan had the right broad features, but it needed stronger sequencing and clearer release gates. This version improves it by:

- Separating confirmed proposal scope from decisions that are still Unknown.
- Treating recurring card payments as a feasibility and consent gate, not as a simple extension of one time checkout.
- Adding checks for duplicate checkout attempts, event replay, payment amount and currency mismatches, overpayment and concurrent updates.
- Making Shadobooks sync conditional and defining a reconciliation export as the fallback.
- Adding financial audit and access controls for manual paid or undo actions.
- Considering the existing shared hosting guidance: no long running worker is assumed, queue and scheduler support must be confirmed, and a cPanel cron approach may be needed.
- Requiring evidence from executed tests before any item is called complete. Source code or a written test is not proof that it passes.
- Avoiding a claim that the system can be guaranteed bug free or immune to attack. The goal is to reduce risk, detect failures and block release when critical checks fail.

## Decisions to confirm

Do not guess these values. Record the answer or keep the item as **Unknown**.

| Decision | Current status | Needed before |
|---|---|---|
| University, programme, course and payment purpose lists | Unknown | Form and admin configuration |
| New versus existing student fields and show or hide rules | Unknown | Form behavior |
| Student identifier and duplicate student handling | Unknown | Admin workflow and records |
| Whether installments are equal or individually configurable | Unknown | Schedule generation |
| Monthly due date, first due date, time zone and grace rules | Unknown | Schedule and expiry behavior |
| Partial payments, full balance payments, refunds and cancellations | Unknown | Payment state and reconciliation rules |
| Consent wording, record retention, revocation and retry policy | Unknown | Monthly card attempts |
| Actual methods enabled for the approved merchant account | Unknown | Checkout acceptance |
| Shadobooks tenant, plan, API access, supported invoice and payment operations, and sandbox | Unknown | Direct sync decision |
| Finance recipient, sender identity and receipt requirements | Unknown | Email acceptance |
| Production database, proxy list, scheduler and queue capability | Unknown | Deployment design |
| Which staff roles may create plans, mark payments or correct records | Unknown | Admin permissions |

## Go live blockers

**Must pass before the first live charge**

- Checkout amount, currency, transaction and plan must match on both webhook and return verification paths.
- Duplicate checkout requests, concurrent payments and replayed events must not create a second charge or a second ledger entry.
- Admin access, manual corrections, reversals, financial history retention and sensitive logging must be reviewed and tested.
- Proxy trust must match the actual hosting proxy chain. Do not ship an unrestricted trust setting without review.

**Must pass before the proposal scope is accepted as complete**

- Consent, recurring card attempts, failure handling and a supported scheduler must work together. If the account or hosting cannot support this, stop and agree a revised scope before implementation.
- Student confirmation and finance copy must actually be sent after settlement. The page must not claim that a receipt was sent when no mail was dispatched.
- Shadobooks must either sync through confirmed access or have the agreed reconciliation export, with matching totals.
- Required university, programme, course, purpose and new or existing student rules must be present and approved.

## Architecture and data boundaries

Keep the existing Laravel structure where practical. Do not introduce a new package or a broad rewrite without approval.

```text
Student and staff pages
        |
Routes, middleware and request validation
        |
Payment application services
   |                 |
Gateway adapter   Shadobooks adapter
        |                 |
Payment records, event history and reconciliation
        |
Notifications after a verified state change
```

- **Pages:** Blade payment page and Filament admin screens. Browser supplied values never decide the amount or payment status.
- **HTTP layer:** Controllers validate requests and call services. Keep CSRF protection on browser forms. Exempt only the provider callback route, and verify its signature before processing it.
- **Application layer:** Keep schedule calculation, checkout creation, payment state transitions and reconciliation out of Blade templates and thin controllers.
- **Gateway adapter:** Use hosted card entry. The application must not collect or store full card numbers or card security codes. Confirm payment outcomes server side.
- **Persistence:** Store amounts as integer minor units. Keep a financial history that cannot be silently erased by deleting a plan. If needed, add a unique webhook event ledger and a durable outbox for notifications and external sync. Add new migrations only after review and approval. Do not alter existing migrations or run migrations without approval.
- **Event processing:** Record a unique provider event identifier and process each event idempotently. Apply a payment and update its installment within one database transaction. Use locking and database constraints to prevent concurrent duplicate settlement.
- **Notifications:** Create a deduplicated outbox record in the same transaction as the verified payment update. Dispatch after commit. This avoids losing a receipt or finance copy if sending fails after the payment is recorded.
- **Shadobooks:** Keep this integration behind a separate connector. Make updates retry safe. If supported access is unavailable, produce an export whose totals can be reconciled to the payment ledger.
- **Hosting:** Production capabilities are Unknown. Existing shared hosting guidance uses synchronous jobs by default and does not assume a persistent worker. It describes an optional database queue drained through the scheduler and cPanel cron. Confirm the actual account's PHP runtime, database, scheduler, cron and queue settings before choosing how monthly attempts, retries, notifications and sync run.

## Implementation phases

### Scope and acceptance

- Confirm all items in the decision table with the Academy.
- Convert each proposal promise into an acceptance check.
- Confirm which initial payment methods are enabled on the approved account.
- Agree on fee calculation, installment dates, failure handling and who receives finance copies.

**Exit gate:** No unresolved decision that changes payment amounts, recurring consent, student fields or Shadobooks behavior is treated as settled.

### Read only baseline and risk triage

- Map existing routes, pages, admin actions, models, migration, gateway adapter and tests to this plan.
- Label each requirement as code present, partial, missing or Unknown.
- Review the current branch and worktree before changing anything.
- Do not run migrations, seeders, installs, builds or destructive commands during this phase.

**Exit gate:** A reviewed gap list and test list exist before implementation changes are proposed.

### Student data and staff plan controls

- Add the confirmed student type, university, programme, course, purpose and identifier rules.
- Validate every field on the server and use allowlisted values for Academy controlled choices.
- Keep fee entry and schedule generation server side. Convert decimal input to minor units without floating point rounding.
- Confirm whether a schedule is equal monthly installments or a custom schedule. Make the displayed amounts sum exactly to the agreed total.
- Verify admin role permissions. Record every manual payment mark, correction, cancellation and reversal with actor, time, reason and reference.
- Prevent normal admin actions from deleting financial history. Prefer an auditable cancelled or archived state over cascading deletion.

**Exit gate:** Admin actions, schedule totals and role boundaries pass tests; student data shown to a link holder is limited to what the Academy approves.

### Student page and one time checkout

- Keep the page responsive, private and clear about the amount, purpose, dates, paid amount and remaining balance.
- Use an unpredictable private link, no index and no store headers, and a no referrer policy. Treat the link as a bearer credential and share it only with the intended student.
- Calculate amount and currency from the stored plan, not from form input.
- Limit duplicate checkout creation for the same installment. A double click or concurrent request must not create two payable sessions.
- Check expiry, cancellation, already paid plans and a full balance payment before creating checkout.
- Confirm the selected methods in the actual approved account. Do not promise methods based only on a global product list.

**Exit gate:** Tampered values, repeated requests and closed plans cannot produce an unintended charge.

### Consent and monthly card attempts

- Confirm the account and card method support off session recurring charges for this merchant and flow.
- Show a clear, unchecked consent control before saving a payment method or scheduling a future charge.
- Store the consent time, wording version and relevant plan reference. Collect only the additional evidence the client and applicable rules require.
- Define how a student withdraws consent. Once withdrawn, no new automatic attempt is created.
- Use a scheduler that is confirmed for the production host. Do not assume an always running worker. A cPanel cron and supported Laravel scheduler may be used only after host access and configuration are confirmed.
- Define limited retries, spacing and notifications. Authentication, expired card or declined payment must stop automatic retries where student action is needed.

**Exit gate:** No charge can run without recorded consent, a valid schedule and an eligible saved method. Failed attempts are visible and have a safe student action path.

### Verified payments, receipts and finance copy

- Verify webhook signatures against the unmodified request body and reject stale, invalid or malformed messages.
- Deduplicate provider events. Validate event type, transaction association, plan state, amount and currency on both webhook and return verification paths.
- Treat the server verified payment event as the source of truth. A browser return or success URL alone never marks a plan paid.
- Lock the affected transaction and installment while applying the change. A repeated event must not pay twice or allocate money to the wrong installment.
- Send the student confirmation and finance copy after the database commit. A page must not claim a receipt was sent unless the mail process accepted it.
- Store only the identifiers and fields needed for reconciliation. Do not persist full raw provider payloads or expose provider error text to students.

**Exit gate:** Correctly signed, amount matched, currency matched payments update exactly once. All other events leave the payment plan unchanged and create a safe operational alert.

### Shadobooks and reconciliation

- Inspect the Academy's actual Shadobooks tenant and documented access before promising a direct connector.
- Map the payment reference, invoice, student, amount, currency and paid date with the client finance contact.
- If a supported interface exists, test duplicate delivery, timeout, retry and recovery without duplicate invoices or payments.
- If it does not, provide a reconciliation export and document which finance action remains manual.
- Reconcile the application totals against provider transactions and Shadobooks records before acceptance.

**Exit gate:** Direct sync or the agreed export path produces matching totals and a visible exception list.

### Security and acceptance testing

- Start by running the existing `PaymentPageTest` in an approved, isolated test database, then run the full suite. It uses `RefreshDatabase`, so test execution runs migrations against that test database. Never point it at production, and obtain approval before any migration-running test. Record the command, environment and result.
- Run unit and feature tests for schedule arithmetic, amount conversion, date boundaries, validation, access, checkout and payment state transitions.
- Test forged amount, currency, plan and installment values; unknown or expired tokens; cancelled plans; repeated checkout and parallel requests.
- Test valid, invalid, stale, replayed, duplicated, out of order and malformed callback events.
- Test amount and currency mismatches on both callback and return verification paths.
- Test manual mark paid, correction and reversal permissions, audit history and preservation of the ledger.
- Test consent missing, revoked consent, declined card, required authentication, expired method and limited retry behavior.
- Test email failure and repeat events. Verify a receipt is sent only once after settlement and finance receives the agreed copy.
- Test Shadobooks outage, duplicate sync, recovery and export reconciliation.
- Verify financial invariants: installment totals equal the plan total; paid amounts come only from verified transactions or an audited manual receipt; a payment is allocated once; unallocated or excess amounts become visible exceptions.
- Review XSS, SQL injection, CSRF, IDOR, open redirects, rate limits, secret handling, proxy trust, cache headers and sensitive logging.
- Perform staff and student UAT on supported phones and browsers.

**Exit gate:** No unresolved critical or high severity finding. All payment totals reconcile. The client signs off on the student fields, schedule, methods, notices and finance workflow.

### Release decision

- The client completes merchant signup, KYC and bank verification. After verification, request only the required technical access through a secure channel. Never request bank login details, passwords or OTPs.
- Confirm production settings, domain, HTTPS, callback URL, trusted proxy configuration, scheduler and backup process.
- Separate test and live credentials. Keep secrets out of source control, logs and chat.
- Apply only reviewed forward migrations after separate approval. Run the release checklist and a small controlled live transaction only with client approval.
- Keep rollback instructions and an exception contact ready before switching traffic.

**Release gate:** Do not enable live charges until all prior gates pass and the client approves the launch.

## Security findings to resolve from the current source review

These are review risks, not confirmed exploits. Verify and address them before live payment use.

- Checkout still creates a new transaction for each request. Protection against multiple pending sessions for the same installment and full balance remains a live charge blocker.
- The return path now requires both the verified amount and currency to match. The regression test is written but has not run.
- A webhook signature and timestamp check exist, and payment application uses a database transaction lock. A durable unique provider event record and a unique session constraint are still missing.
- The success page no longer promises that an email receipt was sent. The actual student receipt and finance mail workflow is still missing.
- The silent Undo action has been removed. Manual paid entries now record the authenticated user ID, but a reviewed correction or reversal workflow and role permissions remain to be defined.
- The reviewed schema cascades plan deletion to installments and transactions. Financial records should be retained and corrected, not silently erased.
- Successful payment records now keep only a small set of provider identifiers. Checkout failures return a generic message and provider logs omit raw error text. These changes are not runtime tested.
- Global proxy trust is configured broadly. Confirm the actual host proxy chain and trust only the intended proxies; forwarded headers affect scheme and client IP handling.
- Payment plan records are behind the Filament login, but payment specific role authorization is Unknown.
- The hosted page limits card data exposure, but no PCI scope statement should be made until the merchant's acquirer confirms the correct assessment path.
- The default grace period and support contact values need Academy confirmation before production use.

## Current source level completion snapshot

Source review only. Nothing below has been runtime tested in this workspace.

| Proposal item | Source level status | Notes |
|---|---|---|
| Private payment page and installment display | Partial | Token link, schedule, paid and remaining display, no index and no store headers are present. |
| Staff creates a fee and schedule per student | Partial | Name, email, phone, programme text, total, installment count and first due date are present. Schedule is equal monthly with remainder in the last installment. |
| University, course, purpose and new or existing student rules | Missing | Not present in the reviewed plan form or model. |
| Initial UAE payment methods | Unknown | Hosted checkout code exists. Account eligibility and actual displayed methods are not verified. |
| Consent based monthly card attempts | Missing | No consent record, saved method workflow, schedule runner, retry policy or revocation path was found. |
| Verified payment and paid or remaining status | Partial | Callback and return paths compare amount and currency; transaction locking exists. Durable event deduplication and duplicate checkout protection remain missing. Runtime result is Unknown. |
| Student receipt and finance copy | Missing | No mail sender was found. The success page no longer claims a receipt was sent. |
| Shadobooks sync or reconciliation export | Missing | No connector or export was found in the reviewed payment module. |
| Feature tests | Written, not run | Sixteen feature cases cover schedule sums, page rendering, checkout, callbacks, return verification, safe error messages and plan states. Test results are Unknown. |

## Codebase file change map

The following file map separates safe source changes from work that depends on client or host confirmation. Do not mark a row complete until the source change is reviewed and its tests pass in an approved environment.

### Current safe implementation batch

| File | Change | Status |
|---|---|---|
| `app/Http/Controllers/PaymentPageController.php` | Require verified amount and currency on the return path; use generic student facing errors; store only approved payment metadata, not the full provider response | Implemented in source; runtime unverified |
| `app/Payments/StripeGateway.php` | Keep raw provider error text and connection details out of logs | Implemented in source; runtime unverified |
| `app/Payments/PaymentPlanService.php` | Record the authenticated staff user on manual payment entries; remove the unrecorded reset to pending operation | Implemented in source; runtime unverified |
| `app/Filament/Resources/PaymentPlanResource/RelationManagers/InstallmentsRelationManager.php` | Remove the silent Undo action until a reviewed, audited correction or reversal flow exists | Implemented in source; runtime unverified |
| `resources/views/pay/result.blade.php` | Do not tell students a receipt was sent until a mail workflow exists | Implemented in source; runtime unverified |
| `tests/Feature/PaymentPageTest.php` | Add regression coverage for return currency mismatch, metadata minimization and safe error messages | Added; 16 feature cases are written, not run |

### Gated implementation work

| Files or area | Planned change | Dependency |
|---|---|---|
| `app/Filament/Resources/PaymentPlanResource.php`, `app/Filament/Resources/PaymentPlanResource/Pages/CreatePaymentPlan.php`, `app/Models/PaymentPlan.php`, and a new migration | Add university, course, purpose, student type and any client approved student identifier fields | Client confirms the lists and new or existing student rules |
| `app/Payments/PaymentGateway.php`, `app/Payments/StripeGateway.php`, `app/Payments/PaymentPlanService.php`, `app/Http/Controllers/PaymentPageController.php`, a new migration and scheduler entry | Add consent records, saved method handling, scheduled card attempts, limited retries and revocation | Merchant account confirms off session eligibility; client approves consent and failure wording; hosting scheduler is confirmed |
| `app/Http/Controllers/PaymentPageController.php`, `app/Models/PaymentTransaction.php`, a new event ledger migration/model and an outbox processor | Deduplicate provider events, prevent parallel checkout attempts, retain minimal event data and dispatch notifications after commit | Database constraints and test database are reviewed; hosting supports the chosen processing method |
| New mail classes, notification configuration and outbox handling | Send exactly one student confirmation and finance copy after settlement | Finance recipient, sender configuration and approved receipt content are supplied |
| `app/Filament/Resources/PaymentPlanResource.php` and a new reconciliation export service | Produce a CSV from the internal plan, installment and settled transaction records, with spreadsheet formula protection | Finance confirms required columns, date range and staff permissions; no Shadobooks API is required for this fallback |
| New Shadobooks adapter | Sync supported invoice and payment fields without duplicate updates | Shadobooks tenant, API documentation, permissions and sample records are supplied |
| `bootstrap/app.php` | Replace broad proxy trust only with the actual approved proxy list | Hosting provider confirms the proxy chain and addresses |
| Payment migration and admin correction flow | Preserve the financial ledger and record corrections instead of cascading deletion or silently clearing paid status | Migration review, approval and a defined finance correction policy |

## Working constraints

- Follow the file change map. Work that depends on an Unknown client, merchant or hosting decision stays blocked until that decision is confirmed.
- Do not run migrations, seeders, package installs or builds without approval. The new feature tests use `RefreshDatabase`, so run them only against an approved isolated test database.
- Do not read or create `.env` files. Never put secret values in a commit or a message.
- PHP and Composer are unavailable in the current sandbox. Source changes and written tests remain runtime unverified until they run in a suitable environment.
- Current production drivers, merchant account eligibility, finance recipient and Shadobooks access remain Unknown until confirmed.

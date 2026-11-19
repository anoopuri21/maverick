# Review remediation — 15 phases

Tracking sheet for the improvements identified in the repository review. Each phase is
intentionally small enough to review and test on its own. Status is updated as work lands.

| Phase | Area | Primary files | Status |
|---|---|---|---|
| 1 | reCAPTCHA works out of the box and is configurable | `config/services.php`, `app/Services/GoogleRecaptchaVerifier.php`, `.env.example` | Done |
| 2 | Form-mail failures are no longer reported as success | `app/Services/FormMailer.php`, form controllers | Done |
| 3 | Sanitize stored rich HTML before rendering | `app/helpers.php`, rich-text views | Done |
| 4 | Run PHP + JS checks in CI | `.github/workflows/tests.yml`, `package.json` | Done |
| 5 | Make media cleanup fail-safe | `app/Services/MediaUsageService.php`, `app/Services/MediaCleanService.php` | Done |
| 6 | Replace hardcoded production identity with config | `app/Support/PreferredOrigin.php`, `config/site.php` | Done |
| 7 | Restrict proxy/host handling | `bootstrap/app.php`, `app/Providers/AppServiceProvider.php` | Done |
| 8 | Replace Laravel boilerplate README and document setup | `README.md`, `.env.example`, `composer.json` | Done |
| 9 | Validate the events AJAX offset | `app/Http/Controllers/EventController.php` | Done |
| 10 | Stop the default seeder creating a known admin account | `database/seeders/DatabaseSeeder.php` | Done |
| 11 | Add honeypot protection to newsletter signup | `app/Http/Requests/NewsletterSubscribeRequest.php`, footer view | Done |
| 12 | Remove brittle reflection into Spatie settings internals | `app/Filament/Concerns/EnsuresSettingsRowsExist.php` | Done |
| 13 | Remove tracked junk and dead code | `backup.sql`, `toArray())`, `verify_events_v3.py`, dead mailable | Done |
| 14 | Scope Filament's global validation overrides | `app/Filament/Support/LenientFormValidation.php` | Done |
| 15 | Consolidate and extend test coverage | `tests/`, `package.json` | Done |

## Second-pass audit fixes

A follow-up review of the implemented phases found and corrected these issues:

- **Phase 6 — origin redirects:** `RedirectPreferredOrigin` compared only host + HTTPS, which
  caused an infinite self-redirect for an `http://` or custom-port canonical origin. It now
  compares scheme, host and port, and the test suite covers that case. Proxies that forward
  the scheme but omit `X-Forwarded-Port` are handled by treating the other protocol's default
  port as unspecified, so the existing forwarded-HTTPS test still passes.
- **Phase 6 — canonical normalization:** `PreferredOrigin::normalize()` could duplicate a
  configured port (`localhost:8000:8000`). The port is now part of both match and replacement.
  The canonical host is also always included in `hosts()`, so changing only
  `PREFERRED_ORIGIN_URL` does not silently disable canonicalization.
- **Phase 5 — media cleanup:** an incomplete reference scan no longer overwrites usage flags,
  and a failed per-asset rescan is treated as "still used" rather than allowing deletion.
  Both cases have regression tests.
- **Phase 1 — CAPTCHA logging:** the "verification skipped" warning is logged once per process
  instead of once per form submission.
- **Phase 2 — mail fallback:** `CONTACT_FALLBACK_EMAIL` is validated through `filled()` before
  use, so a blank env value falls back to the built-in address.
- **Phase 9 — events offset:** the AJAX offset is now clamped to a non-negative, bounded value
  so extremely large input cannot create an unusable SQL offset.
- **Phase 4 — formatting:** corrected the indentation of the npm scripts block.
- **Phase 5 — admin UI:** the media usage / clean dialogs now surface incomplete scans and
  refused deletions instead of showing a success notification.
- **Phase 11 — newsletter:** a long honeypot value no longer causes a 422, and the
  notification is sent before Zoho/Zapier side effects so a mail failure cannot leave a
  half-subscribed lead that the visitor is told to retry.
- **Phase 14 — validation:** credential-like field names with suffixes (`*_secret`,
  `*_token`, any `*password*`) are also excluded from inferred validation overrides.
- **Phase 7 — bootstrap timing:** the trusted-proxy middleware callback reads
  `TRUSTED_PROXIES` from the environment directly because it runs before the config
  repository is bound (reported as `Class "config" does not exist` on a local Laragon install).

## Verification in this checkout

- All 77 changed/new PHP files parse cleanly with PHP 8.3 `token_get_all(..., TOKEN_PARSE)`.
- `node --test tests/js/*.test.cjs` passes (2/2).
- `git diff --check` reports no whitespace errors.
- Workflow YAML parses and defines the expected `php` and `javascript` jobs.
- Runtime checks with PHP-WASM confirm sanitizer behavior and `PreferredOrigin` normalization
  (including custom ports, implied canonical hosts and untouched third-party URLs).
- The PHP suite could not be executed here because `vendor/` and a PHP binary are absent;
  the GitHub Actions workflow added in phase 4 runs `php artisan test` on every push/PR.

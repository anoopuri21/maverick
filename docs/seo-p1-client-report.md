# SEO audit — P1 fix report

**Scope:** Preferred domain and homepage search/social metadata. P2 and P3 are not included.

**Status: code prepared; not yet deployed or verified on production. P1 remains open** until the hosting and CMS steps below are completed. The audit's live findings have not been independently rechecked from this environment.

## 1. Preferred domain

The intended preferred origin is **https://mbalondon.org.uk**.

- The application now normalizes default canonical URLs and known HTTP/www CMS canonical overrides to that origin on the known production hosts. Paths and query strings in overrides are retained. Overrides for other domains are not automatically changed.
- Sitemap entries are normalized to the preferred origin when generated on those hosts; existing sitemap exclusions remain in place.
- A Laravel redirect fallback is available for requests routed through the application. It preserves the path and query; GET/HEAD use 301 and other methods use 308. It does not apply to preview/local hosts or ACME challenges.
- **The fallback is disabled by default** (`SEO_ORIGIN_REDIRECT_ENABLED=false`). This avoids introducing redirect loops before production proxy and HTTPS configuration are known. It also does not cover static files or other requests handled before Laravel.

### Hosting action required

Ask the hosting/CDN administrator to implement one canonical-origin redirect at the first public-facing layer for all three alternate origins to `https://mbalondon.org.uk`, preserving path and query. Prefer this single edge/web-server rule over enabling the Laravel fallback. Tell them to preserve existing legacy redirects and ACME validation, confirm SSL certificates for both hosts, and handle non-GET requests without losing their method/body. They should verify that the proxy sets and sanitizes forwarded headers correctly; the application's current proxy trust is broad and must be reviewed with the actual infrastructure.

If an edge-level rule is not feasible and the administrator chooses the application fallback, set `SEO_ORIGIN_REDIRECT_ENABLED=true` in the production environment and rebuild the Laravel configuration cache as appropriate. The administrator must first verify that Laravel recognizes HTTPS correctly behind the proxy and separately cover static resources. Do not enable both layers without testing for loops.

`APP_URL` should be `https://mbalondon.org.uk` in production; changing it requires the normal configuration-cache refresh. Do not change this value in local or preview environments without checking their setup.

## 2. Homepage metadata

- Added a descriptive title and description **fallback** when the corresponding CMS fields are empty. Non-empty CMS values remain authoritative and may still need editorial updating.
- Homepage Open Graph and Twitter titles/descriptions fall back to the resolved homepage copy; explicitly configured social values take precedence.
- The existing CMS share-image support remains in place; Twitter image falls back to the Open Graph image. No unapproved image was added.

### CMS action required

In the admin panel, open **Homepage → Homepage SEO**. Review and update the title and description if existing CMS values are generic. Set an approved, publicly accessible Open Graph image in the Social Sharing section; use a suitable landscape image, preferably 1200 × 630 pixels. Set a Twitter image only if a different one is desired. Save, then confirm the actual image URL can be loaded without login and check a social preview. Draft fallback copy should also receive editorial approval. Existing CMS values are deliberately not overwritten by deployment.

## Verification and release

1. Run the P1 Laravel feature tests and existing sitemap/homepage tests in a PHP 8.3+ environment with project dependencies installed. Tests were added but **Laravel integration tests could not be run here** because project dependencies are unavailable.
2. PHP syntax checks and isolated origin smoke checks using PHP WASM passed; the latter use request stubs and are **not** a substitute for Laravel or server integration tests. Existing MBA landing and testimonial autoplay JavaScript tests and `git diff --check` passed.
3. After deployment, test each of the four origins on homepage and sample nested URLs with a query string. The three alternatives should reach the preferred URL without loops; check a form POST safely, static resources, legacy redirects and ACME renewal with the hosting owner.
4. Verify one intended homepage canonical and description, social titles and the approved image in rendered HTML. Check sitemap, robots and a missing page (404), review CMS canonical exceptions and monitor Search Console. Refresh relevant caches using the deployment procedure.

Rollback consists of reverting the P1 application changes and separately rolling back any hosting/CDN rule. Preserve unrelated work.

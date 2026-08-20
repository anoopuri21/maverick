# CI Workflow — Manual Installation Required (one time)

Arena's GitHub connection cannot push workflow files (GitHub security: the app token
lacks the `workflows` permission). Install this approved CI config manually — takes
about a minute:

## Steps (GitHub web UI)

1. Open the repo on GitHub → switch to branch **`arena/01a019ff-maverick`**
2. Click **Add file → Create new file**
3. Filename: `.github/workflows/ci.yml`
4. Open [`ci/ci.yml`](./ci.yml) (this folder) → copy its full content → paste
5. **Commit changes** (commit directly to the branch)

The **Checks** column will start showing results on the very next push
(2 jobs: "PHP Checks" ~2-3 min, "Frontend Build" ~1-2 min).

## What the checks do (owner-approved plan)

| Check | Blocking? |
|---|---|
| Composer validate | ✅ fails red |
| PHP 8.3 syntax lint (all .php files) | ✅ |
| Composer security audit | ✅ |
| Migration smoke test (fresh SQLite) | ✅ |
| PHPUnit — 8 existing feature tests | ✅ |
| Laravel Pint (code style) | ⚠️ warning-only |
| npm ci + Vite production build | ✅ |

Note: the first PHPUnit run in CI is also the first run ever for these tests —
if any fail for code reasons, treat that as a real finding, not a CI problem.

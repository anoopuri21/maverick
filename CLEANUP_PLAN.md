# Maverick Codebase Cleanup Plan (Safe & Non-Destructive)

> **Status: ✅ EXECUTED — 2026-09-04 (full clean, user approved "everything" scope)**
> **Goal:** Sirf junk/dead files remove karna. App ka behavior, routes, styling, content — sab kuch untouched rahega.
> **Safety:** Har deleted file git history me recoverable rahegi (working branch pe commit se pehle review).

---

## 0. Execution Log

| Commit | Phase | Files removed |
|---|---|---|
| `6997d57` | Phase 1 — planning/backlog markdown docs | 15 |
| `541a0f0` | Phase 2 — unused design images/binaries | 12 |
| `8345eaa` | Phase 3a — dead CSS/HTML/JS assets + one-off scripts | 10 |
| `83d5ff4` | Phase 3b — tooling, agent artifacts, example files (incl. `scripts/css-equivalence/`, `.claude/`, `.emergent/`, `.gitconfig`) | 174 |

**Total: 211 files removed (~6 MB).** Remaining tracked files: 936 (was 1,147).

**Post-cleanup verification:**
- ✅ Dangling-reference grep: koi deleted file ka ref remaining code me nahi mila (sirf plan record me)
- ✅ Vite production build: pass (`3 modules transformed, built in 252ms`)
- ⚠️ Full `php artisan test` locally nahi chala (sandbox me `vendor/` nahi) — **koi PHP/Blade/js/css file modified nahi hui**, sirf deletions hain
- ✅ `resources/js/app.js`, `resources/css/app.css`, `vite.config.js`, `routes/`, `app/` — zero changes

---

## 1. Project Overview

| Item | Detail |
|---|---|
| Stack | Laravel 13.8 + Filament 3 (admin CMS) + Livewire + Tailwind/Vite |
| Type | Maverick Business Academy — marketing website (public pages) + admin panel |
| Routes | ~30 public routes (`routes/web.php`) + Filament `/admin` |
| Views | 176 Blade files (`resources/views`) |
| Assets | `public/assets/css`, `public/assets/js`, `public/assets/images`, `public/css/pages`, `public/js` (Filament vendor) |
| DB | Migrations + Spatie Settings + models (Program, BlogPost, UniversityPartner, etc.) |
| Tests | 21 Feature/Unit PHP tests + 1 markdown test-plan (stale) |
| Total tracked files | 1,147 |

**Reference-check method used:** Har candidate file ka naam/rel-path pura repo me grep kiya (blade includes, `cached_asset()`, JS imports, CSS link tags, `@include`, PHP `view()` calls, markdown/docs). "Unused" ka matlab = code me kahin bhi reference nahi mila.

---

## 2. Markdown Files — Complete Inventory (25 files)

### A. JUNK / PLANNING DOCS — Remove (13 files, definite ✅)

Yeh sab AI-agent / planning / code-review / backlog files hain. **Repo ke code me inka koi reference nahi hai.** Sirf ek dusre ko ya komment me reference karte hain.

| # | File | Verdict | Reason |
|---|---|---|---|
| 1 | `BLADE_COMPLETION_BACKLOG.md` | REMOVE | Old animation refactor backlog, no code ref |
| 2 | `CURSOR_PROMPT_PD_PHASE2.md` | REMOVE | Cursor AI prompt/instruction file |
| 3 | `DUAL_MBA_BLUEPRINT.md` | REMOVE | Landing page blueprint (design doc) |
| 4 | `MBM_LANDING_CSS_OPTIMIZATION_PLAN.md` | REMOVE | CSS merge plan (work already done) |
| 5 | `MBM_LANDING_CSS_REVIEW.md` | REMOVE | One-time code review notes |
| 6 | `NAVBAR_CODE_REVIEW.md` | REMOVE | One-time code review notes |
| 7 | `PROGRAM_DETAIL_CODE_REVIEW.md` | REMOVE | One-time code review notes |
| 8 | `PROGRAM_DETAIL_UX_CHANGES_PLAN.md` | REMOVE | Old UX plan |
| 9 | `PROGRAM_DYNAMIC_NAVBAR_PLAN.md` | REMOVE | Old architecture plan (v2 exists, work done) |
| 10 | `PROGRAM_PAGE_IMPLEMENTATION_PLAN.md` | REMOVE | Old implementation plan |
| 11 | `PROGRAM_UNIVERSITY_ARCHITECTURE.md` | REMOVE | v1 architecture plan (superseded by v2 → then done) |
| 12 | `PROGRAM_UNIVERSITY_ARCHITECTURE_v2.md` | REMOVE | v2 plan (work already merged) |
| 13 | `plan.md` | REMOVE | 1-line leftover comment "Plan saved — only modify carousel…" — pure junk |

### B. STALE PLANNING / TEAM DOCS — Remove (2 files, high-confidence ✅)

| # | File | Verdict | Reason |
|---|---|---|---|
| 14 | `memory/PRD.md` | REMOVE | Old PRD + backlog; backlog items done/stale; no code ref. (Git history me safe) |
| 15 | `tests/partner-logo-sanity-test.md` | REMOVE | Manual test checklist md; real tests exist in `tests/Feature/`. No code ref |

### C. KEEP — Project/Operational Docs (7 files) ✅

| File | Why keep |
|---|---|
| `README.md` | Project entry + shared-hosting deploy instructions |
| `docs/SHARED_HOSTING.md` | Referenced by `README.md` + `.cursorrules` — production deploy checklist |
| `docs/cloudinary-guide.md` | Explains Cloudinary URL storage (site behavior docs) |
| `docs/zapier-instruction.md` | Explains live Zapier webhook integration (admin-managed) |
| `docs/zoho-instruction.md` | Explains live Zoho SMTP setup |
| `docs/mba-masters-program-specifications.md` | Source-of-truth doc for `docs/listing.pdf` data (migration uses PDF) |
| `docs/mlp-scroll-reveal-fix.md` | Documents a real JS fix with root cause |
| `docs/mlp-design-system.md` | Design system reference for MLP CSS/JS (still live code) |

### D. MD files tied to bigger junk dirs (Phase 3 me unke saath)

| File | Phase |
|---|---|
| `.claude/skills/ui-ux-pro-max/SKILL.md` | Phase 3 (with `.claude/` skill package) |
| `scripts/css-equivalence/README.md` | Phase 3 (with `scripts/css-equivalence/` harness) |

---

## 3. Images / Binary Junk Scan

**Result: Public site images almost 100% used** — `public/assets/images/**` me sirf 1 unused file hai:

### Phase 2 — Images (files)

| # | File | Size | Verdict |
|---|---|---|---|
| 1 | `public/assets/images/mba-masters-landing/class-profile-world-globe.svg` | 4 KB | REMOVE — no ref in any blade/js/css. (Inline SVG bola hua "globe" wahi runtime icon hai, is file ka nahi) |
| 2 | `docs/mlp-moodboard/page-01.png … page-09.png` (9 files) | 1.7 MB | CANDIDATE — sirf `docs/mlp-design-system.md` me aadhi-cite hain; code me 0 ref. Design/moodboard reference = UI reference material. → User confirm: remove, ya keep as design reference? |

### PDFs

| # | File | Size | Verdict |
|---|---|---|---|
| 1 | `docs/listing.pdf` | 364 KB | KEEP — `database/settings/*_sync_mba_masters_*.php` migrations ka data source |
| 2 | `docs/master-landing-page.pdf` | 452 KB | CANDIDATE — 0 refs (old design PDF) |
| 3 | `docs/sections-design-style-reuirements.pdf` | 1.9 MB | CANDIDATE — 0 refs (old design PDF) |

---

## 4. Other Dead Files Scan (CSS / JS / HTML / Scripts)

### Phase 3 — Dead assets & files (definite ✅)

| # | File | Size | Verdict | Proof |
|---|---|---|---|---|
| 1 | `public/css/pages/accreditations-new.css` | 8 KB | REMOVE | 0 refs repo-wide (accreditations page uses `accreditations.css`) |
| 2 | `public/css/pages/accreditations-v2.css` | 15 KB | REMOVE | 0 refs |
| 3 | `public/assets/css/animations.css` | 40 B | REMOVE | 0 refs, mehfile hamesha se aadha-bhara (only comment) |
| 4 | `public/assets/css/sections.css` | 66 KB | REMOVE | 0 refs — old sections CSS; current styles `main.css`/page CSS me hain |
| 5 | `public/assets/css/blog.css` | 32 KB | REMOVE | 0 refs — blog use karta hai `public/css/pages/blog.css` |
| 6 | `public/assets/css/news.css` | 39 KB | REMOVE | 0 refs — news use karta hai `public/css/pages/news.css` |
| 7 | `public/html-index.html` | 103 KB | REMOVE | 0 refs — pre-render/exported static page copy |
| 8 | `public/favicon.ico` | 0 B | REMOVE | 0-refs, empty file (favicon kahin link nahi hai) |
| 9 | `resources/views/welcome.blade.php` | — | REMOVE | Default Laravel view; no route uses it |
| 10 | `scripts/apply-saves-settings-groups.php` | — | REMOVE | 0 refs — one-off dev script |

### Phase 3 — Tooling / agent artifacts (CANDIDATE — user confirm)

| # | Path | Size | Verdict | Note |
|---|---|---|---|---|
| 1 | `scripts/css-equivalence/` (full dir incl. 110 gzip baselines, node package, fixtures) | 4.6 MB | REMOVE RECOMMENDED | CSS merge ka one-time harness; app ya README me koi ref nahi. Iska ref sirf wahi junk MDs (Phase 1) me hai |
| 2 | `.claude/skills/ui-ux-pro-max/` (SKILL.md + 23 CSV + 3 py) | 556 KB | REMOVE RECOMMENDED | Generic AI-agent UI/UX skill package; project code me 0 ref. (Yeh "skill" agent ke liye hai, website ke liye nahi) |
| 3 | `.emergent/` (cron shell scripts + yml) | 21 KB | CONFIRM | Emergent agent ka cron tooling; app me 0 ref. Agar aap Emergent use karte ho to keep |
| 4 | `.gitconfig` | — | REMOVE CANDIDATE | Repo me commit hua local git user identity (`emergent-agent-e1`) — har dev ki apni identity hoti hai; repo me iska koi kaam nahi |
| 5 | `tests/ui-testing-local/run-tests.sh` | — | CONFIRM | Local UI test helper; 0 refs, but kabhi kaam aayega |
| 6 | `resources/views/filament/admin/logo.blade.php` | — | CONFIRM | Panel provider me koi ref nahi mila; pehle manually confirm karo (Filament auto-detect nahi karta) |
| 7 | `tests/Feature/ExampleTest.php` + `tests/Unit/ExampleTest.php` | — | OPTIONAL | Laravel defaults; harmless, standard practice. Remove optional |

### NOT part of this cleanup (no code change rule)
- `app/Console/Commands/*` — kuch one-off migration scripts ho sakte hain (e.g. `ImportLegacyBlogs`, `MigrateBlogsToInsights`) — **code change hai**, alag review phase rakhna chahiye.
- Duplicate-looking migrations/settings — code, touch nahi karenge.
- `public/js/filament/*`, `public/css/filament/*` — published vendor assets, **keep**.

---

## 5. Safe Removal Plan (Phased)

> Har phase alag commit hoga, taaki revert easy ho. Har phase ke baad verify:

```
Verify recipe (har phase ke baad):
  1. git status / git diff --stat        → sirf expected files removed
  2. grep -r "removed-name" .            → koi dangling ref nahi
  3. php artisan route:list               → routes intact
  4. php artisan test (if vendor present) → tests pass
  5. npm run build                       → vite build pass
```

### Phase 1 — Markdown (definite 15 files + 2 candidates if approved)
- Delete: 13 junk MDs (section 2A) + `memory/PRD.md` + `tests/partner-logo-sanity-test.md`
- Do NOT delete: `README.md`, `docs/*.md` (7 operational docs)
- Re-check: `grep -rn "BLADE_COMPLETION_BACKLOG\|PROGRAM_UNIVERSITY_ARCHITECTURE" .` → only git history results
- ~118 KB freed

### Phase 2 — Images / design binaries
- Delete: `class-profile-world-globe.svg`
- Confirm: `docs/mlp-moodboard/` (9 PNG), `docs/master-landing-page.pdf`, `docs/sections-design-style-reuirements.pdf`
- Keep: `docs/listing.pdf` (migration data source), all `public/assets/images/**`
- Safety: sabhi remaining public images ka ref re-verify (`grep -rl "filename" resources/ public/assets/js`)

### Phase 3 — Dead assets + tooling
- Definite: 10 files (section 4 first table) — CSS stale copies, html-index, empty favicon, welcome view, one-off script
- Confirm: `scripts/css-equivalence/`, `.claude/skills/ui-ux-pro-max/`, `.emergent/`, `.gitconfig`, `tests/ui-testing-local/run-tests.sh`, `filament/admin/logo.blade.php`
- Total potential: **~5.5 MB + 120+ files freed**
- Safety: `public/` me koi file delete karna = production me 404 ka risk. Isliye **Phase 3 ke baad sab pages ko manual smoke test** (home, programs, blog, news, landing, admin) + `route:list` compare

### Phase 4 — Final verification (no bug gate)
1. `git diff --stat` — sirf deletions
2. Full test suite run (`php artisan test`)
3. Vite build (`npm run build`)
4. All routes smoke-test (GET 200) — local dev server
5. Browser check: 5 key pages (Home, Program Detail, MBA Landing, Blog, Admin login)

---

## 6. What We Are NOT Doing (safety boundaries)

- ❌ Koi PHP/Blade/JS/CSS **code logic change** nahi
- ❌ Koi route, controller, model, migration, seeder change nahi
- ❌ Koi DB setting/content change nahi
- ❌ Koi vendor/node_modules/public build change nahi
- ❌ `docs/listing.pdf`, `README.md`, `docs/` operational MDs delete nahi
- ✅ Sab kuch sirf `git rm` (delete) hoga — `git log` se kabhi bhi wapas aa sakta hai

---

## 7. Expected Result

| Metric | Before | After (planned) |
|---|---|---|
| Root-level junk MD files | 13 | 0 (README only) |
| Total tracked files | 1,147 | ~1,010 (Phase 1–3 ke baad) |
| Freed space | — | ~6 MB |
| Runtime behavior | — | **Unchanged** |

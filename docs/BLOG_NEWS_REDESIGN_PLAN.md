# Blog & News Pages — Redesign Plan (v2)

**Branch:** `feature/global-bachelors-pathway`
**Date:** 2026-08-05
**Status:** Final — approved by user

---

## Design Decisions (confirmed)

| # | Decision | Choice |
|---|---|---|
| 1 | Blog/News hero background | Our Story hero pattern — custom hero with cloudinary image from Admin Panel settings |
| 2 | News hero title markup | `News & <em class="news-masthead__title-em">Announcements</em>` |
| 3 | Sidebar | Sidepanel on both listing pages: search bar (top) → category section → top 10 tags section |
| 4 | News ticker | GSAP horizontal scroll |
| 5 | Blog TOC | Collapsible toggle (existing component behavior) |
| 6 | Progress bar JS | Single `blog.js` for both blog + news detail pages |

---

## Architecture

### New settings classes (Admin Panel managed)

| Class | File | Group | Fields |
|---|---|---|---|
| `BlogHeroSettings` | `app/Settings/BlogHeroSettings.php` | `blog_hero` | `eyebrow`, `heading`, `description`, `image_url` |
| `NewsHeroSettings` | `app/Settings/NewsHeroSettings.php` | `news_hero` | `eyebrow`, `heading`, `description`, `image_url` |

These mirror `OurStoryHeroSettings` — Spatie Settings, editable via Filament admin.

### Controller updates

| Controller | Change |
|---|---|
| `BlogController::index()` | Add `$blogHero` from `app(BlogHeroSettings::class)`, add `$topTags` from Insight tags |
| `NewsController::index()` | Add `$newsHero` from `app(NewsHeroSettings::class)`, add `$topTags` from Insight tags |

### Top 10 tags logic
```php
$allTags = Insight::published()->pluck('tags')->flatten()->filter()->values();
$topTags = $allTags->countBy()->sortDesc()->take(10)->keys();
```
使用的标签来自 `Insight` 模型的 `tags` 数组字段.

---

## Page Layouts

### Blog Listing (`/blogs`)

```
┌─────────────────────────────────────────────────┐
│  [OS-style CINEMATIC HERO]                       │
│  eyebrow: BLOGS                                  │
│  heading: (from BlogHeroSettings)                │
│  description: (from BlogHeroSettings)            │
│  bg: image_url from settings                     │
│  → gradient, noise, shapes, particles,           │
│    scanline, corners                             │
├─────────────────────────────────────────────────┤
│  ┌─────────────────────────┬───────────────────┐ │
│  │  MAIN CONTENT (left)    │  SIDEPANEL(right) │ │
│  │                         │                   │ │
│  │  [FEATURED POST]        │  [SEARCH BAR]     │ │
│  │  large card             │  [CATEGORIES]     │ │
│  │                         │  All | Business   │ │
│  │  [ARTICLE GRID]         │  Strategy | ...   │ │
│  │  3-col card grid        │                   │ │
│  │  (blog-card)            │  [TOP TAGS]       │ │
│  │                         │  tag1 tag2 ...    │ │
│  │  [PAGINATION]           │                   │ │
│  └─────────────────────────┴───────────────────┘ │
├─────────────────────────────────────────────────┤
│  [FINAL CTA]                                     │
└─────────────────────────────────────────────────┘
```

### News Listing (`/news`)

```
┌─────────────────────────────────────────────────┐
│  [OS-style CINEMATIC HERO]                       │
│  eyebrow: NEWS                                   │
│  heading: News & <em class="news-masthead__     │
│            title-em">Announcements</em>          │
│  description: (from NewsHeroSettings)            │
│  bg: image_url from settings                     │
├─────────────────────────────────────────────────┤
│  ┌─────────────────────────┬───────────────────┐ │
│  │  MAIN CONTENT (left)    │  SIDEPANEL(right) │ │
│  │                         │                   │ │
│  │  [NEWS TICKER]          │  [SEARCH BAR]     │ │
│  │  GSAP horizontal scroll │  [CATEGORIES]     │ │
│  │  latest 5 headlines     │  All | Announcements│
│  │                         │                   │ │
│  │  [FEATURED STORY]       │  [TOP TAGS]       │ │
│  │  split layout           │  tag1 tag2 ...    │ │
│  │                         │                   │ │
│  │  [ARTICLE LIST]         │                   │ │
│  │  newspaper rows         │                   │ │
│  │                         │                   │ │
│  │  [PAGINATION]           │                   │ │
│  └─────────────────────────┴───────────────────┘ │
├─────────────────────────────────────────────────┤
│  [FINAL CTA]                                     │
└─────────────────────────────────────────────────┘
```

### Blog Detail (`/{slug}`)

```
┌─────────────────────────────────────────────────┐
│  [READING PROGRESS BAR] (fixed)                  │
├─────────────────────────────────────────────────┤
│  [SHORT CINEMATIC HERO] — ~50vh                 │
│  eyebrow: BLOGS                                  │
│  title: post title                               │
│  subtitle: post excerpt                          │
│  (solid dark bg — no image for focus)           │
├─────────────────────────────────────────────────┤
│  [ARTICLE HEADER] (white bg)                     │
│  category pill + author meta + featured image    │
├──────────────────────┬──────────────────────────┤
│  ARTICLE BODY (70%)  │  SIDEBAR (30%)           │
│                      │  [TOC] collapsible       │
│  {!! content !!}     │  [SHARE BAR]            │
│  h2, h3, p styling  │                          │
├──────────────────────┴──────────────────────────┤
│  [RELATED POSTS] — 3 card grid                   │
├─────────────────────────────────────────────────┤
│  [FINAL CTA]                                     │
└─────────────────────────────────────────────────┘
```

### News Detail (`/{slug}`)

```
┌─────────────────────────────────────────────────┐
│  [READING PROGRESS BAR] (fixed)                  │
├─────────────────────────────────────────────────┤
│  [SHORT CINEMATIC HERO] — ~50vh                 │
│  eyebrow: NEWSROOM BULLETIN                      │
│  title: article title                            │
│  (solid dark bg)                                 │
├─────────────────────────────────────────────────┤
│  [EDITORIAL MASTHEAD] (white bg)                │
│  badge row + title + excerpt + byline            │
├─────────────────────────────────────────────────┤
│  [FEATURED IMAGE] (full width)                   │
├──────────────────────┬──────────────────────────┤
│  ARTICLE BODY (70%)  │  SIDEBAR (30%)           │
│                      │  [SHARE BAR] (sticky)    │
│  {!! content !!}     │                          │
│                      │                          │
├──────────────────────┴──────────────────────────┤
│  [EDITORIAL SIGNATURE] ─── MBA — Newsroom ───   │
├─────────────────────────────────────────────────┤
│  [MORE UPDATES] — compact row list              │
├─────────────────────────────────────────────────┤
│  [FINAL CTA]                                     │
└─────────────────────────────────────────────────┘
```

---

## Files to Create/Modify

### New files
| File | Purpose |
|---|---|
| `app/Settings/BlogHeroSettings.php` | Blog hero settings (Spatie) |
| `app/Settings/NewsHeroSettings.php` | News hero settings (Spatie) |
| `public/css/pages/blog.css` | Blog listing + detail CSS |
| `public/css/pages/news.css` | News listing + detail CSS |
| `public/assets/js/blog.js` | Reading progress + TOC tracking + detail entrance animations |

### Modified files
| File | Change |
|---|---|
| `app/Http/Controllers/BlogController.php` | Add `$blogHero`, `$topTags` |
| `app/Http/Controllers/NewsController.php` | Add `$newsHero`, `$topTags` |
| `resources/views/blogs/index.blade.php` | Full rewrite |
| `resources/views/blogs/show.blade.php` | Full rewrite |
| `resources/views/news/index.blade.php` | Full rewrite |
| `resources/views/news/show.blade.php` | Full rewrite |

---

## Test Compliance

| Test | Assertion | Satisfaction |
|---|---|---|
| `BlogTest::test_blog_listing_page` | sees "Latest Articles & Insights", sees "Unlocking Global Leadership" | hero heading + card title |
| `BlogTest::test_blog_search` | `/blogs?search=Venture` sees "Demystifying Venture Capital" | search form + server filter |
| `BlogTest::test_blog_detail_page` | sees title, "Dr. Elizabeth Vance", "Table of Contents", H2 | masthead + TOC + content |
| `BlogTest::test_blog_detail_not_found` | 404 | route model binding |
| `BlogTest::test_blog_falls_back_to_branded_cover_when_no_image` | sees `blog-thumb--fallback` | `x-blog.thumbnail` already does this |
| `NewsTest::test_news_listing_page` | sees `News &amp; <em class="news-masthead__title-em">Announcements</em>`, both article titles | hero title markup + article rows |
| `NewsTest::test_news_detail_page` | sees title, "Academic Council", "Institutional Breakthrough" | masthead + content |

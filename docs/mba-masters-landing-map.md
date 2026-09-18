# MBA Masters Landing Page - Complete Dependency Map
### URL: `/online-mba-masters-uae`

> Ye document is single URL ke liye pura project ka mini-overview hai. Har baar pura codebase scan karne ki jarurat nahi - yahi file se samajh aa jayega ki kaunse files, controllers, settings, views, assets aur admin panels involved hai.

---

## 1. URL & Routing

**File:** `routes/web.php`

```php
Route::get('/online-mba-masters-uae', [PageController::class, 'mbaMastersLanding'])
    ->name('mba-masters-landing');

Route::post('/online-mba-masters-uae/enquire', [MbaMastersLandingController::class, 'enquire'])
    ->middleware('throttle:5,1')
    ->name('mba-masters-landing.enquire');
```

- **GET route name:** `mba-masters-landing` -> canonical URL generation ke liye use hota hai (`route('mba-masters-landing', absolute: true)`)
- **POST route:** enquiry form submit, throttled 5 req/min
- **Preview URL in Filament:** har Filament chunk page me `url('/online-mba-masters-uae')` button hai

---

## 2. Controllers (Data Lane + Page Banane)

### 2.1 `app/Http/Controllers/PageController.php` - `mbaMastersLanding()`
**Main page builder.** Ye method:

1. Saare Spatie Settings ko `safe_settings()` helper se load karta hai
2. `settings_array()` se repeater fields ko normalize karta hai
3. 3 Models se logos fetch karta hai:
   - `PartnerLogo` where `type=alumni` (employer ribbon)
   - `UniversityPartner` where `is_active + logo_url not null`
   - `PartnerLogo` where `type in [accreditation, recognition]`
4. Video testimonials ko homepage wale `testimonials.js` ke compatible format me convert karta hai (`testimonialsJson`)
5. View ko return karta hai: `view('pages.mba-masters-landing', [...])`

**Variables jo view ko pass hote hai:**
```
hero, trust, overview, why, journey, mba, masters, fees,
class, career, alumni, videoTestimonials, testimonialsJson,
partners, testimonials, compare, faq, final, seo, site,
alumniLogos, universityPartnerLogos, accreditationLogos
```

### 2.2 `app/Http/Controllers/MbaMastersLandingController.php`
- **Method:** `enquire(MbaMastersLandingEnquiryRequest $request, FormMailer $formMailer)`
- Honeypot check: `website` field filled => fake success
- Qualification & timeline mapping (slug -> human readable)
- `FormMailer->send([...], 'MBA/Master\'s landing enquiry: {name}', ['reply_to'=>email])`
- Session flash: `success` / `error`

### 2.3 Related Shared Controllers (indirect)
- `ContactController` / `ProgramController` / `BlogController` - same layout use karte hai but is page se direct link nahi

---

## 3. Request Validation

**File:** `app/Http/Requests/MbaMastersLandingEnquiryRequest.php`

```php
name: required|string|max:100
email: required|email|max:150
phone: required|string|max:30
country: nullable|string|max:100
program: nullable|string|max:120 (MBA, Executive MBA, Master's / MSc, LLM, Not sure)
specialization: nullable (legacy, not in current form but allowed)
qualification: nullable|in[high-school, diploma, bachelor, master, other]
start_timeline: nullable|in[1-3-months, 3-6-months, more-than-6-months, not-decided]
website: nullable|string|max:100 (honeypot)
```

---

## 4. Settings - Spatie Laravel Settings (Data Source)

> Har section ka content DB me `settings` table me JSON ke form me stored hai, group-wise. Filament admin se edit hota hai.

**Location:** `app/Settings/MbaMasters*.php` (19 files)

| Group Name (DB) | Settings Class | Content | Admin Filament Page |
|-----------------|----------------|---------|---------------------|
| `mba_masters_hero` | `MbaMastersHeroSettings` | eyebrow, headline, subheading, bg_image, 3 CTAs, form_title | ManageHeroTrust |
| `mba_masters_trust` | `MbaMastersTrustSettings` | label, quote, stats[] (value+label) | ManageHeroTrust |
| `mba_masters_overview` | `MbaMastersOverviewSettings` | index, label, heading, intro, items[], ctas, plate_image | ManagePrograms |
| `mba_masters_why` | `MbaMastersWhySettings` | index, label, heading, intro, chapters[] (title+text) | ManagePrograms |
| `mba_masters_journey` | `MbaMastersJourneySettings` | steps[], cta (currently view me commented) | ManagePrograms |
| `mba_masters_mba` | `MbaMastersMbaSettings` | label, heading, intro, stage_image, tabs[] (university logos, campuses) | ManagePrograms |
| `mba_masters_masters` | `MbaMastersMastersSettings` | universities[], trending[], trending_title (with | for two-tone) | ManagePrograms |
| `mba_masters_fees` | `MbaMastersFeesSettings` | heading, intro, note, stage_image, rows[], ctas | ManagePrograms |
| `mba_masters_class` | `MbaMastersClassSettings` | label, heading, intro, audience, metrics[], regions[], industries[] (with image) | ManageAudience |
| `mba_masters_career` | `MbaMastersCareerSettings` | label, heading, intro, stories[] (name, country, program, prev_role, curr_role, quote, portrait) | ManageAudience |
| `mba_masters_video_testimonials` | `MbaMastersVideoTestimonialsSettings` | label, heading, intro, videos[] (video_url, thumbnail, name, role, category) | ManageAudience |
| `mba_masters_alumni` | `MbaMastersAlumniSettings` | index, label, heading, intro, trust_line (logos PartnerLogo se) | ManageProof |
| `mba_masters_partners` | `MbaMastersPartnersSettings` | label, heading, intro, trust_line (logos UniversityPartner se) | ManageProof |
| `mba_masters_testimonials` | `MbaMastersTestimonialsSettings` | items[] (name, role, quote, photo) | ManageProof |
| `mba_masters_compare` | `MbaMastersCompareSettings` | col_online, col_traditional, rows[] (criterion, online, traditional), cta (commented) | ManageProof |
| `mba_masters_faq` | `MbaMastersFaqSettings` | label, heading, items[] (question, answer) | ManageFaqClose |
| `mba_masters_final` | `MbaMastersFinalSettings` | heading, intro, plate_image, ctas, show_form, form_title | ManageFaqClose |
| `mba_masters_seo` | `MbaMastersSeoSettings` | meta_title, meta_description, keywords, canonical, robots, og_*, twitter_*, schema_json, custom_head/body_scripts | ManageSeo |
| `mba_masters_learning` | `MbaMastersLearningSettings` | legacy / unused? (plate_image, points) - code me use nahi but migration me hai | - |

**Helper usage:**
- `safe_settings(Class::class)` - request memoized, fallback on failure
- `settings_array($value)` - ensure array_values
- `settings_media_url($settingsRow, 'field')` - resolves URL or asset_id -> MediaAsset

**Migrations:**
`database/settings/` me 20+ files:
- `2026_08_23_190000_create_mba_masters_landing_settings.php` (hero, trust, seo)
- `2026_08_23_200000_create_mba_masters_overview_settings.php`
- ... har group ke liye ek
- `2026_09_04_000001_add_trending_to_mba_masters_masters_settings.php`
- `2026_09_04_000002_add_trending_title_to_mba_masters_masters_settings.php`

---

## 5. Models (Logos ke liye)

**File:** `app/Models/PartnerLogo.php`
- Fields: name, logo_url, type (alumni/accreditation/recognition/award), sort_order, is_active, logo_url_asset_id
- Trait: `HasMediaAssets` -> `getMediaUrl()`
- Used for: alumni ribbon + accreditation slider

**File:** `app/Models/UniversityPartner.php`
- Fields: name, slug, country, logo_url, campus_image_url, sort_order, is_active, etc.
- Used for: partners section logos
- Method: `programDetailImageUrl()`

**File:** `app/Models/MediaAsset.php`
- Cloudinary asset table, `url` field
- `settings_media_url` helper isse resolve karta hai jab `*_asset_id` set ho

---

## 6. Views - Blade Templates

### 6.1 Main Layout
**File:** `resources/views/pages/mba-masters-landing.blade.php`

```blade
@extends('layouts.app')
@section('title', $seo->meta_title)
@push('head') @include('partials.seo-meta', ['seo'=>$seo]) + FAQ schema
@push('styles') mba-masters-landing.css
@section('content')
  @include('pages.mba-masters-landing.hero')
  @include('pages.mba-masters-landing.trust')
  @include('pages.mba-masters-landing.overview')
  @include('pages.mba-masters-landing.why')
  {{-- journey commented --}}
  @include('pages.mba-masters-landing.mba')
  @include('pages.mba-masters-landing.masters')
  @include('pages.mba-masters-landing.class-2025')  // static hardcoded chapter
  @include('sections.accreditations')                // shared
  @include('pages.mba-masters-landing.class-snapshot')
  @include('pages.mba-masters-landing.fees')
  @include('pages.mba-masters-landing.career')
  @include('pages.mba-masters-landing.alumni')
  @include('pages.mba-masters-landing.video-testimonials')
  @include('pages.mba-masters-landing.partners')
  @include('pages.mba-masters-landing.video-proof')  // hardcoded YT: youtu.be/4p0rsCEljgo
  @include('pages.mba-masters-landing.testimonials')
  {{-- compare commented --}}
  @include('pages.mba-masters-landing.faq')
  @include('pages.mba-masters-landing.final')
@endsection
@push('scripts') 10 JS files
```

### 6.2 Section Partials (19 files)

**Location:** `resources/views/pages/mba-masters-landing/`

| File | Settings Used | Extra Logic |
|------|---------------|-------------|
| `hero.blade.php` | `$hero`, `$site` (whatsapp) | Includes `partials/enquire-form.blade.php`, bg image via `mlp_image_url`, CTAs |
| `trust.blade.php` | `$trust` (stats, quote, label) | Signal atlas animation, count-up via `data-mlp-count`, bg `trust-bg.jpg` |
| `overview.blade.php` | `$overview` (items, plate_image, ctas) | Blueprint design, `MlpProse::html()` |
| `why.blade.php` | `$why` (chapters) | Full-bleed scroll chapters, icons map: flexible→calendar, academic→graduation-cap, etc. |
| `journey.blade.php` | `$journey` (steps, cta) | Currently commented in main |
| `mba.blade.php` | `$mba` (tabs, stage_image) | Tabs for MBA specializations, logos, campuses |
| `masters.blade.php` | `$masters` (universities, trending, trending_title) | Bar chart for trending specializations, two-tone title split by `|` |
| `class-2025.blade.php` | **Hardcoded** (no settings) | Static SVG blueprint for MBA Class of 2025 |
| `class-snapshot.blade.php` | `$class` (metrics) maybe | Snapshot metrics |
| `class.blade.php` | `$class` (metrics, regions, industries) | Class profile |
| `fees.blade.php` | `$fees` (rows, note, ctas, stage_image) | Fee comparison table |
| `career.blade.php` | `$career` (stories) | Dossiers with portrait, prev→curr role |
| `alumni.blade.php` | `$alumni`, `$alumniLogos` (PartnerLogo) | Moving ribbon, fallback hardcoded 6 logos if DB empty |
| `video-testimonials.blade.php` | `$videoTestimonials`, `$testimonialsJson` | Scroll row, `window.testimonialsData` injected, uses `testimonials.js` |
| `partners.blade.php` | `$partners`, `$universityPartnerLogos` | University partner logos grid |
| `video-proof.blade.php` | **Hardcoded YT** `https://youtu.be/4p0rsCEljgo` | Inline YT player with `data-inline-youtube` |
| `testimonials.blade.php` | `$testimonials` (items) | Luxury testimonials |
| `compare.blade.php` | `$compare` (rows, cols) | Commented in main |
| `faq.blade.php` | `$faq` (items) | Accordion FAQ |
| `final.blade.php` | `$final` (heading, intro, plate_image, ctas, show_form) | Final CTA + enquire form reuse |

**Sub-partial:**
- `partials/enquire-form.blade.php` - form with honeypot, fields: name, email, phone, country, program (select), start_timeline, submit to `mba-masters-landing.enquire`

**Shared Section:**
- `sections/accreditations.blade.php` - uses `$accreditationLogos` + `$homepageChrome` (label, heading, trust), logo slider

**Layout Dependencies:**
- `layouts/app.blade.php` - includes navbar, footer, preloader, cursor, Lenis, GSAP, ScrollTrigger, lucide icons, main.css, responsive.css, plus page-specific CSS/JS stacks
- `partials/navbar.blade.php`, `partials/footer.blade.php`
- `partials/seo-meta.blade.php` - OG, Twitter, canonical, schema, custom scripts

---

## 7. Filament Admin (Control Panel)

**Location:** `app/Filament/Pages/MbaMastersLanding/`

> Admin me `Landing Pages` group ke andar 6 pages hai. Har page ek chunk edit karta hai.

| File | Navigation Label | Sort | Edits Which Settings | View |
|------|------------------|------|----------------------|------|
| `ManageHeroTrust.php` | MBA Masters — Hero & Trust | 1 | `MbaMastersHeroSettings`, `MbaMastersTrustSettings` | `filament.pages.mba-masters-landing.chunk` |
| `ManagePrograms.php` | MBA Masters — Programs | 2 | `Overview`, `Why`, `Journey`, `Mba`, `Masters`, `Fees` | same |
| `ManageAudience.php` | MBA Masters — Audience | 3 | `Class`, `Career`, `VideoTestimonials` | same |
| `ManageProof.php` | MBA Masters — Proof | 4 | `Alumni`, `Partners`, `Testimonials`, `Compare` | same |
| `ManageFaqClose.php` | MBA Masters — FAQ & Close | 5 | `Faq`, `Final` | same |
| `ManageSeo.php` | MBA Masters — SEO | 6 | `Seo` | same |

**Concern Trait:**
- `Concerns/ManagesMbaMastersChunk.php` - provides `chunkHint()`, `richEditor()`, `getFormStateOrNotify()`, `notifySaved()`, uses `HydratesRepeaterMediaFields`
- `Filament/Concerns/SavesSettingsGroups.php` - generic save logic for multiple settings groups
- `Filament/Concerns/HydratesRepeaterMediaFields.php` - hydrates image fields in repeaters
- `Filament/Forms/Components/MediaPicker.php` - Cloudinary picker, stores `*_asset_id` + URL

**Common Filament View:**
- `resources/views/filament/pages/mba-masters-landing/chunk.blade.php` - simple form + Save + Preview button (`/online-mba-masters-uae`)

**Media Upload Paths (Cloudinary folders):**
- `mba-masters-landing` (hero bg)
- `mba-masters-landing/overview` (plate)
- `mba-masters-landing/mba`, `mba-masters-landing/mba/logos`, `mba-masters-landing/mba/campuses`
- `mba-masters-landing/masters`, `mba-masters-landing/masters/logos`, `mba-masters-landing/masters/campuses`
- `mba-masters-landing/fees`, `mba-masters-landing/final`, `mba-masters-landing/class/industries`
- `mba-masters-landing/career/portraits`, `mba-masters-landing/testimonials`, `mba-masters-landing/seo`

---

## 8. Assets - CSS & JS

**CSS:**
- `public/assets/css/pages/mba-masters-landing.css` (5888 lines) - main polished design system
- Shared: `assets/css/main.css`, `assets/css/responsive.css`
- Docs: `docs/mlp-design-system.md` (design system), `docs/mlp-scroll-reveal-fix.md` (mid-page refresh fix)

**JS (all in `public/assets/js/pages/`):**

| File | Purpose |
|------|---------|
| `mba-masters-landing.js` | Core motion primitives, `MLPMotion` global, `reveal()`, `slideReveal()`, `whenInView()`, rescue queue for scroll |
| `mba-masters-hero-assembly.js` | Hero assembly animation |
| `mba-masters-trust.js` | Trust stats count-up (`data-mlp-count`), signal atlas |
| `mba-masters-overview.js` | Overview blueprint interactions |
| `mba-masters-accreditations.js` | Accreditation slider (shared but page-specific) |
| `mba-masters-archive.js` | Archive/alumni ribbon, career dossiers |
| `mba-masters-video-proof.js` | Inline YouTube player (`data-inline-youtube`) |
| `mba-masters-class-topics.js` | Class topics / industries interaction |
| `mba-masters-testimonials.js` | Testimonials closing animation? (check) |
| `mba-masters-closing.js` | Final CTA / FAQ interactions |
| `mba-masters-polish.js` | Polish refinements |

**Shared JS (from layout):**
- `assets/js/main.js`, `navigation.js`, `animations-utils.js`
- `assets/js/testimonials.js` - drives `video-testimonials` section using `window.testimonialsData`
- `assets/js/scroll-controls.js`, `faculty-insights-toggle.js`
- CDN: GSAP 3.12.5, ScrollTrigger, ScrollToPlugin, Lenis 1.0.42, Lucide 0.468.0

---

## 9. Services & Helpers

**Services:**
- `app/Services/FormMailer.php` - sends email via Zoho or default mailer, recipient from `ZohoSettings` or `SiteSettings` or fallback `admissions@mbalondon.org.uk`, uses `GenericFormMail` mailable
- `app/Services/CloudinaryService.php` - image upload

**Helpers (`app/helpers.php`):**
- `mlp_image_url($path, ['w'=>1600])` - Cloudinary f_auto,q_auto,w_{n} optimization
- `media_url($path, $fallback)` - absolute vs relative handling, `cached_asset()` wrapper
- `settings_media_url($item, $field)` - resolves from URL or `*_asset_id` via MediaAsset model
- `cached_asset($path)` - versioned by mtime `?v=`
- `youtube_video_id()`, `youtube_embed_url()`, `youtube_thumbnail_url()` - YT parsing
- `settings_array()` - `array_values` wrapper
- `safe_settings()` - memoized per request, fallback object on error
- `settings_fallback()` - creates anonymous object from default property values

**Support:**
- `app/Support/MlpProse.php` - `html()` method: if contains `<` return as is, else wrap in `<p>` with escaped
- `app/Support/PublicContentCache.php` - not used for MLP (direct queries), but used for other pages

**Mail:**
- `app/Mail/GenericFormMail.php` - generic form mail

---

## 10. Site-Wide Dependencies

**SiteSettings (`app/Settings/SiteSettings.php`):**
- `whatsapp_number` - used for sticky WhatsApp button + floating button (but hidden on this page for sticky version)
- `email`, etc.

**ZohoSettings:**
- `default_recipient`, `username`, `from_name`, `enabled`, `reply_to` - for FormMailer

**HomepageChromeSettings:**
- `accred_label`, `accred_heading_line1/2`, `accred_subtitle`, `accred_trust` - used in `sections/accreditations`

---

## 11. Data Flow Diagram

```
Browser GET /online-mba-masters-uae
  -> routes/web.php (name: mba-masters-landing)
  -> PageController@mbaMastersLanding()
     -> safe_settings() for 18 groups (from DB settings table, cached per request)
     -> settings_array() normalization
     -> PartnerLogo::where(type=alumni) -> $alumniLogos
     -> UniversityPartner::where(is_active + logo_url) -> $universityPartnerLogos
     -> PartnerLogo::where(type=accreditation/recognition) -> $accreditationLogos
     -> Build $testimonialsJson from videoTestimonials videos (youtube_thumbnail_url + youtube_embed_url)
     -> seo canonical = route('mba-masters-landing')
  -> view: pages.mba-masters-landing
     -> layout: layouts.app (navbar, footer, GSAP, Lenis, lucide)
     -> partial: seo-meta (OG, Twitter, canonical, schema, FAQ schema from $faq)
     -> sections: hero (form), trust (stats), overview, why, mba, masters, class-2025 (hardcoded), accreditations, class-snapshot, fees, career, alumni (ribbon), video-testimonials (testimonials.js), partners, video-proof (hardcoded YT), testimonials, faq, final (form again)
     -> assets: mba-masters-landing.css + 10 JS files
     -> sticky: WhatsApp (from $site->whatsapp_number) + Apply Now (#mlp-enquire)

Browser POST /online-mba-masters-uae/enquire
  -> throttle:5,1
  -> MbaMastersLandingEnquiryRequest validation
  -> MbaMastersLandingController@enquire
     -> honeypot check (website field)
     -> map qualification & timeline slugs to human readable
     -> FormMailer->send([...fields], subject, ['reply_to'=>email])
        -> ZohoSettings / SiteSettings for recipient & from
        -> Mail::mailer('zoho') if enabled else default
     -> back() with flash success/error + old input
```

---

## 12. URL Generation Points

- **Canonical:** `route('mba-masters-landing', absolute: true)` in PageController (fallback if seo canonical empty)
- **OG URL:** `url()->current()` or `$seo->canonical_url` in `seo-meta.blade.php`
- **Enquiry Form Action:** `route('mba-masters-landing.enquire')` in `enquire-form.blade.php`
- **Filament Preview:** `url('/online-mba-masters-uae')` in `chunk.blade.php`
- **Internal CTAs:** Most CTAs default to `#mlp-enquire` (anchor to form), editable via settings (`cta_primary_url` etc.)
- **WhatsApp:** `https://wa.me/{preg_replace('/\D+/', '', $site->whatsapp_number)}`

---

## 13. Quick Reference - File List

**Core Files (must know):**
```
routes/web.php
app/Http/Controllers/PageController.php (373-465)
app/Http/Controllers/MbaMastersLandingController.php
app/Http/Requests/MbaMastersLandingEnquiryRequest.php
resources/views/pages/mba-masters-landing.blade.php
resources/views/pages/mba-masters-landing/*.blade.php (19 files)
resources/views/pages/mba-masters-landing/partials/enquire-form.blade.php
resources/views/sections/accreditations.blade.php
resources/views/layouts/app.blade.php
resources/views/partials/seo-meta.blade.php, navbar.blade.php, footer.blade.php
```

**Settings (19):**
```
app/Settings/MbaMastersHeroSettings.php
MbaMastersTrustSettings.php
MbaMastersOverviewSettings.php
MbaMastersWhySettings.php
MbaMastersJourneySettings.php
MbaMastersMbaSettings.php
MbaMastersMastersSettings.php
MbaMastersFeesSettings.php
MbaMastersClassSettings.php
MbaMastersCareerSettings.php
MbaMastersAlumniSettings.php
MbaMastersPartnersSettings.php
MbaMastersTestimonialsSettings.php
MbaMastersCompareSettings.php
MbaMastersFaqSettings.php
MbaMastersFinalSettings.php
MbaMastersSeoSettings.php
MbaMastersVideoTestimonialsSettings.php
MbaMastersLearningSettings.php (legacy)
```

**Filament Admin (6 + 1 trait):**
```
app/Filament/Pages/MbaMastersLanding/ManageHeroTrust.php
ManagePrograms.php
ManageAudience.php
ManageProof.php
ManageFaqClose.php
ManageSeo.php
Concerns/ManagesMbaMastersChunk.php
resources/views/filament/pages/mba-masters-landing/chunk.blade.php
```

**Models:**
```
app/Models/PartnerLogo.php
app/Models/UniversityPartner.php
app/Models/MediaAsset.php
```

**Assets:**
```
public/assets/css/pages/mba-masters-landing.css
public/assets/js/pages/mba-masters-landing.js
mba-masters-hero-assembly.js
mba-masters-trust.js
mba-masters-overview.js
mba-masters-accreditations.js
mba-masters-archive.js
mba-masters-video-proof.js
mba-masters-class-topics.js
mba-masters-testimonials.js
mba-masters-closing.js
mba-masters-polish.js
public/assets/js/testimonials.js (shared)
```

**DB Migrations:**
```
database/settings/2026_08_23_190000_create_mba_masters_landing_settings.php
2026_08_23_200000_create_mba_masters_overview_settings.php
2026_08_23_201000_create_mba_masters_why_settings.php
2026_08_23_202000_create_mba_masters_journey_settings.php
2026_08_23_203000_create_mba_masters_mba_settings.php
2026_08_23_204000_create_mba_masters_masters_settings.php
2026_08_23_205000_create_mba_masters_fees_settings.php
2026_08_23_210000_create_mba_masters_class_settings.php
2026_08_23_211000_create_mba_masters_career_settings.php
2026_08_23_212000_create_mba_masters_alumni_settings.php
2026_08_23_213000_create_mba_masters_learning_settings.php
2026_08_23_214000_create_mba_masters_partners_settings.php
2026_08_23_215000_create_mba_masters_testimonials_settings.php
2026_08_23_216000_create_mba_masters_compare_settings.php
2026_08_23_217000_create_mba_masters_faq_settings.php
2026_08_23_218000_create_mba_masters_final_settings.php
+ 6 more alter migrations (plate_image, quote, trending, etc.)
```

**Services & Support:**
```
app/Services/FormMailer.php
app/Services/CloudinaryService.php
app/Support/MlpProse.php
app/helpers.php (media_url, settings_media_url, mlp_image_url, youtube_*, safe_settings, etc.)
app/Mail/GenericFormMail.php
```

---

## 14. Admin Editing Flow (for non-devs)

1. **Filament Admin** -> `Landing Pages` group
2. Choose chunk:
   - **Hero & Trust:** headline, subheading, bg image, stats, quote
   - **Programs:** overview items, why chapters, MBA tabs, Masters universities & trending bars, fees rows
   - **Audience:** class metrics/regions/industries, career stories, video testimonials (YT URLs)
   - **Proof:** alumni heading/trust_line (logos via PartnerLogos admin), partners heading (logos via UniversityPartners admin), published quotes, compare table
   - **FAQ & Close:** FAQ items, final CTA + plate image
   - **SEO:** meta title/description, OG/Twitter images, canonical, robots, schema, custom scripts
3. **Partner Logos admin:** `PartnerLogoResource` -> type=alumni for employer ribbon, type=accreditation/recognition for accreditation slider
4. **University Partners admin:** `UniversityPartnerResource` -> active + logo_url for partners section
5. Save -> Preview button goes to `/online-mba-masters-uae`

---

## 15. Gotchas / Notes

- **Journey & Compare sections commented** in main blade but settings & admin still exist. Un-comment to show.
- **Class-2025 & Video-proof hardcoded** - not from settings, need code change to edit.
- **Trending title uses `|` separator** - e.g. `Trending|Specialisations` -> first part dark navy, second gold. Code in `masters.blade.php` splits by `|`.
- **Video testimonials** reuse homepage `testimonials.js` - requires `window.testimonialsData` global.
- **Throttling:** 5 enquiries/min per IP on POST.
- **Honeypot:** hidden `website` field - if filled, fake success (spam protection).
- **No caching** for this page (unlike homepage/our-story which use `PublicContentCache`). Direct DB queries each request for logos.
- **Media handling:** `logo_url` may be Cloudinary URL or relative path; `media_url()` + `cached_asset()` resolves both. If `*_asset_id` set, `MediaAsset` model is source of truth.

---

## 16. Future Cleanup / Improvement Ideas

- Move hardcoded `class-2025` & `video-proof` YT URL to settings
- Add caching layer like other pages if traffic high
- Unify `MbaMastersLearningSettings` (unused) removal or usage
- Consolidate JS files (10 files) if performance needed

---

**Last Updated:** 2026-09-18
**Branch:** arena/01a0b3d6-maverick
**Maintained for:** Quick overview without scanning whole project

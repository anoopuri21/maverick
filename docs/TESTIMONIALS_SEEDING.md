# Testimonials Seeder & Admin Integration Guide

## Summary
The first 10 student testimonials from the official Maverick Student Reviews document have been converted into structured data, associated with high-resolution portrait photos, and wired to seed both the database and the Filament Admin Panel for:
1. **Our Story Page** (`/our-story` -> Testimonials section & Admin: *About Section* -> *Our Story Page* -> *Testimonials* tab)
2. **Master Landing Page** (`/online-mba-masters-uae` -> Voices / Testimonials section & Admin: *Landing Pages* -> *MBA Masters — Proof* -> *Testimonials* section)
3. **Media Library** (`/admin/media-assets`)

---

## 1. Selected 10 Testimonials

| # | Student Name | Course / Program | University / Partner | Nationality / Country | Image Asset |
|---|--------------|------------------|----------------------|-----------------------|-------------|
| 1 | **Christopher Kinene** | Global MBA (Triple Qualification) | University for the Creative Arts (UCA) | Uganda | `assets/images/testimonials/01-christopher-kinene.jpg` |
| 2 | **Merrisa Nicholas** | Global MBA (Triple Qualification) | University for the Creative Arts | Jamaica | `assets/images/testimonials/02-merrisa-nicholas.jpg` |
| 3 | **Kleese Gonzales** | MBA | University of Gloucestershire | Philippines | `assets/images/testimonials/03-kleese-gonzales.jpg` |
| 4 | **Marie Claire Claus** | Dual Doctoral Degree (PhD MIS & DBA Healthcare Management) | GAU & Rushford Business School | Malta | `assets/images/testimonials/04-marie-claire-claus.jpg` |
| 5 | **Haider Ali** | MSc Accounting & Finance | University of Gloucestershire | Pakistan | `assets/images/testimonials/05-haider-ali.jpg` |
| 6 | **Frederic Casagrande** | EPD - Global Business Management & Strategy | Rushford Business School | France | `assets/images/testimonials/06-frederic-casagrande.jpg` |
| 7 | **Gulfam Anvarhusen Tariya** | MBA Business Administration | University of Gloucestershire (UOG) | India | `assets/images/testimonials/07-gulfam-tariya.jpg` |
| 8 | **Sanika Yengheswaran** | MBA | University of Gloucestershire (UOG) | India | `assets/images/testimonials/08-sanika-yengheswaran.jpg` |
| 9 | **Jack Charles Boath** | MSc in Sustainability and Environmental Management | Rushford Business School | United Kingdom | `assets/images/testimonials/09-jack-boath.jpg` |
| 10 | **Ramkumar Dilli** | European Professional Doctorate (EPD) | Rushford Business School | India | `assets/images/testimonials/10-ramkumar-dilli.jpg` |

---

## 2. Terminal / Server Commands

To run the seeding on your server or terminal, use either of the following commands:

### Option A: Dedicated Artisan Command (Recommended)
```bash
php artisan testimonials:seed
```
*Options:*
- `--download-from-doc`: Re-download original images directly from Google Docs URLs if needed:
  ```bash
  php artisan testimonials:seed --download-from-doc
  ```

### Option B: Standard Laravel Seeder
```bash
php artisan db:seed --class=TestimonialSeeder
```

### Option C: Full Database Seed
```bash
php artisan db:seed
```
*(TestimonialSeeder has been registered in `database/seeders/DatabaseSeeder.php`)*

---

## 3. What Happens When the Command Runs

1. **Images & Media Library (`media_assets` table)**:
   - High-resolution student portraits are referenced from `public/assets/images/testimonials/`.
   - Each image is verified and indexed into the `media_assets` table with folder `our-story/testimonials`, SHA-256 hash, and dimensions.
   - Images immediately become visible and selectable in the Filament Media Library modal.

2. **Our Story Page & Admin (`our_story_testimonials` table)**:
   - Creates or updates 10 records in `our_story_testimonials` with student name, university, position, country, rating (5 stars), rich text quote, image path, and `media_asset_id`.
   - Visible in Filament Admin at **About Section** → **Our Story Page** → **Testimonials** tab.
   - Visible publicly on `/our-story` in the student review slider.

3. **Master Landing Page & Admin (`MbaMastersTestimonialsSettings`)**:
   - Updates the Spatie settings table with all 10 quotes formatted for the luxury slider, linking both image URL and `photo_asset_id`.
   - Visible in Filament Admin at **Landing Pages** → **MBA Masters — Proof** → **Testimonials** section.
   - Visible publicly on `/online-mba-masters-uae` in the Voices Closing Archive slider.

4. **Public Cache Flush**:
   - Automatically flushes `PublicContentCache::OUR_STORY` and all relevant cache keys so updates reflect instantly without manual cache clearing.

---

## 4. File Structure Created / Updated

- `database/seeders/data/testimonials.php` — Data source containing all 10 testimonials, quotes, metadata, and image mappings.
- `database/seeders/TestimonialSeeder.php` — Laravel Seeder class.
- `app/Console/Commands/SeedTestimonialsCommand.php` — Artisan command `testimonials:seed`.
- `public/assets/images/testimonials/` — 10 high-resolution student portrait images.
- `database/seeders/DatabaseSeeder.php` — Registered `TestimonialSeeder`.
- `app/Http/Controllers/PageController.php` — Injected `$storyTestimonials` into Master Landing Page view for fallback resilience.

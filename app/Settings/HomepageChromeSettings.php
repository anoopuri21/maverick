<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageChromeSettings extends Settings
{
    public ?string $featured_label = null;

    public ?string $featured_heading_line1 = null;

    public ?string $featured_heading_line2 = null;

    public ?string $featured_subtitle = null;

    public ?string $featured_cta_label = null;

    public ?string $alumni_label = null;

    public ?string $alumni_heading = null;

    public ?string $alumni_heading_accent = null;

    public ?string $alumni_subtitle = null;

    public ?string $alumni_description = null;

    public ?string $alumni_trust = null;

    public ?string $accred_label = null;

    public ?string $accred_heading_line1 = null;

    public ?string $accred_heading_line2 = null;

    public ?string $accred_subtitle = null;

    public ?string $accred_trust = null;

    public ?string $faculty_label = null;

    public ?string $faculty_heading_line1 = null;

    public ?string $faculty_heading_line2 = null;

    public ?string $faculty_subtitle = null;

    public ?string $events_label = null;

    public ?string $events_heading_line1 = null;

    public ?string $events_heading_line2 = null;

    public ?string $events_subtitle = null;

    public ?string $testimonials_label = null;

    public ?string $testimonials_heading_line1 = null;

    public ?string $testimonials_heading_line2 = null;

    public ?string $testimonials_subtitle = null;

    public ?string $faq_label = null;

    public ?string $faq_heading_line1 = null;

    public ?string $faq_heading_line2 = null;

    public ?string $faq_subtitle = null;

    public ?string $faq_image_url = null;

    public ?string $faq_image_url_asset_id = null;

    public static function group(): string
    {
        return 'homepage_chrome';
    }
}

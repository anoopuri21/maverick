<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProgramsDetailChromeSettings extends Settings
{
    public ?string $enquire_label = null;
    public ?string $apply_label = null;
    public ?string $scholarship_badge = null;
    public ?string $download_brochure_label = null;
    public ?string $quick_highlights_label = null;
    public ?string $glance_heading = null;
    public ?string $overview_label = null;
    public ?string $overview_heading = null;
    public ?string $why_label = null;
    public ?string $why_heading = null;
    public ?string $learn_label = null;
    public ?string $learn_heading = null;
    public ?string $learn_intro = null;
    public ?string $career_label = null;
    public ?string $career_heading = null;
    public ?string $career_intro = null;
    public ?string $structure_label = null;
    public ?string $structure_heading = null;
    public ?string $structure_intro = null;
    public ?string $university_label = null;
    public ?string $university_heading = null;
    public ?string $accreditation_label = null;
    public ?string $accreditation_heading = null;
    public ?string $partner_label = null;
    public ?string $partner_heading = null;
    public ?string $partner_intro = null;
    public ?string $stories_label = null;
    public ?string $stories_heading = null;
    public ?string $fees_intro = null;
    public ?string $fees_request_label = null;
    public ?string $faq_heading = null;
    public ?string $enquiry_heading = null;
    public ?string $enquiry_subheading = null;
    public ?string $final_cta_heading = null;
    public ?string $final_cta_body = null;

    public static function group(): string
    {
        return 'programs_detail_chrome';
    }
}
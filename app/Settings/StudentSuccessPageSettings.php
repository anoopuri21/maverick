<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class StudentSuccessPageSettings extends Settings
{
    public ?string $hero_tag = null;
    public ?string $hero_heading = null;
    public ?string $hero_heading_italic = null;
    public ?string $hero_description = null;
    public ?string $hero_background_image = null;
    public ?string $hero_background_image_asset_id = null;
    public ?string $section_label = null;
    public ?string $section_heading = null;
    public ?string $section_heading_italic = null;
    public ?string $section_subheading = null;
    public ?string $video_section_label = null;
    public ?string $video_section_heading = null;
    public ?string $video_section_heading_italic = null;
    public ?string $video_section_subheading = null;

    public static function group(): string
    {
        return 'student_success_page';
    }
}
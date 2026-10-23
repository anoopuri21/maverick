<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OurStorySectionsSettings extends Settings
{
    public ?string $journey_badge = null;

    public ?string $journey_heading = null;

    public ?string $gallery_badge = null;

    public ?string $gallery_heading = null;

    public ?string $testimonials_badge = null;

    public ?string $testimonials_heading = null;

    public ?string $testimonials_intro = null;

    public ?string $testimonials_stack_caption = null;

    public bool $show_testimonials = true;

    public static function group(): string
    {
        return 'our_story_sections';
    }
}

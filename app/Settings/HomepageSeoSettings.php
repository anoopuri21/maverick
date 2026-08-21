<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageSeoSettings extends Settings
{
    // ─── Basic SEO ───
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;
    public ?string $canonical_url = null;
    public ?string $robots = 'index, follow';

    // ─── Open Graph ───
    public ?string $og_title = null;
    public ?string $og_description = null;
    public ?string $og_image_url = null;
    public ?string $og_image_url_asset_id = null;
    public ?string $og_type = 'website';

    // ─── Twitter ───
    public ?string $twitter_card = 'summary_large_image';
    public ?string $twitter_title = null;
    public ?string $twitter_description = null;
    public ?string $twitter_image_url = null;
    public ?string $twitter_image_url_asset_id = null;

    // ─── Advanced ───
    public ?string $schema_json = null;
    public ?string $custom_head_scripts = null;
    public ?string $custom_body_scripts = null;

    public static function group(): string
    {
        return 'homepage_seo';
    }
}

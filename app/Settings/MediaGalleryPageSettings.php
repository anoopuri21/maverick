<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MediaGalleryPageSettings extends Settings
{
    public ?string $hero_tag = null;
    public ?string $hero_heading_line1 = null;
    public ?string $hero_heading_italic = null;
    public ?string $hero_description = null;
    public ?string $hero_background_image = null;
    public ?string $hero_background_image_asset_id = null;
    public ?string $photos_label = null;
    public ?string $photos_heading = null;
    public ?string $photos_subheading = null;
    public ?string $videos_label = null;
    public ?string $videos_heading = null;
    public ?string $videos_subheading = null;

    public static function group(): string
    {
        return 'media_gallery_page';
    }
}
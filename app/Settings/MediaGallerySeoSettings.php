<?php

namespace App\Settings;

use App\Settings\Concerns\HasPageSeoFields;
use Spatie\LaravelSettings\Settings;

class MediaGallerySeoSettings extends Settings
{
    use HasPageSeoFields;

    public static function group(): string
    {
        return 'media_gallery_seo';
    }
}

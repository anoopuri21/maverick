<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    protected $table = 'seo_metadata';

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image_url',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image_url',
        'schema_json',
        'custom_head_scripts',
        'custom_body_scripts',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
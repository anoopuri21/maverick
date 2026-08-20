<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GlobalPathway extends Model
{
    protected $fillable = [
        'type',
        'slug',
        'title',
        'eyebrow',
        'heading',
        'heading_italic',
        'intro',
        'image_url',
        'image_url_asset_id',
        'items',
        'seo',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'items' => 'array',
        'seo' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /** Items normalized to array of rows. */
    public function getItemsListAttribute(): array
    {
        return collect($this->items ?? [])->map(function ($item) {
            return is_array($item) ? $item : ['title' => (string) $item];
        })->values()->all();
    }
}

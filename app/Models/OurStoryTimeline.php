<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\HasMediaAssets;

class OurStoryTimeline extends Model
{
    use HasMediaAssets;

    use SoftDeletes;

    protected $fillable = [
        'year',
        'title',
        'description',
        'icon_url',
        'sort_order',
        'is_active',
        'icon_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $item->year = (string) ($item->year ?? '');
            $item->title = (string) ($item->title ?? '');
        });
    }
}

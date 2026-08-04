<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\HasMediaAssets;

class MediaGalleryEvent extends Model
{
    use HasMediaAssets;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'event_date',
        'location',
        'image_url',
        'image_url_asset_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'event_date' => 'date',
    ];
}

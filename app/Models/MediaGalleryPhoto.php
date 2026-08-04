<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\HasMediaAssets;

class MediaGalleryPhoto extends Model
{
    use HasMediaAssets;
    use SoftDeletes;

    protected $fillable = [
        'image_url',
        'image_url_asset_id',
        'caption',
        'category',
        'size',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}

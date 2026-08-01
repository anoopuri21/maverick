<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\HasMediaAssets;

class OurStoryGalleryImage extends Model
{
    use HasMediaAssets;

    use SoftDeletes;

    protected $fillable = [
        'image_url',
        'caption',
        'category',
        'sort_order',
        'is_active',
        'image_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasMediaAssets;

class FacultyInsight extends Model
{
    use HasMediaAssets;

    protected $fillable = [
        'title',
        'slug',
        'badge',
        'image_url',
        'link_url',
        'sort_order',
        'is_active',
        'image_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
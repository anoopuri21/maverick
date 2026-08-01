<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasMediaAssets;

class UniversityPartner extends Model
{
    use HasMediaAssets;

    protected $fillable = [
        'name',
        'country',
        'country_code',
        'city',
        'latitude',
        'longitude',
        'is_hub',
        'logo_url',
        'website_url',
        'description',
        'recognition',
        'programs',
        'sort_order',
        'is_active',
        'logo_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_hub' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'programs' => 'array',
    ];
}
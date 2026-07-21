<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniversityPartner extends Model
{
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
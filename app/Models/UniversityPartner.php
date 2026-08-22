<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Concerns\EnsuresUniqueSlug;
use App\Concerns\HasMediaAssets;

class UniversityPartner extends Model
{
    use EnsuresUniqueSlug;
    use HasMediaAssets;

    protected $fillable = [
        'name',
        'slug',
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
    ];

    protected static function booted(): void
    {
        static::saving(function (self $partner) {
            $partner->name = (string) ($partner->name ?? '');
            $partner->country = (string) ($partner->country ?? '');
        });
    }

    /**
     * All programs this university offers (1 university has many programs).
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'university_partner_id');
    }
}

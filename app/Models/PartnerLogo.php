<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasMediaAssets;

class PartnerLogo extends Model
{
    use HasMediaAssets;

    protected $fillable = [
        'name',
        'logo_url',
        'type',
        'description',
        'sort_order',
        'is_active',
        'logo_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $logo) {
            $logo->name = (string) ($logo->name ?? '');

            if (! in_array($logo->type, ['alumni', 'accreditation', 'recognition', 'award'], true)) {
                $logo->type = 'alumni';
            }
        });
    }
}
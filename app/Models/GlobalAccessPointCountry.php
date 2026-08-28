<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalAccessPointCountry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'iso_numeric',
        'iso2',
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $country) {
            $country->iso_numeric = str_pad((string) ($country->iso_numeric ?? ''), 3, '0', STR_PAD_LEFT);
            $country->iso2 = strtoupper(trim((string) ($country->iso2 ?? '')));
            $country->name = (string) ($country->name ?? '');
        });
    }
}

<?php

namespace App\Models;

use App\Concerns\EnsuresUniqueSlug;
use App\Concerns\HasMediaAssets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GupPartnerUniversity extends Model
{
    use EnsuresUniqueSlug;
    use HasMediaAssets;
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'abbreviation',
        'country',
        'flag_emoji',
        'recognition',
        'logo_url',
        'logo_url_asset_id',
        'cta_url',
        'cta_label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $uni) {
            $uni->name = (string) ($uni->name ?? '');
            $uni->country = (string) ($uni->country ?? '');
        });
    }

    public function getLogoAttribute(): ?string
    {
        return media_url($this->getMediaUrl('logo_url'));
    }

    public function getDisplayAbbreviationAttribute(): string
    {
        if (filled($this->abbreviation)) {
            return $this->abbreviation;
        }

        return collect(explode(' ', (string) ($this->name ?? '')))
            ->filter(fn ($word) => $word !== '')
            ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->take(4)
            ->implode('');
    }

    public function getCtaLinkAttribute(): string
    {
        return $this->cta_url ?: url('/programs');
    }
}

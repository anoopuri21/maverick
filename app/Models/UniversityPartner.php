<?php

namespace App\Models;

use App\Concerns\EnsuresUniqueSlug;
use App\Concerns\HasMediaAssets;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

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
        'campus_image_url',
        'website_url',
        'description',
        'recognition',
        'recognition_logos',
        'sort_order',
        'is_active',
        'logo_url_asset_id',
        'campus_image_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_hub' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'recognition_logos' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $partner) {
            $partner->name = (string) ($partner->name ?? '');
            $partner->country = (string) ($partner->country ?? '');
        });

        static::updating(function (self $partner): void {
            if ($partner->wasPartnerWallEntry() && ! $partner->isPartnerWallEntry()) {
                $partner->ensureAnotherPartnerWallEntryRemains('is_active');
            }
        });

        static::deleting(function (self $partner): void {
            if ($partner->isPartnerWallEntry()) {
                $partner->ensureAnotherPartnerWallEntryRemains('record');
            }
        });
    }

    /**
     * Scope to active universities that have the name and a resolvable logo
     * required by the shared public partner wall.
     */
    public function scopeListedInPartnerWall(Builder $query): Builder
    {
        $table = $this->getTable();

        return $query
            ->where($table.'.is_active', true)
            ->whereNotNull($table.'.name')
            ->where($table.'.name', '<>', '')
            ->where(function ($logos) use ($table): void {
                $logos->where(function ($url) use ($table): void {
                    $url->whereNotNull($table.'.logo_url')
                        ->where($table.'.logo_url', '<>', '');
                })->orWhereIn(
                    $table.'.logo_url_asset_id',
                    MediaAsset::query()
                        ->select('id')
                        ->whereNotNull('url')
                        ->where('url', '<>', '')
                );
            });
    }

    private function wasPartnerWallEntry(): bool
    {
        return (bool) $this->getRawOriginal('is_active')
            && filled($this->getRawOriginal('name'))
            && (
                filled($this->getRawOriginal('logo_url'))
                || $this->hasResolvableLogoAsset($this->getRawOriginal('logo_url_asset_id'))
            );
    }

    private function isPartnerWallEntry(): bool
    {
        return (bool) $this->is_active
            && filled($this->name)
            && (
                filled($this->logo_url)
                || $this->hasResolvableLogoAsset($this->logo_url_asset_id)
            );
    }

    private function hasResolvableLogoAsset(mixed $assetId): bool
    {
        return filled($assetId)
            && MediaAsset::query()
                ->whereKey($assetId)
                ->whereNotNull('url')
                ->where('url', '<>', '')
                ->exists();
    }

    private function ensureAnotherPartnerWallEntryRemains(string $attribute): void
    {
        $anotherListedPartnerExists = static::query()
            ->listedInPartnerWall()
            ->where($this->qualifyColumn($this->getKeyName()), '<>', $this->getKey())
            ->exists();

        if (! $anotherListedPartnerExists) {
            throw ValidationException::withMessages([
                $attribute => 'At least one active university partner with a name and logo must remain.',
            ]);
        }
    }

    /**
     * Image used by linked program detail pages.
     *
     * The media asset is the source of truth when one is selected. The logo
     * fallback keeps existing program pages stable while campus images are
     * added gradually in the admin panel.
     */
    public function programDetailImageUrl(): ?string
    {
        return $this->getMediaUrl('campus_image_url')
            ?: $this->getMediaUrl('logo_url');
    }

    /**
     * Recognised & Accredited logo strip shared by every linked program.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function getRecognitionLogosListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->recognition_logos ?? [])
            ->filter(function ($row) {
                if (! is_array($row)) {
                    return false;
                }

                return filled($row['name'] ?? null) || filled($row['logo'] ?? null);
            })
            ->values();
    }

    /**
     * All programs this university offers (1 university has many programs).
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'university_partner_id');
    }
}

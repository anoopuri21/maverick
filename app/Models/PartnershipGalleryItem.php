<?php

namespace App\Models;

use App\Concerns\HasMediaAssets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnershipGalleryItem extends Model
{
    use HasMediaAssets;
    use SoftDeletes;

    public const CATEGORIES = [
        'mou-signings' => 'MOU Signings',
        'graduations' => 'Graduations',
        'university-visits' => 'University Visits',
        'conferences' => 'Conferences',
        'forums' => 'Forums',
    ];

    public const SIZES = [
        'medium' => 'Medium',
        'tall' => 'Tall',
        'wide' => 'Wide',
    ];

    protected $fillable = [
        'image_url',
        'image_url_asset_id',
        'category',
        'badge',
        'event_date',
        'title',
        'caption',
        'size',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getImageAttribute(): ?string
    {
        return media_url($this->getMediaUrl('image_url'));
    }

    public function getFormattedDateAttribute(): ?string
    {
        return $this->event_date?->format('d M Y');
    }

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $item->category = filled($item->category) ? $item->category : 'mou-signings';
            $item->badge = (string) ($item->badge ?? '');
            $item->size = filled($item->size) ? $item->size : 'medium';
        });
    }
}

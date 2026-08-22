<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurStoryTestimonial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'rating',
        'testimonial',
        'photo',
        'media_asset_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'rating' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $item->name = (string) ($item->name ?? '');
            $item->testimonial = (string) ($item->testimonial ?? '');
            if ($item->rating === null) {
                $item->rating = 5;
            }
        });
    }

    public function mediaAsset()
    {
        return $this->belongsTo(MediaAsset::class);
    }
}

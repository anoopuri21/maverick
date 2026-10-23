<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'event_time',
        'event_type',
        'location',
        'display_image',
        'gallery_images',
        'link_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'event_date' => 'date',
        'gallery_images' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $event) {
            $event->title = (string) ($event->title ?? '');

            if (\Illuminate\Support\Facades\Schema::hasColumn('events', 'slug')) {
                if (empty($event->slug) && ! empty($event->title)) {
                    $baseSlug = Str::slug($event->title);
                    $slug = $baseSlug;
                    $count = 1;
                    while (static::where('slug', $slug)->where('id', '!=', $event->id ?? 0)->exists()) {
                        $slug = $baseSlug . '-' . $count;
                        $count++;
                    }
                    $event->slug = $slug;
                }
            }

            if (empty($event->event_date)) {
                $event->event_date = now()->toDateString();
            }
        });
    }

    public function getDisplayImageUrlAttribute(): ?string
    {
        if (empty($this->display_image)) {
            return null;
        }

        if (Str::startsWith($this->display_image, ['http://', 'https://'])) {
            return $this->display_image;
        }

        return asset('storage/' . ltrim($this->display_image, '/'));
    }

    public function getGalleryImageUrlsAttribute(): array
    {
        $gallery = $this->gallery_images;
        if (! is_array($gallery)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($path) {
            if (empty($path)) {
                return null;
            }
            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }
            return asset('storage/' . ltrim($path, '/'));
        }, $gallery)));
    }

    public function getIsPastAttribute(): bool
    {
        return $this->event_date ? $this->event_date->isPast() : false;
    }
}

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
        'event_start_time',
        'event_end_time',
        'event_type',
        'location',
        'display_image',
        'display_image_asset_id',
        'gallery_images',
        'gallery_videos',
        'registration_fields',
        'link_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'event_date' => 'date',
        'gallery_images' => 'array',
        'gallery_videos' => 'array',
        'registration_fields' => 'array',
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

        });
    }

    protected function formatSingleTime(?string $time): ?string
    {
        if (empty($time)) {
            return null;
        }

        try {
            $timestamp = strtotime($time);
            if ($timestamp !== false) {
                return date('h:i A', $timestamp);
            }
        } catch (\Throwable $e) {
            // fallback if string parsing fails
        }

        return $time;
    }

    public function getFormattedTimeAttribute(): ?string
    {
        $start = $this->formatSingleTime($this->event_start_time);
        $end = $this->formatSingleTime($this->event_end_time);

        if (! empty($start) && ! empty($end)) {
            return "{$start} - {$end}";
        }

        if (! empty($start)) {
            return $start;
        }

        if (! empty($end)) {
            return $end;
        }

        return $this->event_time ? $this->formatSingleTime($this->event_time) : null;
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

        // Handle single associative item corrupted by legacy single-item wrap
        if (isset($gallery['url']) || isset($gallery['image_url'])) {
            $gallery = [$gallery];
        }

        $urls = [];
        foreach ($gallery as $item) {
            $path = is_array($item) ? ($item['url'] ?? $item['image_url'] ?? '') : (string) $item;
            $path = trim($path);
            if (empty($path)) {
                continue;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                $urls[] = $path;
            } else {
                $urls[] = asset('storage/' . ltrim($path, '/'));
            }
        }

        return array_values(array_unique($urls));
    }

    public function getFormattedVideosAttribute(): array
    {
        $videos = $this->gallery_videos;
        if (! is_array($videos)) {
            return [];
        }

        $formatted = [];
        foreach ($videos as $video) {
            $url = is_array($video) ? ($video['url'] ?? $video['video_url'] ?? '') : (string) $video;
            $url = trim($url);
            if (empty($url)) {
                continue;
            }

            $embedUrl = null;
            $type = 'direct';

            // YouTube match
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                $type = 'youtube';
            }
            // Vimeo match
            elseif (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/[^\/]*\/videos\/|album\/\d+\/video\/|video\/|)(\d+)/', $url, $matches)) {
                $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
                $type = 'vimeo';
            }
            // Direct video or storage link
            else {
                if (Str::startsWith($url, ['http://', 'https://'])) {
                    $embedUrl = $url;
                } else {
                    $embedUrl = asset('storage/' . ltrim($url, '/'));
                }
                $type = 'direct';
            }

            $formatted[] = [
                'original' => $url,
                'embed_url' => $embedUrl,
                'type' => $type,
                'title' => is_array($video) ? ($video['title'] ?? '') : '',
            ];
        }

        return $formatted;
    }

    public function getIsPastAttribute(): bool
    {
        return $this->event_date ? $this->event_date->isPast() : false;
    }
}

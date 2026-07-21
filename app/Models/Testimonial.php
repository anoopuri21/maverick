<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'company',
        'thumbnail_url',
        'video_url',
        'video_type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get properly formatted embed URL for any YouTube/Vimeo URL
     */
    public function getEmbedUrlAttribute(): string
    {
        $url = trim($this->video_url ?? '');

        if (empty($url)) {
            return '';
        }

        // YouTube patterns
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Just a YouTube video ID (11 chars)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return 'https://www.youtube.com/embed/' . $url;
        }

        // Vimeo patterns
        if (preg_match('/vimeo\.com\/(?:.*\/)?(\d+)/i', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        // Direct video file (mp4, webm)
        return $url;
    }

    /**
     * Auto-generate thumbnail from YouTube if not provided
     */
    public function getAutoThumbnailAttribute(): string
    {
        // If user uploaded a thumbnail, use that
        if (!empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        $url = trim($this->video_url ?? '');

        // Extract YouTube video ID for auto-thumbnail
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/maxresdefault.jpg";
        }

        // Just YouTube ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return "https://img.youtube.com/vi/{$url}/maxresdefault.jpg";
        }

        return asset('assets/images/placeholder.jpg');
    }
}
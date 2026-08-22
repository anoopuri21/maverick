<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaRecycleLog extends Model
{
    protected $fillable = [
        'media_asset_id',
        'cloudinary_public_id',
        'url',
        'hash',
        'folder',
        'disk_env',
        'original_name',
        'deleted_from_cloudinary',
        'payload',
    ];

    protected $casts = [
        'deleted_from_cloudinary' => 'boolean',
        'payload' => 'array',
    ];

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }
}

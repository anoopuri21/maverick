<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaMigrationMap extends Model
{
    protected $table = 'media_migration_map';

    protected $fillable = [
        'old_public_id',
        'new_public_id',
        'old_url',
        'new_url',
        'bytes',
        'media_asset_id',
        'source',
        'source_ref',
        'status',
        'reason',
        'last_error',
        'attempts',
    ];

    protected $casts = [
        'bytes' => 'integer',
        'attempts' => 'integer',
    ];

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }
}

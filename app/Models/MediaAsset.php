<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hash',
        'original_name',
        'mime_type',
        'size_bytes',
        'width',
        'height',
        'cloudinary_public_id',
        'url',
        'folder',
        'alt',
        'disk_env',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function ourStoryTestimonials(): HasMany
    {
        return $this->hasMany(OurStoryTestimonial::class);
    }
}

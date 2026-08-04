<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaPressMention extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'publication',
        'code',
        'title',
        'url',
        'publication_date',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_type',
        'location',
        'link_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'event_date' => 'date',
    ];
}
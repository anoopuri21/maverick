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
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'rating' => 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Program extends Model
{
    protected $fillable = [
        'program_category_id',
        'title',
        'slug',
        'partner_university',
        'duration',
        'level',
        'short_description',
        'description',
        'image_url',
        'is_featured',
        'is_active',
        'sort_order', 
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function programCategory(): BelongsTo
    {
        return $this->belongsTo(ProgramCategory::class);
    }
        public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('sort_order');
    }
}
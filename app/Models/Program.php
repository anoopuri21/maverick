<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Concerns\HasMediaAssets;

class Program extends Model
{
    use HasMediaAssets;

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
        'image_url_asset_id',
        'highlights',
        'recognition',
        'snapshot',
        'benefits',
        'learning',
        'careers',
        'structure',
        'support',
        'university',
        'accreditation_groups',
        'testimonials',
        'fees',
        'reviews',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'highlights' => 'array',
        'recognition' => 'array',
        'snapshot' => 'array',
        'benefits' => 'array',
        'learning' => 'array',
        'careers' => 'array',
        'structure' => 'array',
        'support' => 'array',
        'university' => 'array',
        'accreditation_groups' => 'array',
        'testimonials' => 'array',
        'fees' => 'array',
        'reviews' => 'array',
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
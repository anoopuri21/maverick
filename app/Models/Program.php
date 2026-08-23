<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Concerns\HasMediaAssets;
use App\Models\UniversityPartner;

class Program extends Model
{
    use HasMediaAssets;

    protected $fillable = [
        'program_category_id',
        'university_partner_id',
        'title',
        'slug',
        'duration',
        'level',
        'short_description',
        'description',
        'image_url',
        'brochure_url',
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
        'accreditation_groups' => 'array',
        'testimonials' => 'array',
        'fees' => 'array',
        'reviews' => 'array',
    ];

    public function programCategory(): BelongsTo
    {
        return $this->belongsTo(ProgramCategory::class);
    }

    /**
     * The single university offering this program (1 program = 1 university).
     */
    public function universityPartner(): BelongsTo
    {
        return $this->belongsTo(UniversityPartner::class);
    }
        public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('sort_order');
    }

    // ------------------------------------------------------------------
    // Detail-page content accessors (normalize stored JSON into the shapes
    // the views expect). Keeps heavy mapping out of the blade templates.
    // ------------------------------------------------------------------

    /** Quick highlights — [{label, value}] */
    public function getHighlightsListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->highlights ?? []);
    }

    /** Recognition logos — [{name, logo, note}] */
    public function getRecognitionListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->recognition ?? []);
    }

    /** Snapshot — [{label, value}] */
    public function getSnapshotListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->snapshot ?? []);
    }

    /** Benefits (Why Choose) — [{title, desc, icon}] */
    public function getBenefitsListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->benefits ?? []);
    }

    /** Learning outcomes — [{item}] normalized to a simple list */
    public function getLearningListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->learning ?? [])
            ->map(fn ($l) => $l['item'] ?? $l)
            ->values();
    }

    /** Careers — [{title}] normalized to a simple list */
    public function getCareersListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->careers ?? [])
            ->map(fn ($c) => $c['title'] ?? $c)
            ->values();
    }

    /** Programme structure — [{title, subtitle, modules:[...]}] normalized */
    public function getStructureListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->structure ?? [])->map(function ($stage) {
            return [
                'title'    => $stage['title'] ?? '',
                'subtitle' => $stage['subtitle'] ?? '',
                'modules'  => collect($stage['modules'] ?? [])->map(function ($m) {
                    return [
                        'title'    => $m['title'] ?? '',
                        'overview' => $m['overview'] ?? null,
                        'desc'     => $m['desc'] ?? null,
                        'list'     => collect($m['list'] ?? [])
                            ->map(fn ($li) => $li['point'] ?? $li)
                            ->values()
                            ->all(),
                    ];
                })->values()->all(),
            ];
        })->values();
    }

    /** Maverick Support — [{item}] normalized to a simple list */
    public function getSupportListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->support ?? [])
            ->map(fn ($s) => $s['item'] ?? $s)
            ->values();
    }

    /** About the University — derived from the linked UniversityPartner (single source). */
    public function getUniversityObjectAttribute(): object
    {
        $partner = $this->universityPartner;

        return (object) [
            'name'          => $partner->name ?? null,
            'description'   => $partner->description ?? null,
            'establishment' => null,
            'image'         => $partner->logo_url ?? null,
        ];
    }

    /** Accreditation groups — [{group, items:[{name,logo}]}] normalized */
    public function getAccreditationGroupsListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->accreditation_groups ?? [])->map(function ($g) {
            return [
                'group' => $g['group'] ?? '',
                'items' => collect($g['items'] ?? []),
            ];
        })->values();
    }

    /** Video testimonials — [{name, role, country, category, thumb, video}] */
    public function getTestimonialsListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->testimonials ?? []);
    }

    /** Fees — [{title}] normalized to a simple list */
    public function getFeesListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->fees ?? [])
            ->map(fn ($f) => $f['title'] ?? $f)
            ->values();
    }

    /** Reviews (Google ratings) — [{name, avatar, rating, review}] */
    public function getReviewsListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->reviews ?? []);
    }

    /** Reviews mapped to the shared Our Story testimonial slider shape */
    public function getReviewTestimonialObjectsAttribute(): \Illuminate\Support\Collection
    {
        return $this->reviews_list->map(fn ($r) => (object) [
            'name'         => $r['name'] ?? '',
            'rating'       => $r['rating'] ?? 5,
            'testimonial'  => $r['review'] ?? '',
            'photo'        => $r['avatar'] ?? null,
            'organisation' => $r['organisation'] ?? null,
            'position'     => $r['position'] ?? null,
            'country'      => $r['country'] ?? null,
        ])->values();
    }

    /**
     * Left-side scrollspy dots — one per section that actually renders.
     * Keeps render conditions in ONE place so they can never desync from
     * the section @if guards.
     */
    public function getSectionNavAttribute(): \Illuminate\Support\Collection
    {
        return collect([
            ['id' => 'overview',       'label' => 'Overview',       'render' => ($this->highlights_list->count() || $this->description)],
            ['id' => 'why-choose',     'label' => 'Why Choose',     'render' => $this->benefits_list->count() > 0],
            ['id' => 'careers',        'label' => 'Careers',        'render' => ($this->learning_list->count() || $this->careers_list->count())],
            ['id' => 'structure',      'label' => 'Structure',      'render' => $this->structure_list->count() > 0],
            ['id' => 'university',     'label' => 'University',     'render' => ! empty($this->university_object->name)],
            ['id' => 'accreditation',  'label' => 'Accreditation',  'render' => $this->accreditation_groups_list->count() > 0],
            ['id' => 'support',        'label' => 'Support',        'render' => $this->support_list->count() > 0],
            ['id' => 'testimonials',   'label' => 'Testimonials',   'render' => $this->testimonials_list->count() > 0],
            ['id' => 'fees',           'label' => 'Fees',           'render' => $this->fees_list->count() > 0],
            ['id' => 'faq',            'label' => 'FAQ',            'render' => $this->faqs->count() > 0],
            ['id' => 'enquire',        'label' => 'Enquire',        'render' => true],
        ])->filter(fn ($s) => $s['render'])->values();
    }
}
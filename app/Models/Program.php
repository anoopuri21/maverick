<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Concerns\EnsuresUniqueSlug;
use App\Concerns\HasMediaAssets;
use App\Models\UniversityPartner;

class Program extends Model
{
    use EnsuresUniqueSlug;
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
        'gcc_heading',
        'gcc_reasons',
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
        'gcc_reasons' => 'array',
        'accreditation_groups' => 'array',
        'testimonials' => 'array',
        'fees' => 'array',
        'reviews' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $program) {
            $program->title = (string) ($program->title ?? '');

            if (empty($program->program_category_id)) {
                $uncategorized = ProgramCategory::query()->firstOrCreate(
                    ['slug' => 'uncategorized'],
                    ['name' => 'Uncategorized'],
                );
                $program->program_category_id = $uncategorized->id;
            }
        });
    }

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
            ->map(fn ($l) => is_array($l) ? ($l['item'] ?? $l) : $l)
            ->values();
    }

    /** Careers — [{title}] normalized to a simple list */
    public function getCareersListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->careers ?? [])
            ->map(fn ($c) => is_array($c) ? ($c['title'] ?? $c) : $c)
            ->values();
    }

    /** Programme structure — [{title, subtitle, modules:[...]}] normalized */
    public function getStructureListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->structure ?? [])
            ->filter(fn ($stage) => is_array($stage))
            ->map(function ($stage) {
                return [
                    'title' => data_get($stage, 'title', ''),
                    'subtitle' => data_get($stage, 'subtitle', ''),
                    'modules' => collect(data_get($stage, 'modules') ?? [])
                        ->filter(fn ($m) => is_array($m))
                        ->map(function ($m) {
                            return [
                                'title' => data_get($m, 'title', ''),
                                'overview' => data_get($m, 'overview'),
                                'desc' => data_get($m, 'desc'),
                                'list' => collect(data_get($m, 'list') ?? [])
                                    ->map(fn ($li) => is_array($li) ? ($li['point'] ?? $li) : $li)
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
            ->map(fn ($s) => is_array($s) ? ($s['item'] ?? $s) : $s)
            ->values();
    }

    /** GCC professional reasons — [{title, text, icon}] */
    public function getGccReasonsListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->gcc_reasons ?? [])
            ->filter(fn ($item) => is_array($item) && filled($item['title'] ?? null))
            ->values();
    }

    /** About the University — derived from the linked UniversityPartner (single source). */
    public function getUniversityObjectAttribute(): object
    {
        $partner = $this->universityPartner;

        return (object) [
            'name'          => $partner?->name,
            'description'   => $partner?->description,
            'establishment' => null,
            'image'         => $partner?->programDetailImageUrl(),
        ];
    }

    /** Accreditation groups — [{group, items:[{name,logo}]}] normalized */
    public function getAccreditationGroupsListAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->accreditation_groups ?? [])
            ->filter(fn ($g) => is_array($g))
            ->map(function ($g) {
                return [
                    'group' => data_get($g, 'group', ''),
                    'items' => collect(data_get($g, 'items') ?? [])->filter(fn ($item) => is_array($item)),
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
            ->map(fn ($f) => is_array($f) ? ($f['title'] ?? $f) : $f)
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
        return $this->reviews_list
            ->filter(fn ($r) => is_array($r))
            ->map(fn ($r) => (object) [
                'name' => data_get($r, 'name', ''),
                'rating' => data_get($r, 'rating', 5),
                'testimonial' => data_get($r, 'review', ''),
                'photo' => data_get($r, 'avatar'),
                'organisation' => data_get($r, 'organisation'),
                'position' => data_get($r, 'position'),
                'country' => data_get($r, 'country'),
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
            ['id' => 'gcc-choose',     'label' => 'GCC',            'render' => $this->gcc_reasons_list->count() > 0],
            ['id' => 'testimonials',   'label' => 'Testimonials',   'render' => $this->testimonials_list->count() > 0],
            ['id' => 'fees',           'label' => 'Fees',           'render' => $this->fees_list->count() > 0],
            ['id' => 'faq',            'label' => 'FAQ',            'render' => $this->faqs->count() > 0],
            ['id' => 'enquire',        'label' => 'Enquire',        'render' => true],
        ])->filter(fn ($s) => $s['render'])->values();
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramEnquiryRequest;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Services\FormMailer;
use App\Services\ZapierWebhookDispatcher;
use App\Support\ZapierEvents;
use App\Settings\ProgramsListingSeoSettings;
use App\Settings\ProgramsListingPageSettings;
use App\Settings\ProgramsDetailChromeSettings;
use App\Support\PublicContentCache;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Programme listing page.
     */
    public function index()
    {
        $listing = PublicContentCache::remember(PublicContentCache::PROGRAMS_LISTING, function () {
            $categories = PublicContentCache::serializeRows(
                ProgramCategory::select('id', 'name', 'slug', 'icon', 'sort_order')
                    ->withCount([
                        'programs' => fn ($q) => $q->where('is_active', true)->hasPublicSlug(),
                    ])
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get(),
                fn (ProgramCategory $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'sort_order' => $category->sort_order,
                    'programs_count' => $category->programs_count,
                ]
            );

            $programs = PublicContentCache::serializeRows(
                Program::select([
                    'id', 'program_category_id', 'university_partner_id', 'title', 'slug',
                    'duration', 'level', 'short_description', 'image_url', 'sort_order',
                ])
                    ->with([
                        'programCategory:id,name,slug',
                        'universityPartner:id,name,logo_url,country',
                    ])
                    ->where('is_active', true)
                    ->hasPublicSlug()
                    ->orderBy('sort_order')
                    ->orderBy('title')
                    ->get(),
                fn (Program $program) => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'slug' => $program->slug,
                    'duration' => $program->duration,
                    'level' => $program->level,
                    'short_description' => $program->short_description,
                    'image_url' => $program->image_url,
                    'sort_order' => $program->sort_order,
                    'programCategory' => $program->programCategory
                        ? [
                            'name' => $program->programCategory->name,
                            'slug' => $program->programCategory->slug,
                        ]
                        : null,
                    'universityPartner' => $program->universityPartner
                        ? ['name' => $program->universityPartner->name]
                        : null,
                ]
            );

            return [
                'categories' => $categories,
                'programs' => $programs,
            ];
        });

        return view('pages.programs.index', [
            'programsListingPage' => safe_settings(ProgramsListingPageSettings::class),
            'categories' => PublicContentCache::hydrateRows(ProgramCategory::class, $listing['categories'] ?? []),
            'programs' => PublicContentCache::hydrateRows(Program::class, $listing['programs'] ?? [], [
                'program_category' => ProgramCategory::class,
                'university_partner' => \App\Models\UniversityPartner::class,
            ]),
            'programsListingSeo' => safe_settings(ProgramsListingSeoSettings::class),
        ]);
    }

    /**
     * Programme detail page.
     */
    public function show(Request $request, $slug)
    {
        $program = Program::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'programCategory:id,name,slug',
                'universityPartner',
                'faqs' => fn ($q) => $q->where('is_active', true),
                'seo',
            ])
            ->first();

        if (! $program) {
            abort(404);
        }

        return view('pages.programs.detail', [
            'program' => $program,
            'chrome' => safe_settings(ProgramsDetailChromeSettings::class),
        ]);
    }

    public function enquire(ProgramEnquiryRequest $request, FormMailer $formMailer)
    {
        $data = $request->validated();

        $qualifications = [
            'high-school' => 'High School / Secondary',
            'diploma' => 'Diploma',
            'bachelor' => "Bachelor's Degree",
            'master' => "Master's Degree",
            'other' => 'Other',
        ];

        $qualification = $data['qualification'] ?? '';
        if (isset($qualifications[$qualification])) {
            $qualification = $qualifications[$qualification];
        }

        $formMailer->send([
            'Programme' => $data['programme'] ?? '',
            'Name' => $data['name'] ?? '',
            'Email' => $data['email'] ?? '',
            'Phone' => $data['phone'] ?? '',
            'Country' => $data['country'] ?? '',
            'Study mode' => $data['study_mode'] ?? '',
            'Qualification' => $qualification,
            'Message' => $data['message'] ?? '',
        ], 'Programme enquiry'.(filled($data['programme'] ?? null) ? ': '.$data['programme'] : ''), [
            'reply_to' => $data['email'] ?? null,
        ]);

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::PROGRAM_ENQUIRY_SUBMITTED, array_merge($data, [
            'qualification' => $qualification,
        ]));

        return back()->with('success', 'Thank you! We will get back to you shortly.');
    }
}

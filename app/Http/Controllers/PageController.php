<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Settings\HeroSettings;
use App\Settings\NumbersSettings;
use App\Settings\WhoWeAreSettings;
use App\Settings\CeoSettings;
use App\Settings\WhatIsMaverickSettings;
use App\Settings\FinalCtaSettings;
use App\Models\PartnerLogo;
use App\Models\UniversityPartner;
use App\Models\FacultyInsight;
use App\Models\Event;
use App\Models\Testimonial;
use App\Settings\WhatWeDoSettings;
use App\Settings\HowWeDoItSettings;
use App\Settings\WhyMaverickSettings;
use App\Settings\GlobalOpportunitiesSettings;

class PageController extends Controller
{
    public function home()
{
    // Cache homepage data for 5 minutes with safe array serialization
    $data = cache()->remember('homepage_data_v1', now()->addMinutes(5), function () {
        
        // Settings - Convert objects to arrays to avoid serialization issues
        $settings = [
            'hero' => (array) app(HeroSettings::class),
            'numbers' => (array) app(NumbersSettings::class),
            'whoWeAre' => (array) app(WhoWeAreSettings::class),
            'ceo' => (array) app(CeoSettings::class),
            'whatIsMaverick' => (array) app(WhatIsMaverickSettings::class),
            'finalCta' => (array) app(FinalCtaSettings::class),
            'whatWeDo' => (array) app(WhatWeDoSettings::class),
            'howWeDoIt' => (array) app(HowWeDoItSettings::class),
            'whyMaverick' => (array) app(WhyMaverickSettings::class),
            'globalOpportunities' => (array) app(GlobalOpportunitiesSettings::class),
        ];

        // Collections — efficient queries with select() to reduce memory
        $featuredPrograms = Program::select('id', 'title', 'slug', 'partner_university', 'short_description', 'image_url', 'sort_order')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $alumniLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->where('type', 'alumni')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $accreditationLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->where('type', 'accreditation')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $universityPartners = UniversityPartner::select('id', 'name', 'country', 'city', 'latitude', 'longitude', 'is_hub', 'recognition', 'programs', 'logo_url')
            ->where('is_active', true)
            ->orderBy('country')
            ->get();

        $facultyInsights = FacultyInsight::select('id', 'title', 'slug', 'badge', 'image_url', 'link_url', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $events = Event::select('id', 'title', 'description', 'event_date', 'event_type', 'location', 'link_url')
            ->where('is_active', true)
            ->orderBy('event_date', 'desc')
            ->limit(6)
            ->get();

        $testimonials = Testimonial::select('id', 'name', 'designation', 'company', 'thumbnail_url', 'video_url', 'video_type', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(9)
            ->get();

        $homepageFaqs = \App\Models\Faq::select('id', 'question', 'answer', 'sort_order')
            ->where('faqable_type', 'homepage')
            ->where('faqable_id', 1)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Transform data for JS
        $universityPartnersJson = $universityPartners
            ->groupBy('country')
            ->map(function ($partners, $country) {
                $first = $partners->first();
                return [
                    'id' => strtolower(str_replace(' ', '-', $country)),
                    'name' => $country,
                    'city' => $first->city ?? '',
                    'lat' => (float) ($first->latitude ?? 0),
                    'lng' => (float) ($first->longitude ?? 0),
                    'isHub' => (bool) ($first->is_hub ?? false),
                    'universities' => $partners->map(fn($p) => [
                        'name' => $p->name,
                        'country' => $p->country,
                        'recognition' => $p->recognition ?? '',
                        'programs' => $p->programs ?? [],
                    ])->values(),
                ];
            })
            ->values();

        $testimonialsJson = $testimonials->map(fn($t) => [
            'category' => strtoupper($t->company ?? 'STUDENT'),
            'name' => $t->name,
            'role' => $t->designation ?? '',
            'thumbnail' => $t->auto_thumbnail,
            'video' => $t->embed_url,
        ])->values();

        return array_merge($settings, [
            'featuredPrograms' => $featuredPrograms,
            'alumniLogos' => $alumniLogos,
            'accreditationLogos' => $accreditationLogos,
            'universityPartners' => $universityPartners,
            'universityPartnersJson' => $universityPartnersJson,
            'facultyInsights' => $facultyInsights,
            'events' => $events,
            'testimonials' => $testimonials,
            'testimonialsJson' => $testimonialsJson,
            'homepageFaqs' => $homepageFaqs,
        ]);
    });

    return view('pages.home', $data);
}

    public function ourStory()
    {
        return view('pages.our-story');
    }

    public function aboutUs()
    {
        return view('pages.about-us');
    }

    public function founder()
    {
        return view('pages.founder');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! We will contact you shortly.');
    }

    public function gallery()
    {
        return view('pages.gallery');
    }
}
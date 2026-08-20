<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Settings\HeroSettings;
use App\Settings\NumbersSettings;
use App\Settings\WhoWeAreSettings;
use App\Settings\CeoSettings;
use App\Settings\WhatIsMaverickSettings;
use App\Models\OurStoryTestimonial;
use App\Models\PartnerLogo;
use App\Models\FacultyInsight;
use App\Models\Event;
use App\Models\Testimonial;
use App\Settings\HowWeDoItSettings;
use App\Settings\WhyMaverickSettings;
use App\Settings\GlobalOpportunitiesSettings;
use App\Settings\PathwayProgramsSettings;
use App\Settings\GlobalOpportunitiesPageSettings;
use App\Models\GupPartnerUniversity;
use App\Models\PartnershipGalleryItem;
use App\Settings\GlobalPartnersBenefitsSettings;
use App\Settings\GlobalPartnersCardsSettings;
use App\Settings\GlobalPartnersHeroSettings;
use App\Settings\GlobalPartnersJourneySettings;
use App\Settings\GlobalPartnersOverviewSettings;
use App\Settings\GlobalPartnersSeoSettings;
use App\Settings\LeadershipHeroSettings;
use App\Settings\LeadershipLeadersSettings;
use App\Settings\LeadershipSeoSettings;
use App\Settings\GlobalPartnersWhySettings;
use App\Settings\CsrHeroSettings;
use App\Settings\CsrCommitmentSettings;
use App\Settings\CsrFocusSettings;
use App\Settings\CsrGallerySettings;
use App\Settings\CsrImpactSettings;
use App\Settings\CsrScholarshipSettings;
use App\Settings\CsrSeoSettings;

class PageController extends Controller
{
    public function home()
    {
        // NO CACHE - Direct data loading
        
        // Settings
        $settings = [
            'hero' => app(HeroSettings::class),
            'numbers' => app(NumbersSettings::class),
            'whoWeAre' => app(WhoWeAreSettings::class),
            'ceo' => app(CeoSettings::class),
            'whatIsMaverick' => app(WhatIsMaverickSettings::class),
            'howWeDoIt' => app(HowWeDoItSettings::class),
            'whyMaverick' => app(WhyMaverickSettings::class),
            'globalOpportunities' => app(GlobalOpportunitiesSettings::class),
        ];

        // Collections
        // Alumni section: Alumni type only
        $alumniLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->where('type', 'alumni')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Accreditations, Partnerships & Recognitions section: accreditation + alumni + recognition types
        $accreditationLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->whereIn('type', ['accreditation', 'recognition'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $facultyInsights = FacultyInsight::select('id', 'title', 'slug', 'badge', 'image_url', 'link_url', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $events = Event::select('id', 'title', 'description', 'event_date', 'event_type', 'location', 'link_url')
            ->where('is_active', true)
            ->orderBy('event_date', 'desc')
            ->limit(10)
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

        $testimonialsJson = $testimonials->map(fn($t) => [
            'category' => strtoupper($t->company ?? 'STUDENT'),
            'name' => $t->name,
            'role' => $t->designation ?? '',
            'thumbnail' => $t->auto_thumbnail,
            'video' => $t->embed_url,
        ])->values();

        $data = array_merge($settings, [
            'alumniLogos' => $alumniLogos,
            'accreditationLogos' => $accreditationLogos,
            'facultyInsights' => $facultyInsights,
            'events' => $events,
            'testimonials' => $testimonials,
            'testimonialsJson' => $testimonialsJson,
            'homepageFaqs' => $homepageFaqs,
        ]);

        return view('pages.home', $data);
    }

    public function ourStory()
    {
        // Our Story–specific settings
        $settings = [
            'hero' => app(\App\Settings\OurStoryHeroSettings::class),
            'beginning' => app(\App\Settings\OurStoryBeginningSettings::class),
            'today' => app(\App\Settings\OurStoryTodaySettings::class),
            'impact' => app(\App\Settings\OurStoryImpactSettings::class),
            'vision' => app(\App\Settings\OurStoryVisionSettings::class),
            // Shared with homepage
            'ceo' => app(CeoSettings::class),
        ];

        $ourStoryTestimonials = OurStoryTestimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        // Our Story–specific collections
        $timelines = \App\Models\OurStoryTimeline::select('id', 'year', 'title', 'description', 'icon_url', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $awards = \App\Models\OurStoryAward::select('id', 'title', 'image_url', 'sort_order', 'is_active')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $galleryImages = \App\Models\OurStoryGalleryImage::select('id', 'image_url', 'caption', 'category', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Shared collections (same sources as homepage)
        $accreditationLogos = PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
            ->whereIn('type', ['accreditation', 'recognition'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $facultyInsights = FacultyInsight::select('id', 'title', 'slug', 'badge', 'image_url', 'link_url', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $testimonials = Testimonial::select('id', 'name', 'designation', 'company', 'thumbnail_url', 'video_url', 'video_type', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(9)
            ->get();

        $testimonialsJson = $testimonials->map(fn ($t) => [
            'category' => strtoupper($t->company ?? 'STUDENT'),
            'name' => $t->name,
            'role' => $t->designation ?? '',
            'thumbnail' => $t->auto_thumbnail,
            'video' => $t->embed_url,
        ])->values();

        $data = array_merge($settings, [
            'timelines' => $timelines,
            'awards' => $awards,
            'galleryImages' => $galleryImages,
            'accreditationLogos' => $accreditationLogos,
            'facultyInsights' => $facultyInsights,
            'ourStoryTestimonials' => $ourStoryTestimonials,
            'testimonials' => $testimonials,
            'testimonialsJson' => $testimonialsJson,
            'ourStorySeo' => app(\App\Settings\OurStorySeoSettings::class),
        ]);

        return view('pages.our-story', $data);
    }

    public function dualMba()
    {
        return view('pages.dual-mba');
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

    public function csrCommunityImpact()
    {
        $focus = app(CsrFocusSettings::class);
        $focus->items = array_values($focus->items ?? []);

        $gallery = app(CsrGallerySettings::class);
        $gallery->items = array_values($gallery->items ?? []);

        $impact = app(CsrImpactSettings::class);
        $impact->items = array_values($impact->items ?? []);

        $scholarship = app(CsrScholarshipSettings::class);
        $scholarship->items = array_values($scholarship->items ?? []);

        return view('pages.csr-community-impact', [
            'hero' => app(CsrHeroSettings::class),
            'commitment' => app(CsrCommitmentSettings::class),
            'focus' => $focus,
            'gallery' => $gallery,
            'impact' => $impact,
            'scholarship' => $scholarship,
            'csrSeo' => app(CsrSeoSettings::class),
        ]);
    }

    public function pathwayPrograms()
    {
        $settings = app(GlobalOpportunitiesSettings::class);

        return view('pages.pathway-programs', [
            'hero' => app(PathwayProgramsSettings::class),
            'overview' => app(PathwayProgramsSettings::class),
            'globalOpportunities' => $settings,
            'cards' => array_values($settings->pathways ?? []),
        ]);
    }

    public function gallery()
    {
        $photos = \App\Models\MediaGalleryPhoto::select(
            'id',
            'image_url',
            'caption',
            'category',
            'size',
            'sort_order',
            'is_active'
        )
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $videos = \App\Models\MediaGalleryVideo::select(
            'id',
            'title',
            'video_url',
            'thumbnail_url',
            'duration',
            'category',
            'sort_order',
            'is_active'
        )
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Events power the shared upcoming-events section (same source as homepage).
        $events = \App\Models\Event::select('id', 'title', 'description', 'event_date', 'event_type', 'location', 'link_url')
            ->where('is_active', true)
            ->orderBy('event_date', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'photos' => $photos,
            'videos' => $videos,
            'events' => $events,
            'photoCount' => $photos->count(),
            'videoCount' => $videos->count(),
        ];

        return view('pages.media-gallery', $data);
    }

    public function globalUniversityPartners()
    {
        $galleryItems = PartnershipGalleryItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $categoryCounts = $galleryItems->countBy('category');

        $galleryCategories = collect([
            ['slug' => 'all', 'name' => 'All', 'count' => $galleryItems->count()],
        ]);

        foreach (PartnershipGalleryItem::CATEGORIES as $slug => $name) {
            $count = (int) ($categoryCounts[$slug] ?? 0);
            if ($count > 0) {
                $galleryCategories->push([
                    'slug' => $slug,
                    'name' => $name,
                    'count' => $count,
                ]);
            }
        }

        return view('pages.global-university-partners', [
            'hero' => app(GlobalPartnersHeroSettings::class),
            'overview' => app(GlobalPartnersOverviewSettings::class),
            'cards' => app(GlobalPartnersCardsSettings::class),
            'whyPartnerships' => app(GlobalPartnersWhySettings::class),
            'benefits' => app(GlobalPartnersBenefitsSettings::class),
            'journey' => app(GlobalPartnersJourneySettings::class),
            'globalPartnersSeo' => app(GlobalPartnersSeoSettings::class),
            'partnerUniversities' => GupPartnerUniversity::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'galleryItems' => $galleryItems,
            'galleryCategories' => $galleryCategories,
        ]);
    }

    /**
     * Leadership Board page — admin-managed via Leadership*Settings.
     */
    public function leadershipBoard()
    {
        $leaders = app(LeadershipLeadersSettings::class);
        $leaders->items = array_values($leaders->items ?? []);

        return view('pages.leadership', [
            'hero' => app(LeadershipHeroSettings::class),
            'leaders' => $leaders,
            'leadershipSeo' => app(LeadershipSeoSettings::class),
        ]);
    }
    /** /global-opportunities — Global Opportunities landing page */
    public function globalOpportunities()
    {
        $settings = app(GlobalOpportunitiesSettings::class);

        return view('pages.global-opportunities', [
            'hero' => app(GlobalOpportunitiesPageSettings::class),
            'pageSettings' => app(GlobalOpportunitiesPageSettings::class),
            'opportunityItems' => array_values($settings->opportunities ?? []),
        ]);
    }

    /** /events — editorial events page */
    public function events()
    {
        return view('pages.events');
    }

    /** /student-success — editorial student stories page */
    public function studentSuccess()
    {
        return view('pages.student-success');
    }

}
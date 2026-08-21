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
use App\Settings\DualMbaEmployersSettings;
use App\Settings\DualMbaFaqSettings;
use App\Settings\DualMbaFinalCtaSettings;
use App\Settings\DualMbaHeroSettings;
use App\Settings\DualMbaOverviewSettings;
use App\Settings\DualMbaProcessSettings;
use App\Settings\DualMbaSeoSettings;
use App\Settings\DualMbaSpecsSettings;
use App\Settings\DualMbaTestimonialsSettings;
use App\Settings\DualMbaTwiceSettings;
use App\Settings\DualMbaWhySettings;
use App\Settings\GbpAdmissionSettings;
use App\Settings\GbpAreasSettings;
use App\Settings\GbpComparisonSettings;
use App\Settings\GbpCostSettings;
use App\Settings\GbpDestinationsSettings;
use App\Settings\GbpDocumentsSettings;
use App\Settings\GbpExploreSettings;
use App\Settings\GbpFinalCtaSettings;
use App\Settings\GbpHeroSettings;
use App\Settings\GbpIntroSettings;
use App\Settings\GbpOverviewSettings;
use App\Settings\GbpPartnersSettings;
use App\Settings\GbpSeoSettings;
use App\Settings\GbpSnapshotSettings;
use App\Settings\GbpWhySettings;
use App\Settings\MpAudienceSettings;
use App\Settings\MpDestinationsSettings;
use App\Settings\MpFinalCtaSettings;
use App\Settings\MpHeroSettings;
use App\Settings\MpHowSettings;
use App\Settings\MpNoticeSettings;
use App\Settings\MpOverviewSettings;
use App\Settings\MpProcessSettings;
use App\Settings\MpRequirementsSettings;
use App\Settings\MpSeoSettings;
use App\Settings\MpWhySettings;
use App\Settings\EdutainmentExperiencesSettings;
use App\Settings\EdutainmentFaqSettings;
use App\Settings\EdutainmentFinalCtaSettings;
use App\Settings\EdutainmentHeroSettings;
use App\Settings\EdutainmentInstitutionsSettings;
use App\Settings\EdutainmentIntroSettings;
use App\Settings\EdutainmentLearningBeyondSettings;
use App\Settings\EdutainmentPackagesSettings;
use App\Settings\EdutainmentProgrammesSettings;
use App\Settings\EdutainmentSeoSettings;
use App\Settings\EdutainmentThemesSettings;
use App\Settings\EdutainmentWhatIsSettings;
use App\Settings\EdutainmentWhoForSettings;
use App\Settings\EdutainmentWhyChooseSettings;
use App\Settings\PathwayProgramsSeoSettings;
use App\Settings\GlobalOpportunitiesSeoSettings;
use App\Settings\MediaGallerySeoSettings;
use App\Settings\EventsSeoSettings;
use App\Settings\StudentSuccessSeoSettings;

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
            'homeSeo' => app(\App\Settings\HomepageSeoSettings::class),
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
        $hero = app(DualMbaHeroSettings::class);
        $hero->stats = array_values($hero->stats ?? []);
        $hero->ctas = array_values($hero->ctas ?? []);

        $overview = app(DualMbaOverviewSettings::class);
        $overview->cards = array_values($overview->cards ?? []);

        $twice = app(DualMbaTwiceSettings::class);
        $twice->slides = array_values($twice->slides ?? []);

        $why = app(DualMbaWhySettings::class);
        $why->cards = array_values($why->cards ?? []);

        $specs = app(DualMbaSpecsSettings::class);
        $specs->cards = array_values($specs->cards ?? []);

        $employers = app(DualMbaEmployersSettings::class);
        $employers->collage = array_values($employers->collage ?? []);
        $employers->items = array_values($employers->items ?? []);

        $testimonials = app(DualMbaTestimonialsSettings::class);
        $testimonials->items = array_values($testimonials->items ?? []);

        $process = app(DualMbaProcessSettings::class);
        $process->steps = array_values($process->steps ?? []);

        $faq = app(DualMbaFaqSettings::class);
        $faq->items = array_values($faq->items ?? []);

        $finalCta = app(DualMbaFinalCtaSettings::class);
        $finalCta->ctas = array_values($finalCta->ctas ?? []);

        return view('pages.dual-mba', [
            'hero' => $hero,
            'overview' => $overview,
            'twice' => $twice,
            'why' => $why,
            'specs' => $specs,
            'employers' => $employers,
            'testimonials' => $testimonials,
            'process' => $process,
            'faq' => $faq,
            'finalCta' => $finalCta,
            'dualMbaSeo' => app(DualMbaSeoSettings::class),
        ]);
    }

    public function globalBachelorsPathway()
    {
        $snapshot = app(GbpSnapshotSettings::class);
        $snapshot->cards = array_values($snapshot->cards ?? []);
        $snapshot->ctas = array_values($snapshot->ctas ?? []);

        $intro = app(GbpIntroSettings::class);
        $intro->paragraphs = array_values($intro->paragraphs ?? []);
        $intro->highlights = array_values($intro->highlights ?? []);

        $overview = app(GbpOverviewSettings::class);
        $overview->paragraphs = array_values($overview->paragraphs ?? []);
        $overview->stages = array_values($overview->stages ?? []);
        $overview->panel_stats = array_values($overview->panel_stats ?? []);

        $why = app(GbpWhySettings::class);
        $why->items = array_values($why->items ?? []);

        $explore = app(GbpExploreSettings::class);
        $explore->cards = array_values($explore->cards ?? []);

        $destinations = app(GbpDestinationsSettings::class);
        $destinations->items = array_values($destinations->items ?? []);

        $cost = app(GbpCostSettings::class);
        $cost->comparisons = array_values($cost->comparisons ?? []);

        $comparison = app(GbpComparisonSettings::class);
        $comparison->cards = array_values($comparison->cards ?? []);

        $areas = app(GbpAreasSettings::class);
        $areas->cards = array_values($areas->cards ?? []);

        $partners = app(GbpPartnersSettings::class);
        $partners->cards = array_values($partners->cards ?? []);

        $admission = app(GbpAdmissionSettings::class);
        $admission->eligibility = array_values($admission->eligibility ?? []);
        $admission->entry_requirements = array_values($admission->entry_requirements ?? []);

        $documents = app(GbpDocumentsSettings::class);
        $documents->groups = array_values($documents->groups ?? []);

        $finalCta = app(GbpFinalCtaSettings::class);
        $finalCta->ctas = array_values($finalCta->ctas ?? []);

        return view('pages.global-bachelors-pathway', [
            'hero' => app(GbpHeroSettings::class),
            'snapshot' => $snapshot,
            'intro' => $intro,
            'overview' => $overview,
            'why' => $why,
            'explore' => $explore,
            'destinations' => $destinations,
            'cost' => $cost,
            'comparison' => $comparison,
            'areas' => $areas,
            'partners' => $partners,
            'admission' => $admission,
            'documents' => $documents,
            'finalCta' => $finalCta,
            'gbpSeo' => app(GbpSeoSettings::class),
        ]);
    }

    public function mastersPathway()
    {
        $hero = app(MpHeroSettings::class);
        $hero->paragraphs = array_values($hero->paragraphs ?? []);
        $hero->ctas = array_values($hero->ctas ?? []);
        $hero->route_steps = array_values($hero->route_steps ?? []);

        $overview = app(MpOverviewSettings::class);
        $overview->paragraphs = array_values($overview->paragraphs ?? []);
        $overview->phases = array_values($overview->phases ?? []);

        $how = app(MpHowSettings::class);
        $how->phases = array_values(array_map(function ($phase) {
            $phase['facts'] = array_values($phase['facts'] ?? []);

            return $phase;
        }, $how->phases ?? []));

        $destinations = app(MpDestinationsSettings::class);
        $destinations->items = array_values($destinations->items ?? []);

        $why = app(MpWhySettings::class);
        $why->items = array_values($why->items ?? []);

        $audience = app(MpAudienceSettings::class);
        $audience->items = array_values($audience->items ?? []);

        $requirements = app(MpRequirementsSettings::class);
        $requirements->items = array_values($requirements->items ?? []);

        $process = app(MpProcessSettings::class);
        $process->steps = array_values($process->steps ?? []);

        $finalCta = app(MpFinalCtaSettings::class);
        $finalCta->ctas = array_values($finalCta->ctas ?? []);
        $finalCta->contacts = array_values($finalCta->contacts ?? []);

        return view('pages.masters-pathway', [
            'hero' => $hero,
            'overview' => $overview,
            'how' => $how,
            'destinations' => $destinations,
            'why' => $why,
            'audience' => $audience,
            'requirements' => $requirements,
            'process' => $process,
            'notice' => app(MpNoticeSettings::class),
            'finalCta' => $finalCta,
            'mpSeo' => app(MpSeoSettings::class),
        ]);
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
            'pathwayProgramsSeo' => app(PathwayProgramsSeoSettings::class),
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
            'mediaGallerySeo' => app(MediaGallerySeoSettings::class),
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
            'globalOpportunitiesSeo' => app(GlobalOpportunitiesSeoSettings::class),
        ]);
    }

    public function edutainment()
    {
        $intro = app(EdutainmentIntroSettings::class);
        $intro->ctas = array_values($intro->ctas ?? []);

        $whatIs = app(EdutainmentWhatIsSettings::class);
        $whatIs->items = array_values($whatIs->items ?? []);

        $learning = app(EdutainmentLearningBeyondSettings::class);
        $learning->cards = array_values($learning->cards ?? []);

        $whoFor = app(EdutainmentWhoForSettings::class);
        $whoFor->cards = array_values($whoFor->cards ?? []);
        $whoFor->ctas = array_values($whoFor->ctas ?? []);

        $programmes = app(EdutainmentProgrammesSettings::class);
        $programmes->cards = array_values($programmes->cards ?? []);
        $programmes->china_items = array_values($programmes->china_items ?? []);

        $themes = app(EdutainmentThemesSettings::class);
        $themes->cards = array_values($themes->cards ?? []);

        $experiences = app(EdutainmentExperiencesSettings::class);
        $experiences->categories = array_values($experiences->categories ?? []);

        $whyChoose = app(EdutainmentWhyChooseSettings::class);
        $whyChoose->cards = array_values($whyChoose->cards ?? []);

        $packages = app(EdutainmentPackagesSettings::class);
        $packages->items = array_values($packages->items ?? []);
        $packages->ctas = array_values($packages->ctas ?? []);

        $institutions = app(EdutainmentInstitutionsSettings::class);
        $institutions->tiles = array_values($institutions->tiles ?? []);
        $institutions->ctas = array_values($institutions->ctas ?? []);

        $faq = app(EdutainmentFaqSettings::class);
        $faq->items = array_values($faq->items ?? []);

        $finalCta = app(EdutainmentFinalCtaSettings::class);
        $finalCta->ctas = array_values($finalCta->ctas ?? []);

        return view('pages.edutainment', [
            'hero' => app(EdutainmentHeroSettings::class),
            'intro' => $intro,
            'whatIs' => $whatIs,
            'learning' => $learning,
            'whoFor' => $whoFor,
            'programmes' => $programmes,
            'themes' => $themes,
            'experiences' => $experiences,
            'whyChoose' => $whyChoose,
            'packages' => $packages,
            'institutions' => $institutions,
            'faq' => $faq,
            'finalCta' => $finalCta,
            'edutainmentSeo' => app(EdutainmentSeoSettings::class),
        ]);
    }

    /** /events — editorial events page */
    public function events()
    {
        return view('pages.events', [
            'eventsSeo' => app(EventsSeoSettings::class),
        ]);
    }

    /** /student-success — editorial student stories page */
    public function studentSuccess()
    {
        return view('pages.student-success', [
            'studentSuccessSeo' => app(StudentSuccessSeoSettings::class),
        ]);
    }

}
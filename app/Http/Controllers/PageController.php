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
use App\Models\StudentSuccessStory;
use App\Models\StudentSuccessVideo;
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
use App\Settings\EventsPageSettings;
use App\Settings\StudentSuccessPageSettings;
use App\Settings\MediaGalleryPageSettings;
use App\Support\PublicContentCache;

class PageController extends Controller
{
    public function home()
    {
        $settings = [
            'hero' => safe_settings(HeroSettings::class),
            'numbers' => safe_settings(NumbersSettings::class),
            'whoWeAre' => safe_settings(WhoWeAreSettings::class),
            'ceo' => safe_settings(CeoSettings::class),
            'whatIsMaverick' => safe_settings(WhatIsMaverickSettings::class),
            'howWeDoIt' => safe_settings(HowWeDoItSettings::class),
            'whyMaverick' => safe_settings(WhyMaverickSettings::class),
            'globalOpportunities' => safe_settings(GlobalOpportunitiesSettings::class),
            'homeSeo' => safe_settings(\App\Settings\HomepageSeoSettings::class),
        ];

        $collections = PublicContentCache::remember(PublicContentCache::HOMEPAGE, function () {
            $accreditationLogos = PublicContentCache::serializeRows(
                PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
                    ->whereIn('type', ['accreditation', 'recognition'])
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
            );

            $facultyInsights = PublicContentCache::serializeRows(
                FacultyInsight::select('id', 'title', 'faculty_role', 'country', 'content', 'image_url', 'image_url_asset_id', 'sort_order')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->limit(9)
                    ->get(),
                fn (FacultyInsight $insight) => [
                    'id' => $insight->id,
                    'title' => $insight->title,
                    'faculty_role' => $insight->faculty_role,
                    'country' => $insight->country,
                    'content' => $insight->content,
                    'image_url' => $insight->image_url ?? $insight->featuredImageUrl(),
                    'sort_order' => $insight->sort_order,
                ]
            );

            $events = PublicContentCache::serializeRows(
                Event::select('id', 'title', 'description', 'event_date', 'event_type', 'location', 'link_url')
                    ->where('is_active', true)
                    ->orderBy('event_date', 'desc')
                    ->limit(10)
                    ->get()
            );

            $testimonials = Testimonial::select('id', 'name', 'designation', 'company', 'thumbnail_url', 'video_url', 'video_type', 'sort_order')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(9)
                ->get();

            $homepageFaqs = PublicContentCache::serializeRows(
                \App\Models\Faq::select('id', 'question', 'answer', 'sort_order')
                    ->where('faqable_type', 'homepage')
                    ->where('faqable_id', 1)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
            );

            $testimonialsJson = $testimonials->map(fn ($t) => [
                'category' => strtoupper($t->company ?? 'STUDENT'),
                'name' => $t->name,
                'role' => $t->designation ?? '',
                'thumbnail' => $t->auto_thumbnail,
                'video' => $t->embed_url,
            ])->values()->all();

            return compact(
                'accreditationLogos',
                'facultyInsights',
                'events',
                'testimonialsJson',
                'homepageFaqs',
            );
        });

        $collections['accreditationLogos'] = PublicContentCache::hydrateRows($collections['accreditationLogos'] ?? []);
        $collections['facultyInsights'] = PublicContentCache::hydrateRows($collections['facultyInsights'] ?? []);
        $collections['homepageFaqs'] = PublicContentCache::hydrateRows($collections['homepageFaqs'] ?? []);
        $collections['events'] = PublicContentCache::hydrateRows(
            $collections['events'] ?? [],
            function ($row) {
                $data = is_array($row) ? $row : (array) $row;
                $data['event_date'] = PublicContentCache::hydrateDate($data['event_date'] ?? null);

                return (object) $data;
            }
        );
        $collections['testimonialsJson'] = collect($collections['testimonialsJson'] ?? [])->values();

        return view('pages.home', array_merge($settings, $collections));
    }

    public function ourStory()
    {
        // Our Story–specific settings
        $settings = [
            'hero' => safe_settings(\App\Settings\OurStoryHeroSettings::class),
            'beginning' => safe_settings(\App\Settings\OurStoryBeginningSettings::class),
            'today' => safe_settings(\App\Settings\OurStoryTodaySettings::class),
            'impact' => safe_settings(\App\Settings\OurStoryImpactSettings::class),
            'vision' => safe_settings(\App\Settings\OurStoryVisionSettings::class),
            // Shared with homepage
            'ceo' => safe_settings(CeoSettings::class),
        ];

        $collections = PublicContentCache::remember(PublicContentCache::OUR_STORY, function () {
            $ourStoryTestimonials = PublicContentCache::serializeRows(
                OurStoryTestimonial::query()
                    ->select('id', 'name', 'organisation', 'position', 'country', 'rating', 'testimonial', 'photo', 'sort_order')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
            );

            $timelines = PublicContentCache::serializeRows(
                \App\Models\OurStoryTimeline::select('id', 'year', 'title', 'description', 'icon_url', 'sort_order')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
            );

            $galleryImages = PublicContentCache::serializeRows(
                \App\Models\OurStoryGalleryImage::select('id', 'image_url', 'caption', 'category', 'sort_order')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
            );

            return compact(
                'ourStoryTestimonials',
                'timelines',
                'galleryImages',
            );
        });

        $collections['ourStoryTestimonials'] = PublicContentCache::hydrateRows($collections['ourStoryTestimonials'] ?? []);
        $collections['timelines'] = PublicContentCache::hydrateRows($collections['timelines'] ?? []);
        $collections['galleryImages'] = PublicContentCache::hydrateRows($collections['galleryImages'] ?? []);

        $data = array_merge($settings, $collections, [
            'ourStorySeo' => safe_settings(\App\Settings\OurStorySeoSettings::class),
        ]);

        $data['timelines'] = collect($data['timelines'] ?? []);
        $data['galleryImages'] = collect($data['galleryImages'] ?? []);
        $data['ourStoryTestimonials'] = collect($data['ourStoryTestimonials'] ?? []);

        return view('pages.our-story', $data);
    }

    public function dualMba()
    {
        $hero = safe_settings(DualMbaHeroSettings::class);
        $hero->stats = settings_array($hero->stats ?? []);
        $hero->ctas = settings_array($hero->ctas ?? []);

        $overview = safe_settings(DualMbaOverviewSettings::class);
        $overview->cards = settings_array($overview->cards ?? []);

        $twice = safe_settings(DualMbaTwiceSettings::class);
        $twice->slides = settings_array($twice->slides ?? []);

        $why = safe_settings(DualMbaWhySettings::class);
        $why->cards = settings_array($why->cards ?? []);

        $specs = safe_settings(DualMbaSpecsSettings::class);
        $specs->cards = settings_array($specs->cards ?? []);

        $employers = safe_settings(DualMbaEmployersSettings::class);
        $employers->collage = settings_array($employers->collage ?? []);
        $employers->items = settings_array($employers->items ?? []);

        $testimonials = safe_settings(DualMbaTestimonialsSettings::class);
        $testimonials->items = settings_array($testimonials->items ?? []);

        $process = safe_settings(DualMbaProcessSettings::class);
        $process->steps = settings_array($process->steps ?? []);

        $faq = safe_settings(DualMbaFaqSettings::class);
        $faq->items = settings_array($faq->items ?? []);

        $finalCta = safe_settings(DualMbaFinalCtaSettings::class);
        $finalCta->ctas = settings_array($finalCta->ctas ?? []);

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
            'dualMbaSeo' => safe_settings(DualMbaSeoSettings::class),
        ]);
    }

    public function globalBachelorsPathway()
    {
        $snapshot = safe_settings(GbpSnapshotSettings::class);
        $snapshot->cards = settings_array($snapshot->cards ?? []);
        $snapshot->ctas = settings_array($snapshot->ctas ?? []);

        $intro = safe_settings(GbpIntroSettings::class);
        $intro->paragraphs = settings_array($intro->paragraphs ?? []);
        $intro->highlights = settings_array($intro->highlights ?? []);

        $overview = safe_settings(GbpOverviewSettings::class);
        $overview->paragraphs = settings_array($overview->paragraphs ?? []);
        $overview->stages = settings_array($overview->stages ?? []);
        $overview->panel_stats = settings_array($overview->panel_stats ?? []);

        $why = safe_settings(GbpWhySettings::class);
        $why->items = settings_array($why->items ?? []);

        $explore = safe_settings(GbpExploreSettings::class);
        $explore->cards = settings_array($explore->cards ?? []);

        $destinations = safe_settings(GbpDestinationsSettings::class);
        $destinations->items = settings_array($destinations->items ?? []);

        $cost = safe_settings(GbpCostSettings::class);
        $cost->comparisons = settings_array($cost->comparisons ?? []);

        $comparison = safe_settings(GbpComparisonSettings::class);
        $comparison->cards = settings_array($comparison->cards ?? []);

        $areas = safe_settings(GbpAreasSettings::class);
        $areas->cards = settings_array($areas->cards ?? []);

        $partners = safe_settings(GbpPartnersSettings::class);
        $partners->cards = settings_array($partners->cards ?? []);

        $admission = safe_settings(GbpAdmissionSettings::class);
        $admission->eligibility = settings_array($admission->eligibility ?? []);
        $admission->entry_requirements = settings_array($admission->entry_requirements ?? []);

        $documents = safe_settings(GbpDocumentsSettings::class);
        $documents->groups = settings_array($documents->groups ?? []);

        $finalCta = safe_settings(GbpFinalCtaSettings::class);
        $finalCta->ctas = settings_array($finalCta->ctas ?? []);

        return view('pages.global-bachelors-pathway', [
            'hero' => safe_settings(GbpHeroSettings::class),
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
            'gbpSeo' => safe_settings(GbpSeoSettings::class),
        ]);
    }

    public function mastersPathway()
    {
        $hero = safe_settings(MpHeroSettings::class);
        $hero->paragraphs = settings_array($hero->paragraphs ?? []);
        $hero->ctas = settings_array($hero->ctas ?? []);
        $hero->route_steps = settings_array($hero->route_steps ?? []);

        $overview = safe_settings(MpOverviewSettings::class);
        $overview->paragraphs = settings_array($overview->paragraphs ?? []);
        $overview->phases = settings_array($overview->phases ?? []);

        $how = safe_settings(MpHowSettings::class);
        $how->phases = collect(settings_array($how->phases ?? []))
            ->filter(fn ($phase) => is_array($phase))
            ->map(fn ($phase) => array_merge($phase, [
                'facts' => settings_array(data_get($phase, 'facts')),
            ]))
            ->values()
            ->all();

        $destinations = safe_settings(MpDestinationsSettings::class);
        $destinations->items = settings_array($destinations->items ?? []);

        $why = safe_settings(MpWhySettings::class);
        $why->items = settings_array($why->items ?? []);

        $audience = safe_settings(MpAudienceSettings::class);
        $audience->items = settings_array($audience->items ?? []);

        $requirements = safe_settings(MpRequirementsSettings::class);
        $requirements->items = settings_array($requirements->items ?? []);

        $process = safe_settings(MpProcessSettings::class);
        $process->steps = settings_array($process->steps ?? []);

        $finalCta = safe_settings(MpFinalCtaSettings::class);
        $finalCta->ctas = settings_array($finalCta->ctas ?? []);
        $finalCta->contacts = settings_array($finalCta->contacts ?? []);

        return view('pages.masters-pathway', [
            'hero' => $hero,
            'overview' => $overview,
            'how' => $how,
            'destinations' => $destinations,
            'why' => $why,
            'audience' => $audience,
            'requirements' => $requirements,
            'process' => $process,
            'notice' => safe_settings(MpNoticeSettings::class),
            'finalCta' => $finalCta,
            'mpSeo' => safe_settings(MpSeoSettings::class),
        ]);
    }

    public function aboutUs()
    {
        return redirect()->route('our-story', [], 301);
    }

    public function csrCommunityImpact()
    {
        $focus = safe_settings(CsrFocusSettings::class);
        $focus->items = settings_array($focus->items ?? []);

        $gallery = safe_settings(CsrGallerySettings::class);
        $gallery->items = settings_array($gallery->items ?? []);

        $impact = safe_settings(CsrImpactSettings::class);
        $impact->items = settings_array($impact->items ?? []);

        $scholarship = safe_settings(CsrScholarshipSettings::class);
        $scholarship->items = settings_array($scholarship->items ?? []);

        return view('pages.csr-community-impact', [
            'hero' => safe_settings(CsrHeroSettings::class),
            'commitment' => safe_settings(CsrCommitmentSettings::class),
            'focus' => $focus,
            'gallery' => $gallery,
            'impact' => $impact,
            'scholarship' => $scholarship,
            'csrSeo' => safe_settings(CsrSeoSettings::class),
        ]);
    }

    public function pathwayPrograms()
    {
        $settings = safe_settings(GlobalOpportunitiesSettings::class);
        $pathwaySettings = safe_settings(PathwayProgramsSettings::class);

        return view('pages.pathway-programs', [
            'hero' => $pathwaySettings,
            'overview' => $pathwaySettings,
            'pathwayPrograms' => $pathwaySettings,
            'globalOpportunities' => $settings,
            'cards' => settings_array($settings->pathways ?? []),
            'pathwayProgramsSeo' => safe_settings(PathwayProgramsSeoSettings::class),
        ]);
    }

    public function gallery()
    {
        $collections = PublicContentCache::remember(PublicContentCache::MEDIA_GALLERY, function () {
            $photos = PublicContentCache::serializeRows(
                \App\Models\MediaGalleryPhoto::select(
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
                    ->get()
            );

            $videos = PublicContentCache::serializeRows(
                \App\Models\MediaGalleryVideo::select(
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
                    ->get(),
                fn (\App\Models\MediaGalleryVideo $video) => [
                    'id' => $video->id,
                    'title' => $video->title,
                    'video_url' => $video->video_url,
                    'thumbnail_url' => $video->thumbnail_url,
                    'auto_thumbnail' => $video->auto_thumbnail,
                    'duration' => $video->duration,
                    'category' => $video->category,
                    'sort_order' => $video->sort_order,
                    'is_active' => $video->is_active,
                ]
            );

            $events = PublicContentCache::serializeRows(
                Event::select('id', 'title', 'description', 'event_date', 'event_type', 'location', 'link_url')
                    ->where('is_active', true)
                    ->orderBy('event_date', 'desc')
                    ->limit(10)
                    ->get()
            );

            return [
                'photos' => $photos,
                'videos' => $videos,
                'events' => $events,
                'photoCount' => count($photos),
                'videoCount' => count($videos),
            ];
        });

        $collections['photos'] = PublicContentCache::hydrateRows($collections['photos'] ?? []);
        $collections['videos'] = PublicContentCache::hydrateRows($collections['videos'] ?? []);
        $collections['events'] = PublicContentCache::hydrateRows(
            $collections['events'] ?? [],
            function ($row) {
                $data = is_array($row) ? $row : (array) $row;
                $data['event_date'] = PublicContentCache::hydrateDate($data['event_date'] ?? null);

                return (object) $data;
            }
        );

        $data = array_merge($collections, [
            'mediaGalleryPage' => safe_settings(MediaGalleryPageSettings::class),
            'mediaGallerySeo' => safe_settings(MediaGallerySeoSettings::class),
        ]);

        $data['photos'] = collect($data['photos'] ?? []);
        $data['videos'] = collect($data['videos'] ?? []);

        return view('pages.media-gallery', $data);
    }

    public function globalUniversityPartners()
    {
        $collections = PublicContentCache::remember(PublicContentCache::GLOBAL_PARTNERS, function () {
            $galleryModels = PartnershipGalleryItem::query()
                ->select('id', 'image_url', 'category', 'badge', 'event_date', 'title', 'caption', 'size', 'sort_order')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $galleryItems = PublicContentCache::serializeRows(
                $galleryModels,
                fn (PartnershipGalleryItem $item) => [
                    'id' => $item->id,
                    'image_url' => $item->image_url,
                    'image' => $item->image,
                    'category' => $item->category,
                    'badge' => $item->badge,
                    'event_date' => $item->event_date,
                    'formatted_date' => $item->formatted_date,
                    'title' => $item->title,
                    'caption' => $item->caption,
                    'size' => $item->size,
                    'sort_order' => $item->sort_order,
                ]
            );

            $categoryCounts = $galleryModels->countBy('category');

            $galleryCategories = collect([
                ['slug' => 'all', 'name' => 'All', 'count' => $galleryModels->count()],
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

            $partnerUniversities = PublicContentCache::serializeRows(
                GupPartnerUniversity::query()
                    ->select('id', 'slug', 'name', 'abbreviation', 'country', 'flag_emoji', 'recognition', 'logo_url', 'cta_url', 'sort_order')
                    ->where('is_active', true)
                    ->hasPublicSlug()
                    ->orderBy('sort_order')
                    ->get(),
                fn (GupPartnerUniversity $uni) => [
                    'id' => $uni->id,
                    'slug' => $uni->slug,
                    'name' => $uni->name,
                    'abbreviation' => $uni->abbreviation,
                    'country' => $uni->country,
                    'flag_emoji' => $uni->flag_emoji,
                    'recognition' => $uni->recognition,
                    'logo_url' => $uni->logo_url,
                    'logo' => $uni->logo,
                    'display_abbreviation' => $uni->display_abbreviation,
                    'cta_url' => $uni->cta_url,
                    'cta_link' => $uni->cta_link,
                    'sort_order' => $uni->sort_order,
                ]
            );

            return [
                'galleryItems' => $galleryItems,
                'galleryCategories' => $galleryCategories->values()->all(),
                'partnerUniversities' => $partnerUniversities,
            ];
        });

        $collections['galleryItems'] = PublicContentCache::hydrateRows($collections['galleryItems'] ?? []);
        $collections['galleryCategories'] = collect($collections['galleryCategories'] ?? []);
        $collections['partnerUniversities'] = PublicContentCache::hydrateRows($collections['partnerUniversities'] ?? []);

        $whyPartnerships = safe_settings(GlobalPartnersWhySettings::class);
        $whyPartnerships->items = settings_array($whyPartnerships->items ?? []);

        $benefits = safe_settings(GlobalPartnersBenefitsSettings::class);
        $benefits->items = settings_array($benefits->items ?? []);

        return view('pages.global-university-partners', array_merge($collections, [
            'hero' => safe_settings(GlobalPartnersHeroSettings::class),
            'overview' => safe_settings(GlobalPartnersOverviewSettings::class),
            'cards' => safe_settings(GlobalPartnersCardsSettings::class),
            'whyPartnerships' => $whyPartnerships,
            'benefits' => $benefits,
            'journey' => safe_settings(GlobalPartnersJourneySettings::class),
            'globalPartnersSeo' => safe_settings(GlobalPartnersSeoSettings::class),
        ]));
    }

    /**
     * Leadership Board page — admin-managed via Leadership*Settings.
     */
    public function leadershipBoard()
    {
        $leaders = safe_settings(LeadershipLeadersSettings::class);
        $leaders->items = settings_array($leaders->items ?? []);

        return view('pages.leadership', [
            'hero' => safe_settings(LeadershipHeroSettings::class),
            'leaders' => $leaders,
            'leadershipSeo' => safe_settings(LeadershipSeoSettings::class),
        ]);
    }
    /** /global-opportunities — Global Opportunities landing page */
    public function globalOpportunities()
    {
        $settings = safe_settings(GlobalOpportunitiesSettings::class);

        return view('pages.global-opportunities', [
            'hero' => safe_settings(GlobalOpportunitiesPageSettings::class),
            'pageSettings' => safe_settings(GlobalOpportunitiesPageSettings::class),
            'opportunityItems' => settings_array($settings->opportunities ?? []),
            'globalOpportunitiesSeo' => safe_settings(GlobalOpportunitiesSeoSettings::class),
        ]);
    }

    public function edutainment()
    {
        $intro = safe_settings(EdutainmentIntroSettings::class);
        $intro->ctas = settings_array($intro->ctas ?? []);

        $whatIs = safe_settings(EdutainmentWhatIsSettings::class);
        $whatIs->items = settings_array($whatIs->items ?? []);

        $learning = safe_settings(EdutainmentLearningBeyondSettings::class);
        $learning->cards = settings_array($learning->cards ?? []);

        $whoFor = safe_settings(EdutainmentWhoForSettings::class);
        $whoFor->cards = settings_array($whoFor->cards ?? []);
        $whoFor->ctas = settings_array($whoFor->ctas ?? []);

        $programmes = safe_settings(EdutainmentProgrammesSettings::class);
        $programmes->cards = settings_array($programmes->cards ?? []);
        $programmes->china_items = settings_array($programmes->china_items ?? []);

        $themes = safe_settings(EdutainmentThemesSettings::class);
        $themes->cards = settings_array($themes->cards ?? []);

        $experiences = safe_settings(EdutainmentExperiencesSettings::class);
        $experiences->categories = settings_array($experiences->categories ?? []);

        $whyChoose = safe_settings(EdutainmentWhyChooseSettings::class);
        $whyChoose->cards = settings_array($whyChoose->cards ?? []);

        $packages = safe_settings(EdutainmentPackagesSettings::class);
        $packages->items = settings_array($packages->items ?? []);
        $packages->ctas = settings_array($packages->ctas ?? []);

        $institutions = safe_settings(EdutainmentInstitutionsSettings::class);
        $institutions->tiles = settings_array($institutions->tiles ?? []);
        $institutions->ctas = settings_array($institutions->ctas ?? []);

        $faq = safe_settings(EdutainmentFaqSettings::class);
        $faq->items = settings_array($faq->items ?? []);

        $finalCta = safe_settings(EdutainmentFinalCtaSettings::class);
        $finalCta->ctas = settings_array($finalCta->ctas ?? []);

        return view('pages.edutainment', [
            'hero' => safe_settings(EdutainmentHeroSettings::class),
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
            'edutainmentSeo' => safe_settings(EdutainmentSeoSettings::class),
        ]);
    }

    /** /events — editorial events page */
    public function events()
    {
        $events = PublicContentCache::hydrateRows(
            PublicContentCache::remember(PublicContentCache::EVENTS, function () {
                return PublicContentCache::serializeRows(
                    Event::select('id', 'title', 'description', 'event_date', 'event_type', 'location', 'link_url')
                        ->where('is_active', true)
                        ->orderBy('event_date', 'asc')
                        ->get()
                );
            }),
            function ($row) {
                $data = is_array($row) ? $row : (array) $row;
                $data['event_date'] = PublicContentCache::hydrateDate($data['event_date'] ?? null);

                return (object) $data;
            }
        );

        return view('pages.events', [
            'eventsPage' => safe_settings(EventsPageSettings::class),
            'events' => collect($events ?? []),
            'eventsSeo' => safe_settings(EventsSeoSettings::class),
        ]);
    }

    /** /student-success — editorial student stories page */
    public function studentSuccess()
    {
        $batch = 9;
        $storyQuery = StudentSuccessStory::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id');
        $storyTotal = (clone $storyQuery)->count();
        $stories = $storyQuery->limit($batch)->get()->map->cardPayload()->values();

        $videoQuery = StudentSuccessVideo::query()
            ->where('is_active', true)
            ->whereNotNull('youtube_url')
            ->where('youtube_url', '!=', '')
            ->orderBy('sort_order')
            ->orderBy('id');
        $videoTotal = (clone $videoQuery)->count();
        $videoStories = $videoQuery->limit($batch)->get()->map->cardPayload()->values();

        return view('pages.student-success', [
            'studentSuccessPage' => safe_settings(StudentSuccessPageSettings::class),
            'studentSuccessSeo' => safe_settings(StudentSuccessSeoSettings::class),
            'stories' => collect($stories ?? []),
            'storyTotal' => $storyTotal ?? 0,
            'videoStories' => collect($videoStories ?? []),
            'videoTotal' => $videoTotal ?? 0,
        ]);
    }

    public function studentSuccessStories(Request $request)
    {
        return $this->studentSuccessBatch(
            StudentSuccessStory::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            (int) $request->query('offset', 0),
            'pages.student-success._story-cards',
            'stories'
        );
    }

    public function studentSuccessVideos(Request $request)
    {
        return $this->studentSuccessBatch(
            StudentSuccessVideo::query()
                ->where('is_active', true)
                ->whereNotNull('youtube_url')
                ->where('youtube_url', '!=', '')
                ->orderBy('sort_order')
                ->orderBy('id'),
            (int) $request->query('offset', 0),
            'pages.student-success._video-cards',
            'videos'
        );
    }

    private function studentSuccessBatch($query, int $offset, string $view, string $assign): \Illuminate\Http\JsonResponse
    {
        $batch = 9;
        $offset = max(0, $offset);
        $total = (clone $query)->count();
        $items = $query->offset($offset)->limit($batch)->get()->map->cardPayload()->values();

        return response()->json([
            'html' => view($view, [$assign => $items])->render(),
            'has_more' => ($offset + $items->count()) < $total,
            'next_offset' => $offset + $items->count(),
        ]);
    }

}
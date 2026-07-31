<?php

namespace App\Http\Controllers;

use App\Models\FacultyInsight;
use App\Models\OurStoryAward;
use App\Models\OurStoryTimeline;
use App\Models\OurStoryGalleryImage;
use App\Models\PartnerLogo;
use App\Models\Testimonial;
use App\Settings\CeoSettings;
use App\Settings\FinalCtaSettings;
use App\Settings\OurStoryBeginningSettings;
use App\Settings\OurStoryHeroSettings;
use App\Settings\OurStoryImpactSettings;
use App\Settings\OurStoryTodaySettings;
use App\Settings\OurStoryVisionSettings;
use Illuminate\View\View;

class OurStoryController extends Controller
{
    public function index(): View
    {
        $hero = app(OurStoryHeroSettings::class);
        $beginning = app(OurStoryBeginningSettings::class);
        $today = app(OurStoryTodaySettings::class);
        $impact = app(OurStoryImpactSettings::class);
        $vision = app(OurStoryVisionSettings::class);
        $ceo = app(CeoSettings::class);
        // $finalCta = app(FinalCtaSettings::class);

        $timelines = OurStoryTimeline::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $awards = OurStoryAward::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $galleryImages = OurStoryGalleryImage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $accreditationLogos = PartnerLogo::query()
            ->select('id', 'name', 'logo_url', 'sort_order')
            ->where('type', 'accreditation')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $facultyInsights = FacultyInsight::query()
            ->select('id', 'title', 'slug', 'badge', 'image_url', 'link_url', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $testimonials = Testimonial::query()
            ->select('id', 'name', 'designation', 'company', 'thumbnail_url', 'video_url', 'video_type', 'sort_order')
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

        return view('pages.our-story', compact(
            'hero',
            'beginning',
            'today',
            'impact',
            'vision',
            'ceo',
            'finalCta',
            'timelines',
            'awards',
            'galleryImages',
            'accreditationLogos',
            'facultyInsights',
            'testimonials',
            'testimonialsJson',
        ));
    }
}

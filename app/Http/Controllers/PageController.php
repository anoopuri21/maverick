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
            ->whereIn('type', ['accreditation', 'alumni', 'recognition'])
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
            ->where('type', 'accreditation')
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
        $focusAreas = [
            [
                'title' => 'Education & Skill Development',
                'icon' => 'graduation-cap',
                'activities' => [
                    'Free educational workshops',
                    'Career guidance sessions',
                    'Teacher training programs',
                    'Student mentoring initiatives',
                ],
            ],
            [
                'title' => 'Community Engagement',
                'icon' => 'globe',
                'activities' => [
                    'Community awareness campaigns',
                    'Youth development programs',
                    'Local community partnerships',
                    'Volunteering activities',
                ],
            ],
            [
                'title' => 'Sustainability & Environment',
                'icon' => 'leaf',
                'activities' => [
                    'Paperless learning initiatives',
                    'Green office practices',
                    'Environmental awareness programs',
                    'Sustainability workshops',
                ],
            ],
            [
                'title' => 'Inclusion & Accessibility',
                'icon' => 'handshake',
                'activities' => [
                    'Scholarships',
                    'Educational support programs',
                    'Equal learning opportunities',
                    'Professional development access',
                ],
            ],
        ];

        $galleryActivities = [
            [
                'title' => 'Teachers Training Workshop 2026',
                'image' => 'https://images.pexels.com/photos/10498800/pexels-photo-10498800.jpeg',
                'description' => 'Empowering educators through innovative classroom engagement strategies.',
            ],
            [
                'title' => 'Student Career Development Sessions',
                'image' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&q=80&w=1000',
                'description' => 'Supporting students with career planning and employability skills.',
            ],
            [
                'title' => 'Community Education Initiatives',
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=1000',
                'description' => 'Providing learning opportunities to underserved communities.',
            ],
            [
                'title' => 'Sustainability Awareness Campaign',
                'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=1000',
                'description' => 'Promoting responsible and environmentally conscious practices.',
            ],
        ];

        $impactNumbers = [
            [
                'value' => 500,
                'suffix' => '+',
                'label' => 'Educators Trained',
            ],
            [
                'value' => 1000,
                'suffix' => '+',
                'label' => 'Learners Supported',
            ],
            [
                'value' => 50,
                'suffix' => '+',
                'label' => 'Community Activities',
            ],
            [
                'value' => 20,
                'suffix' => '+',
                'label' => 'CSR Initiatives Conducted',
            ],
        ];

        $scholarshipActivities = [
            'Teachers Training Workshops',
            'Free Masterclasses',
            'Student Development Sessions',
            'Career Guidance Programs',
            'Professional Development Webinars',
            'Educational Partnerships Benefiting Communities',
            'Scholarship Programs',
            'Industry Awareness Events',
            'Women Leadership Initiatives',
            'Youth Entrepreneurship Workshops',
        ];

        return view('pages.csr-community-impact', compact('focusAreas', 'galleryActivities', 'impactNumbers', 'scholarshipActivities'));
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
}
@extends('layouts.app')

@section('title', "Global Bachelor's Pathway Programme | Study Bachelor's in Europe")
@section('meta_description', 'Start your Bachelor\'s journey with Maverick Business Academy London and progress to partner universities in Hungary, Romania, or Moldova. Explore affordable European Bachelor\'s pathways with credit transfer, visa support, and career guidance.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-bachelors-pathway.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-gbp">

@php
    $hero = (object)[
        'tag' => 'GLOBAL BACHELOR\'S PATHWAY',
        'heading' => 'Global Bachelor\'s',
        'heading_italic' => 'Pathway Programme',
        'sub_heading' => 'Your gateway to a globally recognised European Bachelor\'s degree — structured pathways, flexible learning, and international progression through Maverick Business Academy London.',
        'background_image' => 'https://images.pexels.com/photos/1462630/pexels-photo-1462630.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600',
    ];

    $snapshot = (object)[
        'cards' => collect([
            (object)[
                'icon' => 'map',
                'title' => 'Study Route',
                'items' => ['UAE', 'Hybrid', 'Online', 'European University Progression'],
            ],
            (object)[
                'icon' => 'map-pin',
                'title' => 'Destinations',
                'items' => ['Hungary', 'Romania', 'Moldova'],
            ],
            (object)[
                'icon' => 'target',
                'title' => 'Focus International Pathways',
                'items' => ['European Degree', 'Credit Transfer'],
            ],
        ]),
    ];

    $intro = (object)[
        'tag' => 'YOUR PATHWAY',
        'heading_line1' => 'Your Structured Route to a',
        'heading_line2' => 'Globally Recognised European',
        'heading_italic' => 'Bachelor\'s Degree',
        'paragraphs' => [
            'Begin your Bachelor\'s Degree Pathway in UAE with Maverick Business Academy London and progress towards an internationally recognised European Bachelor\'s degree through our partner university pathways in Hungary, Romania, and Moldova.',
            'Designed for students and parents seeking a smarter, affordable, and globally focused study route, the Maverick Bachelor\'s Global Pathway helps learners begin their academic journey with structured support and progress confidently towards international university completion, leading to an Affordable Bachelor\'s Degree in Europe.',
        ],
        'highlights' => collect([
            (object)['icon' => 'globe', 'label' => 'International Pathways', 'value' => 'Study in UAE, progress to Europe'],
            (object)['icon' => 'award', 'label' => 'Recognised Degree', 'value' => 'Globally accepted European qualification'],
            (object)['icon' => 'credit-card', 'label' => 'Cost Effective', 'value' => 'Affordable alternative to full overseas study'],
            (object)['icon' => 'users', 'label' => 'Full Support', 'value' => 'Visa guidance, career counselling, academic mentoring'],
        ]),
    ];

    $overview = (object)[
        'tag' => 'PROGRAMME OVERVIEW',
        'heading' => 'What is the Maverick',
        'heading_italic' => 'Bachelor\'s Pathway Programme?',
        'paragraphs' => [
            'The Maverick Bachelor\'s Pathway Programme is a structured academic route that allows students to begin their bachelor\'s journey with Maverick Business Academy London and progress to selected international partner universities in Europe.',
            'Students complete the initial academic stages through Maverick and then move towards the final bachelor\'s degree through partner universities in Hungary, Romania, or Moldova. Ideal for students seeking flexible learning, credit transfer guidance, visa support, and career-focused academic support.',
        ],
        'stats' => collect([
            (object)['number' => '~6 Mo', 'label' => 'LEVEL 4 DIPLOMA'],
            (object)['number' => '~6 Mo', 'label' => 'LEVEL 5 DIPLOMA'],
            (object)['number' => '', 'label' => 'Progression to International Partner University'],
            (object)['number' => '', 'label' => 'International Bachelor\'s Degree'],
        ]),
        'quotes' => 'This route is ideal for students who want an international degree pathway with flexible learning, credit transfer guidance, visa support, and career-focused academic support.',
    ];

    $whyPathway = (object)[
        'tag' => 'OUR VALUE',
        'heading' => 'Why Choose This',
        'heading_italic' => 'Pathway Programme?',
        'quote' => 'A smarter alternative to the traditional overseas route — start with Maverick, progress internationally at the right stage.',
        'paragraph' => 'The Maverick Bachelor\'s Global Pathway is designed to give students a smarter alternative to the traditional 4-year overseas study route. Instead of moving abroad from year one and paying higher international tuition and living costs, students can begin their journey with Maverick through a Bachelor\'s Degree with Credit Transfer route and progress internationally at a later stage. This creates a more structured, affordable, and globally focused pathway towards completing a European Bachelor\'s degree.',
        'items' => collect([
            (object)['icon' => 'clock', 'title' => 'Save Time', 'description' => 'The pathway can help students save up to one year compared with the traditional Bachelor\'s route.'],
            (object)['icon' => 'award', 'title' => 'Earn 240 UK Credits', 'description' => 'Students complete structured UK credit-based qualifications before progressing to the university stage.'],
            (object)['icon' => 'shuffle', 'title' => 'Flexible Learning Route', 'description' => 'Students can begin their studies through flexible learning before moving into the final university progression stage.'],
            (object)['icon' => 'graduation-cap', 'title' => 'Direct University Progression', 'description' => 'The programme is designed to support progression to selected partner universities.'],
            (object)['icon' => 'trending-down', 'title' => 'Cost-Effective Study Route', 'description' => 'Students and parents can reduce overall study cost compared with starting the full overseas route from year one.'],
        ]),
    ];

    $exploreEurope = collect([
        (object)[
            'flag' => '🇭🇺',
            'country' => 'Hungary',
            'type' => 'Premium European Pathway',
            'university' => 'International Business School, Budapest',
            'highlights' => ['International study experience in Budapest', 'Dual degree opportunities', '100% placement assistance', 'Erasmus+ student exchange'],
        ],
        (object)[
            'flag' => '🇷🇴',
            'country' => 'Romania',
            'type' => 'Affordable European Pathway',
            'university' => 'Aurel Vlaicu University',
            'highlights' => ['Affordable tuition fees', 'One-year completion route', 'Lower cost of living', 'Direct university progression'],
        ],
        (object)[
            'flag' => '🇲🇩',
            'country' => 'Moldova',
            'type' => 'Affordable European Pathway',
            'university' => 'USPEE, Moldova',
            'highlights' => ['Lower overall study cost', 'Reduced study duration', 'Student visa guidance', 'Flexible pathway structure'],
        ],
    ]);

    $stages = collect([
        (object)['year' => '01', 'title' => 'Level 4 Diploma', 'duration' => 'Approx. 6 Months', 'description' => 'Students begin with a Level 4 Diploma designed to build the academic foundation required for bachelor\'s progression.'],
        (object)['year' => '02', 'title' => 'Level 5 Diploma', 'duration' => 'Approx. 6 Months', 'description' => 'Students then complete a Level 5 Diploma, strengthening their academic knowledge and preparing them for international university progression.'],
        (object)['year' => '03', 'title' => 'International University Progression', 'duration' => 'Partner University Stage', 'description' => 'After completing the required academic stages, students progress to an international partner university in Europe.'],
        (object)['year' => '04', 'title' => 'International Bachelor\'s Degree', 'duration' => 'Final Outcome', 'description' => 'Upon successful completion of the final university stage, students receive an internationally recognised bachelor\'s degree from the partner university.'],
    ]);

    $destinations = collect([
        (object)[
            'slug' => 'hungary',
            'position' => 'right',
            'name' => 'Hungary',
            'label' => 'PREMIUM EUROPEAN PATHWAY',
            'university' => 'International Business School, Budapest',
            'description' => 'Hungary is a strong choice for students seeking a premium European study experience, international exposure, and a vibrant student-friendly environment. Through Maverick\'s pathway, students can study Bachelor\'s in Hungary while benefiting from structured academic guidance, career-focused support, and opportunities for global growth. Through Maverick\'s Hungary pathway, students can progress to International Business School, Budapest, one of the leading international business schools in Europe.',
            'image' => 'https://images.pexels.com/photos/3722721/pexels-photo-3722721.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760',
            'points' => [
                'International study experience in Budapest',
                'Dual degree opportunities with University of Buckingham (UK) and Dublin Business School (Ireland)',
                '100% placement assistance and career mentoring',
                'Internship support connected to KPMG, Microsoft, Amazon and more',
                '9–12 month post-study work opportunity',
                'Access to 27+ Schengen countries',
                'Erasmus+ student exchange opportunity',
                'No IELTS / TOEFL required',
            ],
            'best_for' => 'Students looking for a premium European business education pathway with stronger international exposure and career development support.',
        ],
        (object)[
            'slug' => 'romania',
            'position' => 'left',
            'name' => 'Romania',
            'label' => 'AFFORDABLE EUROPEAN PATHWAY',
            'university' => 'Aurel Vlaicu University, Romania',
            'description' => 'Attractive for students who want a European bachelor\'s degree pathway with affordable tuition, lower living costs, and direct university progression. Benefit from structured academic support, reduced study duration, and student visa guidance.',
            'image' => 'https://images.pexels.com/photos/207684/pexels-photo-207684.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760',
            'points' => [
                'Affordable tuition fees',
                'One-year university completion route',
                'Lower cost of living',
                'Internationally recognised European degree',
                'Direct university progression',
                'Reduced overall study duration',
                'Strong return on investment',
                'Student visa guidance',
            ],
            'best_for' => 'Students looking for an affordable European bachelor\'s route with reduced study duration and practical academic progression.',
        ],
        (object)[
            'slug' => 'moldova',
            'position' => 'right',
            'name' => 'Moldova',
            'label' => 'AFFORDABLE EUROPEAN PATHWAY',
            'university' => 'USPEE, Moldova',
            'description' => 'Another affordable European pathway option for students looking for a cost-effective route to complete their bachelor\'s degree. Progress to USPEE, Moldova through Maverick\'s structured pathway.',
            'image' => 'https://images.pexels.com/photos/1519088/pexels-photo-1519088.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760',
            'points' => [
                'Affordable tuition fees',
                'Lower overall study cost',
                'Reduced study duration',
                'International university progression',
                'Student visa guidance',
                'Career and academic support',
                'Flexible pathway structure',
            ],
            'best_for' => 'Students seeking an affordable and practical European bachelor\'s progression route.',
        ],
    ]);

    $costTime = (object)[
        'tag' => 'COST & TIME ADVANTAGE',
        'heading' => 'A Smarter Alternative to the',
        'heading_italic' => 'Traditional 4-Year Route',
        'description' => 'A traditional bachelor\'s route usually requires students to study overseas from year one, which can increase total tuition fees, living costs, and overall time commitment. With Maverick\'s pathway, students can begin their academic journey through Maverick and progress to Europe for the final university stage.',
        'closing' => 'This makes the pathway a practical option for students and parents who want a balance of affordability, international exposure, academic progression, and career value.',
        'comparisons' => collect([
            (object)['label' => 'Traditional Route', 'value' => '4 Years', 'variant' => 'muted'],
            (object)['label' => 'Maverick Pathway', 'value' => '~3 years', 'variant' => 'accent'],
            (object)['label' => 'Time Saving — Hungary', 'value' => 'Up to 12 Months', 'variant' => 'muted'],
            (object)['label' => 'Time Saving — Romania & Moldova', 'value' => 'Up to 24 Months', 'variant' => 'muted'],
        ]),
    ];

    $pathwayAreas = collect([
        (object)[
            'icon' => 'briefcase',
            'title' => 'Business & Management',
            'description' => 'Careers in management, entrepreneurship, marketing, finance, operations, international business, or corporate leadership.',
            'items' => ['Business Administration', 'Business Management', 'International Business', 'Marketing', 'Human Resource Management', 'Finance & Accounting', 'Entrepreneurship', 'Business Analytics'],
        ],
        (object)[
            'icon' => 'cpu',
            'title' => 'IT & Data',
            'description' => 'Enter fast-growing digital and technology-driven careers with globally in-demand skills.',
            'items' => ['Information Technology', 'Management Information Systems', 'Computer Science', 'Data Science', 'Business Analytics', 'AI & Data Analytics'],
        ],
        (object)[
            'icon' => 'compass',
            'title' => 'Hospitality & Tourism',
            'description' => 'Build careers in tourism, hospitality, events, aviation services, hotel management, or international service industries.',
            'items' => ['Hospitality Management', 'Tourism Management', 'International Hospitality & Tourism', 'Service Management'],
        ],
        (object)[
            'icon' => 'globe',
            'title' => 'International & European Studies',
            'description' => 'Global exposure and European academic progression with an internationally focused curriculum.',
            'items' => ['International Relations', 'International Business Management', 'European Business Studies', 'Business & Administration'],
        ],
    ]);

    $partnerOptions = collect([
        (object)[
            'code' => 'HU',
            'name' => 'Hungary — Premium European Pathway',
            'description' => 'Progress to International Business School, Budapest through Maverick\'s premium European route.',
            'best_for' => ['Business Management', 'International Business', 'Marketing', 'Finance', 'Data Analytics', 'AI & Business', 'Entrepreneurship'],
        ],
        (object)[
            'code' => 'RO',
            'name' => 'Romania — Affordable European Pathway',
            'description' => 'Progress to Aurel Vlaicu University, Romania through Maverick\'s affordable European pathway.',
            'best_for' => ['Business Administration', 'Management', 'Information Technology', 'Data Science', 'Hospitality & Tourism', 'International Business'],
        ],
        (object)[
            'code' => 'MD',
            'name' => 'Moldova — Affordable European Pathway',
            'description' => 'Progress to USPEE, Moldova through Maverick\'s affordable European pathway.',
            'best_for' => ['Business Administration', 'Management', 'Information Technology', 'Tourism & Hospitality', 'General Business Studies'],
        ],
    ]);

    $eligibility = collect([
        'High school / Grade 12 graduates',
        'Students who want to study bachelor\'s abroad',
        'Students looking for a European bachelor\'s degree',
        'Students seeking a cost-effective alternative to studying overseas from year one',
        'Students interested in credit transfer and international university progression',
        'Working professionals who want to complete their bachelor\'s degree pathway',
    ]);

    $entryRequirements = collect([
        'High school / Grade 12 certificate or equivalent',
        'Academic transcripts / mark sheets',
        'Passport copy',
        'Passport-size photograph',
        'Updated CV, if applicable',
        'English language evidence, if required',
        'Completed application form',
        'Any additional documents required by the partner university or visa process',
    ]);

    $documentGroups = collect([
        (object)[
            'icon' => 'user',
            'title' => 'Personal Documents',
            'items' => ['Passport copy', 'Passport-size photograph', 'Emirates ID copy (if applicable)', 'Updated CV (if applicable)'],
        ],
        (object)[
            'icon' => 'book-open',
            'title' => 'Academic Documents',
            'items' => ['High school / Grade 12 certificate', 'Academic transcripts / mark sheets', 'Previous diploma or college documents (if applicable)', 'English language documents (if required)'],
        ],
        (object)[
            'icon' => 'file-check',
            'title' => 'Additional Documents for Visa Stage',
            'items' => ['Bank statement or financial proof (if required)', 'Accommodation details (if required)', 'Travel insurance (if required)', 'Medical documents (if required)', 'Any additional documents requested by the embassy or university'],
        ],
    ]);
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION (Cinematic)
═══════════════════════════════════════════ --}}
<section class="cinematic-hero" aria-label="Global Bachelor's Pathway Hero" data-testid="gbp-hero">
    <div class="cinematic-hero__bg" aria-hidden="true">
        <div class="cinematic-hero__bg-image" style="background-image: url('{{ $hero->background_image }}')"></div>
        <div class="cinematic-hero__gradient"></div>
        <div class="cinematic-hero__noise"></div>
        <div class="cinematic-hero__shapes">
            <svg class="cinematic-hero__shape cinematic-hero__shape--1" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="80" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--2" viewBox="0 0 300 300" fill="none">
                <circle cx="150" cy="150" r="120" stroke="rgba(220,38,38,0.2)" stroke-width="1"/>
            </svg>
            <svg class="cinematic-hero__shape cinematic-hero__shape--3" viewBox="0 0 100 100" fill="none">
                <rect x="10" y="10" width="80" height="80" rx="8" stroke="rgba(255,255,255,0.15)" stroke-width="1" transform="rotate(20 50 50)"/>
            </svg>
        </div>
        <div class="cinematic-hero__particles">
            @for($i = 0; $i < 6; $i++)
                <div class="cinematic-hero__particle"></div>
            @endfor
        </div>
        <div class="cinematic-hero__scanline"></div>
        <div class="cinematic-hero__corners">
            <div class="cinematic-hero__corner cinematic-hero__corner--tl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--tr"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--bl"></div>
            <div class="cinematic-hero__corner cinematic-hero__corner--br"></div>
        </div>
    </div>

    <div class="container cinematic-hero__content">
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            {{ $hero->tag }}
        </span>
        <h1 class="cinematic-hero__title">
            {{ $hero->heading }}<br>
            <em>{{ $hero->heading_italic }}</em>
        </h1>
        <p class="cinematic-hero__description">{{ $hero->sub_heading }}</p>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SNAPSHOT — Study Route / Destinations / Focus
═══════════════════════════════════════════ --}}
<section class="gbp-snapshot section-wrapper section--light" aria-label="Programme Snapshot" data-testid="gbp-snapshot">
    <div class="container">
        <div class="gbp-snapshot__grid">
            @foreach($snapshot->cards as $card)
            <article class="gbp-snapshot-card" data-testid="gbp-snapshot-{{ $loop->iteration }}">
                <div class="gbp-snapshot-card__icon" aria-hidden="true">
                    <span data-lucide="{{ $card->icon }}"></span>
                </div>
                <h3 class="gbp-snapshot-card__title card-title">{{ $card->title }}</h3>
                <ul class="gbp-snapshot-card__list">
                    @foreach($card->items as $item)
                    <li>
                        <span data-lucide="check" aria-hidden="true"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>

        <div class="gbp-snapshot__ctas fade-up">
            <a href="#enquire" class="btn btn--primary" data-testid="snapshot-cta-enquire">Enquire Now</a>
            <a href="#advisor" class="btn btn--ghost" data-testid="snapshot-cta-advisor">Speak to an Advisor</a>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 1: YOUR STRUCTURED ROUTE
     Bold statement band — Dark background
═══════════════════════════════════════════ --}}
<section class="gbp-intro" aria-label="Your Structured Route" data-testid="gbp-intro">
    <div class="container">
        <div class="gbp-intro__content">
            <span class="gbp-intro__label">
                <span class="gbp-intro__label-line"></span>
                {{ $intro->tag }}
            </span>
            <h2 class="gbp-intro__heading">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $intro->heading_line1 }}</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $intro->heading_line2 }}</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $intro->heading_italic }}</em></span></span>
            </h2>
            @foreach($intro->paragraphs as $paragraph)
            <p class="gbp-intro__paragraph fade-up">{{ $paragraph }}</p>
            @endforeach
        </div>

        <div class="gbp-intro__highlights">
            @foreach($intro->highlights as $highlight)
            <div class="gbp-intro-card">
                <div class="gbp-intro-card__icon">
                    <span data-lucide="{{ $highlight->icon }}"></span>
                </div>
                <div class="gbp-intro-card__content">
                    <span class="gbp-intro-card__label">{{ $highlight->label }}</span>
                    <span class="gbp-intro-card__value">{{ $highlight->value }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 2: WHAT IS THE PATHWAY PROGRAMME
═══════════════════════════════════════════ --}}
<section class="gbp-overview section-wrapper section--light" aria-label="Programme Overview" data-testid="gbp-overview">
    <div class="container">
        <div class="gbp-overview__grid">
            <div class="gbp-overview__main">
                <span class="section-label"><span>{{ $overview->tag }}</span></span>
                <h2 class="gbp-overview__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $overview->heading }}</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $overview->heading_italic }}</em></span></span>
                </h2>
                @foreach($overview->paragraphs as $paragraph)
                <p class="gbp-overview__paragraph body-text fade-up">{{ $paragraph }}</p>
                @endforeach
            </div>
            <div class="gbp-overview__stats">
                @foreach($overview->stats as $stat)
                <div class="gbp-stat fade-up">
                    @if($stat->number)
                    <span class="gbp-stat__number">{{ $stat->number }}</span>
                    @endif
                    <span class="gbp-stat__label">{{ $stat->label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. WHY CHOOSE THIS PATHWAY?
═══════════════════════════════════════════ --}}
<section class="gbp-why section-wrapper section--light section--warm" aria-label="Why Choose This Pathway" data-testid="gbp-why">
    <div class="container">
        <div class="gbp-why__grid">
            <div class="gbp-why__sticky">
                <span class="section-label"><span>{{ $whyPathway->tag }}</span></span>
                <h2 class="gbp-why__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $whyPathway->heading }}</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $whyPathway->heading_italic }}</em></span></span>
                </h2>
                <blockquote class="gbp-why__quote fade-up">{{ $whyPathway->quote }}</blockquote>
                <p class="gbp-why__paragraph body-text fade-up">{{ $whyPathway->paragraph }}</p>
            </div>
            <div class="gbp-why__cards">
                @foreach($whyPathway->items as $index => $item)
                <article class="gbp-why-card" data-testid="gbp-why-card-{{ $index + 1 }}">
                    <span class="gbp-why-card__number">0{{ $index + 1 }}</span>
                    <div class="gbp-why-card__icon" aria-hidden="true">
                        <span data-lucide="{{ $item->icon }}"></span>
                    </div>
                    <div class="gbp-why-card__body">
                        <h3 class="gbp-why-card__title card-title">{{ $item->title }}</h3>
                        <p class="gbp-why-card__description">{{ $item->description }}</p>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. EXPLORE EUROPE WITH YOUR CHOICES
═══════════════════════════════════════════ --}}
<section class="gbp-explore section-wrapper" aria-label="Explore Europe with Your Choices" data-testid="gbp-explore">
    <div class="container">
        <div class="gbp-explore__header">
            <span class="section-label"><span>YOUR OPTIONS</span></span>
            <h2 class="gbp-explore__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Explore Europe with</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Your Choices</em></span></span>
            </h2>
            <p class="gbp-explore__sub body-text fade-up">
                Hungary | Romania | Moldova — With Maverick's Bachelor's Global Pathway, students can choose from multiple European progression routes based on their academic goals, budget, preferred destination, and long-term career plans.
            </p>
        </div>

        <div class="gbp-explore__grid">
            @foreach($exploreEurope as $country)
            <article class="gbp-explore-card" data-testid="gbp-explore-{{ strtolower($country->country) }}">
                <div class="gbp-explore-card__flag" aria-hidden="true">{{ $country->flag }}</div>
                <h3 class="gbp-explore-card__country card-title">{{ $country->country }}</h3>
                <span class="gbp-explore-card__type">{{ $country->type }}</span>
                <p class="gbp-explore-card__university">{{ $country->university }}</p>
                <ul class="gbp-explore-card__highlights">
                    @foreach($country->highlights as $highlight)
                    <li>
                        <span data-lucide="check" aria-hidden="true"></span>
                        {{ $highlight }}
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     5. PROGRAMME PATHWAY STRUCTURE
═══════════════════════════════════════════ --}}
<section class="gbp-stages section-wrapper section--light" aria-label="Programme Pathway Structure" data-testid="gbp-stages">
    <div class="container">
        <div class="gbp-stages__header">
            <span class="section-label"><span>PROGRAMME PATHWAY</span></span>
            <h2 class="gbp-stages__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">A Structured</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Four-Stage Journey</em></span></span>
            </h2>
            <p class="gbp-stages__sub body-text fade-up">
                From foundational diplomas in the UAE to an internationally recognised European bachelor's degree.
            </p>
        </div>

        <div class="gbp-stages__timeline">
            <div class="gbp-stages__line" aria-hidden="true"></div>
            @foreach($stages as $stage)
            <div class="gbp-stage" data-testid="gbp-stage-{{ $stage->year }}">
                <div class="gbp-stage__dot">{{ $stage->year }}</div>
                <div class="gbp-stage__card">
                    <span class="gbp-stage__duration">{{ $stage->duration }}</span>
                    <h3 class="gbp-stage__title card-title">{{ $stage->title }}</h3>
                    <p class="gbp-stage__description">{{ $stage->description }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <p class="gbp-stages__quote body-text fade-up">{{ $overview->quotes }}</p>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     6. STUDY DESTINATIONS
═══════════════════════════════════════════ --}}
<section class="gbp-destinations section-wrapper" aria-label="Study Destinations" data-testid="gbp-destinations">
    <div class="container">
        <div class="gbp-destinations__header">
            <span class="section-label"><span>STUDY DESTINATIONS</span></span>
            <h2 class="gbp-destinations__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Choose Your European</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Study Destination</em></span></span>
            </h2>
        </div>

        <div class="gbp-destinations__list">
            @foreach($destinations as $dest)
            <article class="gbp-dest gbp-dest--{{ $dest->position }} gbp-dest--{{ $dest->slug }}" data-testid="gbp-dest-{{ $dest->slug }}">
                <div class="gbp-dest__media">
                    <img
                        class="gbp-dest__image"
                        src="{{ $dest->image }}"
                        alt="Study in {{ $dest->name }}"
                        loading="lazy"
                        width="760"
                        height="950"
                    >
                    <div class="gbp-dest__overlay" aria-hidden="true"></div>
                </div>
                <div class="gbp-dest__content fade-up">
                    <span class="gbp-dest__label">{{ $dest->label }}</span>
                    <h3 class="gbp-dest__title card-title">Study in <em>{{ $dest->name }}</em></h3>
                    <p class="gbp-dest__partner">{{ $dest->university }}</p>
                    <p class="gbp-dest__description body-text">{{ $dest->description }}</p>
                    <ul class="gbp-dest__points">
                        @foreach($dest->points as $point)
                        <li>
                            <span data-lucide="check" aria-hidden="true"></span>
                            {{ $point }}
                        </li>
                        @endforeach
                    </ul>
                    <p class="gbp-dest__best"><strong>Best For:</strong> {{ $dest->best_for }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     7. COST & TIME ADVANTAGE
═══════════════════════════════════════════ --}}
<section class="gbp-cost section-wrapper" aria-label="Cost and Time Advantage" data-testid="gbp-cost">
    <div class="container">
        <div class="gbp-cost__grid">
            <div class="gbp-cost__main">
                <span class="section-label"><span>{{ $costTime->tag }}</span></span>
                <h2 class="gbp-cost__heading section-title">
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner">{{ $costTime->heading }}</span></span>
                    <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>{{ $costTime->heading_italic }}</em></span></span>
                </h2>
                <p class="gbp-cost__description body-text fade-up">{{ $costTime->description }}</p>
                <p class="gbp-cost__closing body-text fade-up">{{ $costTime->closing }}</p>
            </div>
            <div class="gbp-cost__rows">
                @foreach($costTime->comparisons as $row)
                <div class="gbp-cost-row gbp-cost-row--{{ $row->variant }} fade-up">
                    <span class="gbp-cost-row__label">{{ $row->label }}</span>
                    <span class="gbp-cost-row__value">{{ $row->value }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     8. PROGRAMS OFFERED
═══════════════════════════════════════════ --}}
<section class="gbp-areas section-wrapper section--light" aria-label="Programs Offered" data-testid="gbp-areas">
    <div class="container">
        <div class="gbp-areas__header">
            <span class="section-label"><span>PATHWAY AREAS</span></span>
            <h2 class="gbp-areas__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Choose a Bachelor's Pathway That Matches</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Your Career Goals</em></span></span>
            </h2>
            <p class="gbp-areas__sub body-text fade-up">
                Career-focused pathway areas across business, technology, hospitality, and international management fields.
            </p>
        </div>

        <div class="gbp-areas__grid">
            @foreach($pathwayAreas as $area)
            <article class="gbp-area-card" data-testid="gbp-area-{{ $loop->iteration }}">
                <div class="gbp-area-card__icon" aria-hidden="true">
                    <span data-lucide="{{ $area->icon }}"></span>
                </div>
                <h3 class="gbp-area-card__title card-title">{{ $area->title }}</h3>
                <p class="gbp-area-card__description">{{ $area->description }}</p>
                <ul class="gbp-area-card__list">
                    @foreach($area->items as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     9. PARTNER UNIVERSITY PROGRESSION
═══════════════════════════════════════════ --}}
<section class="gbp-partners section-wrapper" aria-label="Partner University Progression" data-testid="gbp-partners">
    <div class="container">
        <div class="gbp-partners__header">
            <span class="section-label"><span>PROGRESSION OPTIONS</span></span>
            <h2 class="gbp-partners__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Partner University</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Progression Options</em></span></span>
            </h2>
            <p class="gbp-partners__sub body-text fade-up">
                Three European progression routes — pick the one that fits your budget, timeline, and career direction.
            </p>
        </div>

        <div class="gbp-partners__grid">
            @foreach($partnerOptions as $partner)
            <article class="gbp-partner-card" data-testid="gbp-partner-{{ strtolower($partner->code) }}">
                <span class="gbp-partner-card__code" aria-hidden="true">{{ $partner->code }}</span>
                <h3 class="gbp-partner-card__title card-title">{{ $partner->name }}</h3>
                <p class="gbp-partner-card__description">{{ $partner->description }}</p>
                <div class="gbp-partner-card__suited">
                    <span class="gbp-partner-card__suited-label">Best Suited For</span>
                    <div class="gbp-partner-card__tags">
                        @foreach($partner->best_for as $tag)
                        <span class="gbp-partner-card__tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     10. ADMISSION REQUIREMENTS
═══════════════════════════════════════════ --}}
<section class="gbp-admission section-wrapper section--light" aria-label="Admission Requirements" data-testid="gbp-admission">
    <div class="container">
        <div class="gbp-admission__header">
            <span class="section-label"><span>ADMISSION</span></span>
            <h2 class="gbp-admission__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Admission</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>Requirements</em></span></span>
            </h2>
        </div>

        <div class="gbp-admission__grid">
            <div class="gbp-admission__card fade-up">
                <h3 class="gbp-admission__card-title card-title">Who Can Apply?</h3>
                <ul class="gbp-admission__list">
                    @foreach($eligibility as $item)
                    <li>
                        <span data-lucide="check" aria-hidden="true"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="gbp-admission__card fade-up">
                <h3 class="gbp-admission__card-title card-title">General Entry Requirements</h3>
                <ul class="gbp-admission__list">
                    @foreach($entryRequirements as $item)
                    <li>
                        <span data-lucide="check" aria-hidden="true"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <p class="gbp-admission__note fade-up">No IELTS / TOEFL required, subject to admission requirements.</p>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     11. DOCUMENTS REQUIRED
═══════════════════════════════════════════ --}}
<section class="gbp-docs section-wrapper" aria-label="Documents Required" data-testid="gbp-docs">
    <div class="container">
        <div class="gbp-docs__header">
            <span class="section-label"><span>CHECKLIST</span></span>
            <h2 class="gbp-docs__heading section-title">
                <span class="text-reveal-wrapper"><span class="text-reveal-inner">Documents Required</span></span>
                <span class="text-reveal-wrapper"><span class="text-reveal-inner"><em>for Admission</em></span></span>
            </h2>
        </div>

        <div class="gbp-docs__grid">
            @foreach($documentGroups as $group)
            <article class="gbp-doc-card" data-testid="gbp-doc-{{ $loop->iteration }}">
                <div class="gbp-doc-card__icon" aria-hidden="true">
                    <span data-lucide="{{ $group->icon }}"></span>
                </div>
                <h3 class="gbp-doc-card__title card-title">{{ $group->title }}</h3>
                <ul class="gbp-doc-card__list">
                    @foreach($group->items as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     12. FINAL CTA
═══════════════════════════════════════════ --}}
<section class="gbp-final section-wrapper" id="enquire" aria-label="Start Your Journey" data-testid="gbp-final">
    <div class="container">
        <div class="gbp-final__inner fade-up">
            <span class="gbp-final__eyebrow">Your Global Career Starts Here</span>
            <h2 class="gbp-final__heading section-title">
                Start Your Global <em>Bachelor's Journey</em>
            </h2>
            <p class="gbp-final__sub">Your international bachelor's degree pathway starts here.</p>
            <p class="gbp-final__description body-text">
                Begin with Maverick Business Academy London and progress towards selected partner universities in Hungary, Romania, or Moldova — with structured academic support, visa guidance, and career-focused counselling.
            </p>
            <div class="gbp-final__ctas">
                <a href="#advisor" class="btn gbp-final__btn gbp-final__btn--solid" id="advisor" data-testid="final-cta-advisor">Speak to an Admission Advisor</a>
                <a href="#brochure" class="btn gbp-final__btn gbp-final__btn--outline" id="brochure" data-testid="final-cta-brochure">Download Brochure</a>
                <a href="#apply" class="btn gbp-final__btn gbp-final__btn--outline" id="apply" data-testid="final-cta-apply">Apply for the Next Intake</a>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof AnimationUtils === 'undefined' || typeof gsap === 'undefined') return;

    if (AnimationUtils.prefersReducedMotion) {
        gsap.set('.fade-up, .text-reveal-inner, .gbp-snapshot-card, .gbp-intro-card, .gbp-why-card, .gbp-explore-card, .gbp-area-card, .gbp-partner-card, .gbp-doc-card, .gbp-stage, .gbp-stages__line', {
            clearProps: 'all',
            opacity: 1,
            y: 0,
            scaleY: 1,
        });
        return;
    }

    AnimationUtils.cards('.gbp-snapshot-card', { stagger: 0.12 });
    AnimationUtils.fadeUp('.gbp-snapshot .fade-up', { stagger: 0.1 });

    AnimationUtils.textReveal('.gbp-intro .text-reveal-inner', { stagger: 0.15 });
    AnimationUtils.fadeUp('.gbp-intro .fade-up', { stagger: 0.12, y: 25 });
    AnimationUtils.cards('.gbp-intro-card', { stagger: 0.1, y: 30 });

    const sections = [
        '.gbp-overview', '.gbp-why', '.gbp-explore', '.gbp-stages',
        '.gbp-destinations', '.gbp-cost', '.gbp-areas', '.gbp-partners',
        '.gbp-admission', '.gbp-docs', '.gbp-final',
    ];

    sections.forEach((s) => AnimationUtils.sectionLabel(s));

    [
        '.gbp-overview', '.gbp-why', '.gbp-explore', '.gbp-stages',
        '.gbp-destinations', '.gbp-cost', '.gbp-areas', '.gbp-partners',
        '.gbp-admission', '.gbp-docs',
    ].forEach((s) => AnimationUtils.textReveal(`${s} .text-reveal-inner`));

    sections.forEach((s) => {
        AnimationUtils.fadeUp(`${s} .fade-up`, { stagger: 0.1 });
    });

    AnimationUtils.cards('.gbp-why-card', { stagger: 0.12 });
    AnimationUtils.cards('.gbp-explore-card', { stagger: 0.15 });
    AnimationUtils.cards('.gbp-area-card', { stagger: 0.1 });
    AnimationUtils.cards('.gbp-partner-card', { stagger: 0.12 });
    AnimationUtils.cards('.gbp-doc-card', { stagger: 0.12 });
    AnimationUtils.cards('.gbp-stage', { stagger: 0.2 });

    const timelineLine = document.querySelector('.gbp-stages__line');
    if (timelineLine) {
        gsap.fromTo(timelineLine,
            { scaleY: 0 },
            {
                scaleY: 1,
                transformOrigin: 'top center',
                duration: 1.2,
                ease: 'power2.inOut',
                scrollTrigger: {
                    trigger: '.gbp-stages',
                    start: 'top 70%',
                    toggleActions: 'play none none none',
                },
            }
        );
    }

    ['.gbp-dest--hungary .gbp-dest__image', '.gbp-dest--romania .gbp-dest__image', '.gbp-dest--moldova .gbp-dest__image']
        .forEach((sel) => AnimationUtils.parallax(sel, { y: -30 }));

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endpush

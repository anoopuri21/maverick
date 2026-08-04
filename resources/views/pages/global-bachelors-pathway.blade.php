@extends('layouts.app')

@section('title', 'Global Bachelor\'s Pathway Programme | Study Bachelor\'s in Europe')
@section('meta_description', 'Start your Bachelor\'s journey with Maverick Business Academy London and progress to partner universities in Hungary, Romania, or Moldova. Explore affordable European Bachelor\'s pathways with credit transfer, visa support, and career guidance.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-bachelors-pathway.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cinematic-hero.css') }}">
@endpush

@section('content')
<div class="page-gbp gbp">

@php
    // ═══════════════════════════════════════════
    // STATIC DATA (Future: Move to admin/database)
    // ═══════════════════════════════════════════

    $hero = (object)[
        'tag' => 'GLOBAL BACHELOR\'S PATHWAY',
        'heading' => 'Your Structured Route to a Globally Recognised',
        'heading_italic' => 'European Bachelor\'s Degree',
        'description' => 'Begin your Bachelor\'s Degree Pathway in UAE with Maverick Business Academy London and progress towards an internationally recognised European Bachelor\'s degree through our partner university pathways in Hungary, Romania, and Moldova.',
        'sub_description' => 'Designed for students and parents seeking a smarter, affordable, and globally focused study route, the Maverick Bachelor\'s Global Pathway helps learners begin their academic journey with structured support and progress confidently towards international university completion, leading to an Affordable Bachelor\'s Degree in Europe.',
        'background_image' => asset('https://images.pexels.com/photos/1462630/pexels-photo-1462630.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600'),
        'highlights' => collect([
            (object)['label' => 'Study Route', 'value' => 'UAE / Hybrid / Online + European University Progression'],
            (object)['label' => 'Destinations', 'value' => 'Hungary · Romania · Moldova'],
            (object)['label' => 'Focus', 'value' => 'International Pathways · European Degree · Credit Transfer · Student Visa Support · Career Guidance '],
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
            (object)['number' => '', 'label' => 'International Bachelor\'s Degree '],
        ]),
        'quotes' => 'This route is ideal for students who want an international degree pathway with flexible learning, credit transfer guidance, visa support, and career-focused academic support.'
    ];

    $whyPathway = (object)[
        'tag' => 'OUR VALUE',
        'heading' => 'Why Choose This',
        'heading_italic' => 'Pathway Programme?',
        'quote' => 'A smarter alternative to the traditional overseas route — start with Maverick, progress internationally at the right stage.',
        'paragraph' => 'The Maverick Bachelor\'s Global Pathway is designed to give students a smarter alternative to the traditional 4-year overseas study route. 
        Instead of moving abroad from year one and paying higher international tuition and living costs, students can begin their journey with Maverick through a Bachelor\'s Degree with Credit Transfer route and progress internationally at a later stage. This creates a more structured, affordable, and globally focused pathway towards completing a European Bachelor\'s degree.',
        'items' => collect([
            (object)['icon' => 'clock', 'title' => 'Save Time', 'description' => 'The pathway can help students save up to one year compared with the traditional Bachelor\'s route.'],
            (object)['icon' => 'award', 'title' => 'Earn 240 UK Credits', 'description' => 'Students complete structured UK credit-based qualifications before progressing to the university stage.'],
            (object)['icon' => 'shuffle', 'title' => 'Flexible Learning Route', 'description' => 'Students can begin their studies through flexible learning before moving into the final university progression stage.'],
            (object)['icon' => 'graduation-cap', 'title' => 'Direct University Progression', 'description' => 'The programme is designed to support progression to selected partner universities.'],
            (object)['icon' => 'trending-down', 'title' => 'Cost-Effective Study Route', 'description' => 'Students and parents can reduce overall study cost compared with starting the full overseas route from year one.'],
        ]),
    ];

    $stages = collect([
        (object)['year' => '01', 'title' => 'Level 4 Diploma', 'duration' => 'Approx. 6 Months', 'description' => 'Students begin with a Level 4 Diploma designed to build the academic foundation required for bachelor\'s progression.'],
        (object)['year' => '02', 'title' => 'Level 5 Diploma', 'duration' => 'Approx. 6 Months', 'description' => 'Students then complete a Level 5 Diploma, strengthening their academic knowledge and preparing them for international university progression.'],
        (object)['year' => '03', 'title' => 'International University Progression', 'duration' => 'Partner University Stage', 'description' => 'After completing the required academic stages, students progress to an international partner university in Europe.'],
        (object)['year' => '04', 'title' => 'International Bachelor\'s Degree', 'duration' => 'Final Outcome', 'description' => 'Upon successful completion of the final university stage, students receive an internationally recognised bachelor\'s degree from the partner university.'],
    ]);

    $destinations = collect([
        (object)[
            'position' => 'right',
            'flag' => 'HU',
            'name' => 'Hungary',
            'label' => 'PREMIUM EUROPEAN PATHWAY',
            'university' => 'International Business School, Budapest',
            'description' => 'Hungary is a strong choice for students seeking a premium European study experience, international exposure, and a vibrant student-friendly environment. Through Maverick\'s pathway, students can study Bachelor\'s in Hungary while benefiting from structured academic guidance, career-focused support, and opportunities for global growth.
            Through Maverick\'s Hungary pathway, students can progress to International Business School, Budapest, one of the leading international business schools in Europe.',
            'image' => asset('https://images.pexels.com/photos/3722721/pexels-photo-3722721.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760'),
            'points' => [
                'International study experience in Budapest',
                'Dual degree opportunities with University of Buckingham (UK) and Dublin Business School (Ireland)',
                '100% placement assistance and career mentoring',
                'Internship support connected to KPMG, Microsoft, Amazon and more',
                '9–12 month post-study work opportunity',
                'Access to 27+ Schengen countries',
                'Erasmus+ student exchange opportunity',
                'No IELTS / TOEFL required, subject to admission requirements',
            ],
            'best_for' => 'Students looking for a premium European business education pathway with stronger international exposure and career development support.',
        ],
        (object)[
            'position' => 'left',
            'flag' => 'RO',
            'name' => 'Romania',
            'label' => 'AFFORDABLE EUROPEAN PATHWAY',
            'university' => 'Aurel Vlaicu University, Romania',
            'description' => 'Attractive for students who want a European bachelor\'s degree pathway with affordable tuition, lower living costs, and direct university progression. Benefit from structured academic support, reduced study duration, and student visa guidance.',
            'image' => asset('https://images.pexels.com/photos/207684/pexels-photo-207684.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760'),
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
            'position' => 'right',
            'flag' => 'MD',
            'name' => 'Moldova',
            'label' => 'AFFORDABLE EUROPEAN PATHWAY',
            'university' => 'USPEE, Moldova',
            'description' => 'Another affordable European pathway option for students looking for a cost-effective route to complete their bachelor\'s degree. Progress to USPEE, Moldova through Maverick\'s structured pathway.',
            'image' => asset('https://images.pexels.com/photos/1519088/pexels-photo-1519088.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760'),
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
            (object)['label' => 'Maverick Pathway', 'value' => 'Approximately', 'variant' => 'accent', 'note' => '3 years'],
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
            'description' => 'Progress to International Business School, Budapest through Maverick\'s premium European route. International exposure, career mentoring, internship support, Erasmus+ exchange, and access to the wider Schengen region.',
            'best_for' => ['Business Management', 'International Business', 'Marketing', 'Finance', 'Data Analytics', 'AI & Business', 'Entrepreneurship'],
        ],
        (object)[
            'code' => 'RO',
            'name' => 'Romania — Affordable European Pathway',
            'description' => 'Progress to Aurel Vlaicu University, Romania through Maverick\'s affordable European pathway. Designed for students looking for affordable tuition, reduced study duration, lower living costs, and direct university progression.',
            'best_for' => ['Business Administration', 'Management', 'Information Technology', 'Data Science', 'Hospitality & Tourism', 'International Business'],
        ],
        (object)[
            'code' => 'MD',
            'name' => 'Moldova — Affordable European Pathway',
            'description' => 'Progress to USPEE, Moldova through Maverick\'s affordable European pathway. Suitable for students and parents looking for a practical, lower-cost route towards an international bachelor\'s degree.',
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
            'items' => ['Passport copy', 'Passport-size photograph', 'Emirates ID copy, if applicable', 'Updated CV, if applicable'],
        ],
        (object)[
            'icon' => 'book-open',
            'title' => 'Academic Documents',
            'items' => ['High school / Grade 12 certificate', 'Academic transcripts / mark sheets', 'Previous diploma or college documents, if applicable', 'English language documents, if required'],
        ],
        (object)[
            'icon' => 'file-check',
            'title' => 'Additional Documents for Visa Stage',
            'items' => ['Bank statement or financial proof, if required', 'Accommodation details, if required', 'Travel insurance, if required', 'Medical documents, if required', 'Any additional documents requested by the embassy or university'],
        ],
    ]);

    // NEW: Explore Europe Section Data
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
@endphp


{{-- ═══════════════════════════════════════════
     1. HERO SECTION (Cinematic Design)
═══════════════════════════════════════════ --}}
<section class="cinematic-hero" aria-label="Global Bachelor's Pathway Hero">
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
    <div class="cinematic-hero__content">
        <span class="cinematic-hero__eyebrow">
            <span class="cinematic-hero__eyebrow-line"></span>
            {{ $hero->tag }}
        </span>
        <h1 class="cinematic-hero__title">
            {{ $hero->heading }}<br>
            <em>{{ $hero->heading_italic }}</em>
        </h1>
        <p class="cinematic-hero__description">{{ $hero->description }}</p>

        <div class="gbp-hero__highlights">
            @foreach($hero->highlights as $h)
            <div class="gbp-highlight">
                <span class="gbp-highlight__label">{{ $h->label }}</span>
                <span class="gbp-highlight__value">{{ $h->value }}</span>
            </div>
            @endforeach
        </div>

        <div class="gbp-hero__ctas">
            <a href="#enquire" class="btn btn--primary" data-testid="hero-cta-enquire">Enquire Now</a>
            <a href="#advisor" class="btn btn--secondary" data-testid="hero-cta-advisor">Speak to an Advisor</a>
            <a href="#brochure" class="btn btn--outline" data-testid="hero-cta-brochure">Download Brochure</a>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     2. WHAT IS THE PATHWAY PROGRAMME
═══════════════════════════════════════════ --}}
<section class="gbp-overview section-wrapper" data-testid="gbp-overview">
    <div class="container">
        <div class="gbp-overview__grid">

            <div class="gbp-overview__main">
                <span class="section-label gbp-overview__label">{{ $overview->tag }}</span>
                <h2 class="gbp-overview__heading">
                    {{ $overview->heading }}
                    <em>{{ $overview->heading_italic }}</em>
                </h2>
                @foreach($overview->paragraphs as $paragraph)
                <p class="gbp-overview__paragraph">{{ $paragraph }}</p>
                @endforeach
        </div>
            <div class="gbp-overview__stats">
                @foreach($overview->stats as $stat)
                <div class="gbp-stat">
                    <div class="gbp-stat__number">{{ $stat->number }}</div>
                    <div class="gbp-stat__label">{{ $stat->label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     3. WHY THIS PATHWAY — Sticky left + numbered cards
═══════════════════════════════════════════ --}}
<section class="gbp-why section-wrapper section--light" data-testid="gbp-why">
    <div class="container">
        <div class="gbp-why__grid">

            <div class="gbp-why__sticky">
                <div class="gbp-why__sticky-inner">
                    <span class="section-label">{{ $whyPathway->tag }}</span>
                    <h2 class="gbp-why__heading">
                        {{ $whyPathway->heading }}
                        <em>{{ $whyPathway->heading_italic }}</em>
                    </h2>
                    <blockquote class="gbp-why__quote">{{ $whyPathway->quote }}</blockquote>
                    
                <p class="gbp-overview__paragraph" style="margin-top:30px;">{{ $whyPathway->paragraph }}</p>
                </div>
            </div>

            <div class="gbp-why__cards">
                @foreach($whyPathway->items as $index => $item)
                <article class="gbp-why-card">
                    <div class="gbp-why-card__icon">
                        <span data-lucide="{{ $item->icon }}"></span>
                    </div>
                    <div class="gbp-why-card__content">
                        <h3 class="gbp-why-card__title">{{ $item->title }}</h3>
                        <p class="gbp-why-card__description">{{ $item->description }}</p>
                    </div>
                    <span class="gbp-why-card__number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </article>
                @endforeach
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     4. EXPLORE EUROPE WITH YOUR CHOICES
═══════════════════════════════════════════ --}}
<section class="gbp-explore section-wrapper" data-testid="gbp-explore">
    <div class="container">
        <div class="section-heading-block">
            <span class="section-label">YOUR OPTIONS</span>
            <h2 class="section-heading">
                Explore Europe with <em>Your Choices</em>
            </h2>
            <p class="section-subheading">
                Hungary | Romania | Moldova — With Maverick's Bachelor's Global Pathway, students can choose from multiple European progression routes based on their academic goals, budget, preferred destination, and long-term career plans.
            </p>
        </div>

        <div class="gbp-explore__grid">
            @foreach($exploreEurope as $country)
            <div class="gbp-explore-card fade-up">
                <div class="gbp-explore-card__flag">{{ $country->flag }}</div>
                <h3 class="gbp-explore-card__country">{{ $country->country }}</h3>
                <span class="gbp-explore-card__type">{{ $country->type }}</span>
                <p class="gbp-explore-card__university">{{ $country->university }}</p>
                <ul class="gbp-explore-card__highlights">
                    @foreach($country->highlights as $highlight)
                    <li>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        {{ $highlight }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     5. PROGRAMME PATHWAY STRUCTURE
═══════════════════════════════════════════ --}}
<section class="gbp-stages section-wrapper" data-testid="gbp-stages">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">PROGRAMME PATHWAY</span>
            <h2 class="section-heading">
                A Structured <em>Four-Stage Journey</em>
            </h2>
            <p class="section-subheading">
                From foundational diplomas in the UAE to an internationally recognised European bachelor's degree.
            </p>
        </div>

        <div class="gbp-stages__timeline">
            <div class="gbp-stages__line"></div>
            @foreach($stages as $index => $stage)
            <div class="gbp-stage">
                <div class="gbp-stage__dot">{{ $stage->year }}</div>
                <div class="gbp-stage__card">
                    <span class="gbp-stage__duration">{{ $stage->duration }}</span>
                    <h3 class="gbp-stage__title">{{ $stage->title }}</h3>
                    <p class="gbp-stage__description">{{ $stage->description }}</p>
                </div>
            </div>
            @endforeach
        </div>

                <p class="gbp-overview__paragraph" style="margin-top:30px;">{{ $overview->quotes }}</p>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     6. STUDY DESTINATIONS
═══════════════════════════════════════════ --}}
<section class="gbp-destinations section-wrapper" data-testid="gbp-destinations">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">STUDY DESTINATIONS</span>
            <h2 class="section-heading">
                Choose Your European <em>Study Destination</em>
            </h2>
            <p class="section-subheading">
                With Maverick\'s Bachelor\'s Global Pathway, students can choose from multiple European progression routes based on their academic goals, budget, preferred destination, and long-term career plans
            </p>
        </div>

        <div class="gbp-destinations__list">
            @foreach($destinations as $dest)
            <article class="gbp-dest gbp-dest--{{ $dest->position }}">
                <div class="gbp-dest__image-wrapper">
                    <img src="{{ $dest->image }}" alt="Study in {{ $dest->name }}" class="gbp-dest__image" loading="lazy">
                    <span class="gbp-dest__flag">{{ $dest->flag }}</span>
                </div>
                <div class="gbp-dest__content">
                    <span class="gbp-dest__label">{{ $dest->label }}</span>
                    <h3 class="gbp-dest__title">Study in <em>{{ $dest->name }}</em></h3>
                    <p class="gbp-dest__partner">Partner University · {{ $dest->university }}</p>
                    <p class="gbp-dest__description">{{ $dest->description }}</p>

                    <h4 class="gbp-dest__subhead">Why Study in {{ $dest->name }}?</h4>
                    <ul class="gbp-dest__points">
                        @foreach($dest->points as $point)
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>{{ $point }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="gbp-dest__best-for">
                        <span class="gbp-dest__best-label">Best For</span>
                        {{ $dest->best_for }}
                    </div>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     7. COST & TIME ADVANTAGE
═══════════════════════════════════════════ --}}
<section class="gbp-cost" data-testid="gbp-cost">
    <div class="container">
        <div class="gbp-cost__grid">

            <div class="gbp-cost__content">
                <span class="section-label gbp-cost__label">{{ $costTime->tag }}</span>
                <h2 class="gbp-cost__heading">
                    {{ $costTime->heading }}
                    <em>{{ $costTime->heading_italic }}</em>
                </h2>
                <p class="gbp-cost__description">{{ $costTime->description }}</p>
                <p class="gbp-cost__closing">{{ $costTime->closing }}</p>
            </div>

            <div class="gbp-cost__table">
                @foreach($costTime->comparisons as $row)
                <div class="gbp-cost-row gbp-cost-row--{{ $row->variant }}">
                    <div class="gbp-cost-row__label">{{ $row->label }}</div>
                    <div class="gbp-cost-row__value">
                        {{ $row->value }}
                        @if(isset($row->note))<span class="gbp-cost-row__note">{{ $row->note }}</span>@endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     8. PROGRAMS OFFERED
═══════════════════════════════════════════ --}}
<section class="gbp-areas section-wrapper section--light" data-testid="gbp-areas">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">PATHWAY AREAS</span>
            <h2 class="section-heading">
                Choose a Bachelor's Pathway That Matches <em>Your Career Goals</em>
            </h2>
            <p class="section-subheading">
                Career-focused pathway areas across business, technology, hospitality, and international management fields.
            </p>
        </div>

        <div class="gbp-areas__grid">
            @foreach($pathwayAreas as $area)
            <article class="gbp-area-card">
                <div class="gbp-area-card__icon">
                    <span data-lucide="{{ $area->icon }}"></span>
                </div>
                <h3 class="gbp-area-card__title">{{ $area->title }}</h3>
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
<section class="gbp-partners section-wrapper" data-testid="gbp-partners">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">PROGRESSION OPTIONS</span>
            <h2 class="section-heading">
                Partner University <em>Progression Options</em>
            </h2>
            <p class="section-subheading">
                Three European progression routes — pick the one that fits your budget, timeline, and career direction.
            </p>
        </div>

        <div class="gbp-partners__grid">
            @foreach($partnerOptions as $option)
            <article class="gbp-partner-card">
                <div class="gbp-partner-card__flag">
                    <span>{{ $option->code }}</span>
                </div>
                <h3 class="gbp-partner-card__title">{{ $option->name }}</h3>
                <p class="gbp-partner-card__description">{{ $option->description }}</p>
                <div class="gbp-partner-card__best">
                    <span class="gbp-partner-card__best-label">Best Suited For</span>
                    <div class="gbp-partner-card__tags">
                        @foreach($option->best_for as $tag)
                        <span class="gbp-tag">{{ $tag }}</span>
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
<section class="gbp-admission section-wrapper section--light" data-testid="gbp-admission">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">ADMISSIONS</span>
            <h2 class="section-heading">
                Admission <em>Requirements</em>
            </h2>
            <p class="section-subheading">
                Designed for students who want to start their international bachelor's journey through a structured and cost-effective academic route.
            </p>
        </div>

        <div class="gbp-admission__grid">

            <div class="gbp-admission__col">
                <h3 class="gbp-admission__title">Who Can Apply?</h3>
                <ul class="gbp-admission__list">
                    @foreach($eligibility as $item)
                    <li>
                        <span class="gbp-admission__bullet">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </span>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="gbp-admission__col">
                <h3 class="gbp-admission__title">General Entry Requirements</h3>
                <ul class="gbp-admission__list">
                    @foreach($entryRequirements as $item)
                    <li>
                        <span class="gbp-admission__bullet">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </span>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
                <p class="gbp-admission__note">
                    <strong>Note:</strong> No IELTS / TOEFL required, subject to admission requirements.
                </p>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     11. DOCUMENTS REQUIRED
═══════════════════════════════════════════ --}}
<section class="gbp-docs section-wrapper" data-testid="gbp-docs">
    <div class="container">

        <div class="section-heading-block">
            <span class="section-label">CHECKLIST</span>
            <h2 class="section-heading">
                Documents Required <em>for Admission</em>
            </h2>
        </div>

        <div class="gbp-docs__grid">
            @foreach($documentGroups as $group)
            <article class="gbp-doc-card">
                <div class="gbp-doc-card__icon">
                    <span data-lucide="{{ $group->icon }}"></span>
                </div>
                <h3 class="gbp-doc-card__title">{{ $group->title }}</h3>
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
<section class="gbp-final" id="enquire" data-testid="gbp-final">
    <div class="container">
        <div class="gbp-final__inner">
            <span class="gbp-final__eyebrow">Your Global Career Starts Here</span>
            <h2 class="gbp-final__heading">
                Start Your Global <em>Bachelor's Journey</em>
            </h2>
            <p class="gbp-final__sub">Your international bachelor's degree pathway starts here.</p>
            <p class="gbp-final__description">
                Begin with Maverick Business Academy London and progress towards selected partner universities in Hungary, Romania, or Moldova — with structured academic support, visa guidance, and career-focused counselling.
            </p>
            <div class="gbp-final__ctas">
                <a href="#advisor" class="gbp-btn gbp-btn--primary" data-testid="final-cta-advisor">Speak to an Admission Advisor
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="#brochure" class="gbp-btn gbp-btn--outline-light" data-testid="final-cta-brochure">Download Brochure</a>
                <a href="#apply" class="gbp-btn gbp-btn--outline-light" data-testid="final-cta-apply">Apply for the Next Intake</a>
            </div>
        </div>
    </div>
</section>

@include('sections.final-cta')

</div>
@endsection

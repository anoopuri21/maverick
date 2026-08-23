@extends('layouts.app')

@section('title', ($program->seo?->meta_title ?? (($program->title ?? 'Programme') . ' | Maverick Business Academy')))
@section('meta_description', $program->seo?->meta_description ?? $program->short_description ?? 'Explore this Maverick Business Academy programme.')

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('css/pages/program-detail.css') }}">
@endpush

@push('head')
    @include('partials.seo-meta', ['seo' => $program->seo])
@endpush

@if(!empty($program->seo) && !empty($program->seo->custom_body_scripts))
@push('scripts')
    {!! $program->seo->custom_body_scripts !!}
@endpush
@endif

@section('content')
@php
    $chrome = $chrome ?? safe_settings(\App\Settings\ProgramsDetailChromeSettings::class);
    $cat = $program->programCategory;
    $highlights          = collect($program->highlights_list ?? [])->filter(fn ($h) => is_array($h))->values();
    $recognition         = collect($program->recognition_list ?? [])->filter(fn ($r) => is_array($r))->values();
    $snapshot            = collect($program->snapshot_list ?? [])->filter(fn ($s) => is_array($s))->values();
    $benefits            = collect($program->benefits_list ?? [])->filter(fn ($b) => is_array($b))->values();
    $learning            = collect($program->learning_list ?? []);
    $careers             = collect($program->careers_list ?? []);
    $structure           = collect($program->structure_list ?? [])->filter(fn ($s) => is_array($s))->values();
    $support             = collect($program->support_list ?? []);
    $university          = $program->university_object;
    $accreditationGroups = collect($program->accreditation_groups_list ?? []);
    $testimonials        = collect($program->testimonials_list ?? [])->filter(fn ($t) => is_array($t))->values();
    $fees                = collect($program->fees_list ?? []);
    $faqs                = collect($program->faqs ?? []);
    $reviews             = collect($program->reviews_list ?? [])->filter(fn ($r) => is_array($r))->values();
    // Scholarship ribbon only if content mentions scholarships
    $hasScholarship = $highlights->contains(fn($h) => stripos(($h['label'] ?? '').' '.($h['value'] ?? ''), 'scholar') !== false)
                      || $fees->contains(fn($f) => stripos($f, 'scholar') !== false)
                      || $snapshot->contains(fn($s) => stripos(($s['label'] ?? '').' '.($s['value'] ?? ''), 'scholar') !== false);
    $verifyTag = '<span class="verify-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>verify</span>';
    $initials = fn (?string $name, int $take = 2) =>
        collect(preg_split('/\s+/', trim($name ?? '')))
            ->filter()
            ->map(fn ($w) => mb_substr($w, 0, 1))
            ->take($take)
            ->implode('');
    $heroBgUrl = media_url($program->image_url, 'assets/images/edutainment/hero-cinematic.jpg');
    $uniImgUrl = media_url(data_get($university, 'image'), 'assets/images/edutainment/international-students-university-campus-1.jpg');
    $learnImgUrl = cached_asset('assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg');
    // Mirrors Testimonial::getAutoThumbnailAttribute() / getEmbedUrlAttribute() for JSON testimonials
    $youtubeId = function (?string $url): ?string {
        $url = trim($url ?? '');
        if ($url === '') return null;
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $m)) return $m[1];
        if (preg_match('/^([a-zA-Z0-9_-]{11})$/', $url, $m)) return $m[1];
        return null;
    };
    $storyThumb = function (array $t) use ($youtubeId): ?array {
        if ($url = media_url($t['thumb'] ?? null)) {
            return ['src' => $url, 'retry' => null];
        }
        if ($id = $youtubeId($t['video'] ?? null)) {
            return [
                'src'   => "https://img.youtube.com/vi/{$id}/maxresdefault.jpg",
                'retry' => "https://img.youtube.com/vi/{$id}/hqdefault.jpg",
            ];
        }
        return null;
    };
    $embedUrl = function (?string $url) use ($youtubeId): ?string {
        $url = trim($url ?? '');
        if ($url === '') return null;
        if ($id = $youtubeId($url)) return "https://www.youtube.com/embed/{$id}?autoplay=1&rel=0";
        if (preg_match('/vimeo\.com\/(?:.*\/)?(\d+)/i', $url, $m)) return "https://player.vimeo.com/video/{$m[1]}?autoplay=1";
        return $url;
    };
    $renderLogoChip = function (?string $name, ?string $logo, string $chipClass, string $fallbackClass, int $take = 2) use ($initials) {
        $url = media_url($logo);
        $abbr = e($initials($name, $take));
        $alt = e($name ?? '');
        if ($url) {
            return '<span class="' . $chipClass . '"><img src="' . e($url) . '" alt="' . $alt . '" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false"><span class="' . $fallbackClass . '" hidden>' . $abbr . '</span></span>';
        }
        return '<span class="' . $chipClass . '"><span class="' . $fallbackClass . '">' . $abbr . '</span></span>';
    };
@endphp

<div class="page-pd">

    {{-- ============ STICKY BAR ============ --}}
    <div class="sticky-bar">
        <div class="sticky-inner">
            <div class="sticky-ctas">
                <a href="#enquire" class="btn sticky-enquire"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg><span>{{ $chrome->enquire_label ?? 'Enquire Now' }}</span></a>
                <a href="#enquire" class="btn sticky-apply"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg><span>{{ $chrome->apply_label ?? 'Apply Now' }}</span></a>
            </div>
        </div>
    </div>

    {{-- ============ 1. HERO (Cinematic Dark) ============ --}}
    <section class="hero" id="top" aria-label="{{ $program->title }}">
        <div class="hero-backdrop" style="--hero-bg: url('{{ $heroBgUrl }}')"></div>
        @if($hasScholarship)<span class="ribbon">{{ $chrome->scholarship_badge ?? 'Scholarship Available' }}</span>@endif
        <div class="container">
            <div class="hero-badge rv"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>{{ $program->level ?: 'Undergraduate' }} @if($cat) · {{ $cat->name }} @endif</div>
            <div class="hero-grid">
                <div class="hero-copy">
                    <h1 class="d rv">{{ $program->title }}</h1>
                    @if($program->short_description)<p class="lead rv rv-d1">{{ $program->short_description }}</p>@endif
                    <div class="hero-ctas rv rv-d2">
                        <a href="#enquire" class="btn btn-red">{{ $chrome->apply_label ?? 'Apply Now' }}<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                        <a href="{{ route('contact') }}" class="btn btn-outline">{{ $chrome->download_brochure_label ?? 'Download Brochure' }}<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg></a>
                        <a href="#enquire" class="btn btn-outline">{{ $chrome->enquire_label ?? 'Enquire Now' }}<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                    </div>
                    @if($highlights->count())
                    <div class="hero-hl rv rv-d3">
                        <div class="hl-lab">{{ $chrome->quick_highlights_label ?? 'Quick Highlights' }}</div>
                        <div class="hl-grid">
                            @foreach($highlights as $h)
                                <div class="hl"><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></span>{{ $h['value'] ?? $h['label'] }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="hero-meta rv rv-d4">
                        @if($program->duration)<span class="m"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>{{ $program->duration }}</span><span class="sep"></span>@endif
                        @if($program->level)<span class="m"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>{{ $program->level }}</span><span class="sep"></span>@endif
                        @if($reviews->count())<span class="m"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>{{ $reviews->count() }} Google reviews</span>@endif
                    </div>
                </div>
                @if($snapshot->count())
                <div class="hero-card rv rv-d2">
                    <h3>{{ $chrome->glance_heading ?? 'Programme at a Glance' }}</h3>
                    @foreach($snapshot->take(6) as $s)
                        <div class="hc-row"><span class="l">{{ $s['label'] ?? '' }}</span><span class="v">{{ $s['value'] ?? '' }}</span></div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ============ 2. RECOGNITION STRIP (slider) ============ --}}
    @if($recognition->count())
    <section class="recognition" aria-label="Accredited and recognised by">
        <div class="container">
            <div class="rec-head rv">
                <span class="lab">Awarded By · <b>{{ $program->universityPartner?->name ?? (data_get($recognition->first(), 'name') ?? '') }}</b></span>
                <span class="lab">Recognised &amp; Accredited</span>
            </div>
            <div class="rec-track">
                <div class="rec-slider">
                    @foreach($recognition as $r)
                        <div class="rec-card">{!! $renderLogoChip($r['name'] ?? '', $r['logo'] ?? null, 'rec-logo', 'rec-logo-fallback', 3) !!}</div>
                    @endforeach
                    @foreach($recognition as $r)
                        <div class="rec-card" aria-hidden="true">{!! $renderLogoChip($r['name'] ?? '', $r['logo'] ?? null, 'rec-logo', 'rec-logo-fallback', 3) !!}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 3. SNAPSHOT (Bento Grid) ============ --}}
    <!-- @if($snapshot->count())
    <section class="snapshot section tex-mesh" aria-label="Programme snapshot">
        <div class="container">
            <div class="sec-head rv">
                <span class="kicker">Programme Snapshot</span>
                <h2>{{ $chrome->glance_heading ?? 'Everything at a' }} <em>Glance</em></h2>
            </div>
            <div class="snap-grid">
                @foreach($snapshot as $i => $s)
                    <div class="snap-tile @if($i === 0) snap-feat @endif rv rv-d{{ min($i % 4 + 1, 4) }}">
                        <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7 7 1-5 5 1 7-6-4-6 4 1-7-5-5 7-1z"/></svg></span>
                        <div><div class="k">{{ $s['label'] ?? '' }}</div><div class="v">{{ $s['value'] ?? '' }}</div></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif -->

    {{-- ============ 4. OVERVIEW (Flat + Editorial) ============ --}}
    @if($program->description)
    <section class="overview section" aria-label="Programme introduction">
        <div class="container ov-grid">
            <div class="ov-copy rv">
                <span class="kicker">{{ $chrome->overview_label ?? 'Programme Overview' }}</span>
                <h2 class="d" style="font-size:clamp(28px,3.4vw,42px);line-height:1.06;letter-spacing:-.03em;margin:14px 0 22px">{{ $chrome->overview_heading ?? 'About this' }} <em>Programme</em></h2>
                <div class="ov-rule"></div>
                <div class="ov-copy"><p>{!! rich_html($program->description ?? null) !!}</p></div>
            </div>
            @if($highlights->count())
            <div class="ov-figure rv rv-d1">
                @if($uniImgUrl)<img class="ov-photo" src="{{ $uniImgUrl }}" alt="Campus at {{ $university->name ?? 'partner university' }}" loading="lazy">@endif
                <div class="ov-shade"></div>
                @if($hasScholarship)<span class="badge">Globally Recognised</span>@endif
                <div class="stat">
                    <div class="num">{{ $highlights->first()['value'] ?? 'Leadership' }}</div>
                    <div class="cap">{{ collect($highlights->pluck('value'))->implode(' · ') }}</div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- ============ 5. WHY CHOOSE (Flat + 2D) ============ --}}
    @if($benefits->count())
    <section class="why section tex-grid" aria-label="Why choose this programme">
        <div class="container">
            <div class="sec-head rv">
                <span class="kicker">{{ $chrome->why_label ?? 'Why Choose' }}</span>
                <h2>{{ $chrome->why_heading ?? 'Why Choose This' }} <em>Programme?</em></h2>
            </div>
            <div class="why-grid">
                @foreach($benefits as $i => $b)
                    <div class="why-card w-{{ min($i % 5 + 1, 5) }} rv rv-d{{ min($i % 3 + 1, 3) }}">
                        <span class="why-index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="why-illu"><i data-lucide="{{ $b['icon'] ?? 'sparkles' }}"></i></span>
                        <h4>{{ $b['title'] ?? '' }}</h4>
                        <p>{!! html_filled($b['desc'] ?? null) ? rich_html($b['desc'] ?? null) : '' !!}</p>
                    </div>
                @endforeach
                @if(filled(data_get($university, 'name')))
                <div class="w-note rv rv-d2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><span>An internationally recognised pathway awarded by {{ data_get($university, 'name') }}.</span></div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 6. WHAT YOU'LL LEARN (Organic) ============ --}}
    @if($learning->count())
    <section class="learn section tex-mesh" aria-label="Learning outcomes">
        <div class="container learn-wrap">
            <div class="learn-sticky rv">
                <span class="kicker">{{ $chrome->learn_label ?? 'What You\'ll Learn' }}</span>
                <h2 class="d" style="font-size:clamp(28px,3.4vw,42px);line-height:1.06;letter-spacing:-.03em;margin:14px 0 18px">{{ $chrome->learn_heading ?? 'Learning' }} <em>Outcomes</em></h2>
                <figure class="learn-photo"><img src="{{ $learnImgUrl }}" alt="Students in a collaborative learning environment" loading="lazy"></figure>
                <p style="font-size:16px;line-height:1.7;color:var(--muted)">{!! html_filled($chrome->learn_intro ?? null) ? rich_html($chrome->learn_intro ?? null) : 'Students will learn to:' !!}</p>
            </div>
            <div class="learn-field">
                @foreach($learning as $i => $cap)
                    <div class="learn-item rv rv-d{{ min($i % 4 + 1, 4) }}"><span class="n">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span><span class="txt">{{ $cap }}</span></div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 7. CAREER OPPORTUNITIES (Bento cloud) ============ --}}
    @if($careers->count())
    <section class="careers section tex-grid" aria-label="Career opportunities">
        <div class="container">
            <div class="sec-head rv">
                <span class="kicker">{{ $chrome->career_label ?? 'Career Opportunities' }}</span>
                <h2>{{ $chrome->career_heading ?? 'Where This Degree Can' }} <em>Take You</em></h2>
                <p>{!! html_filled($chrome->career_intro ?? null) ? rich_html($chrome->career_intro ?? null) : 'Potential careers include:' !!}</p>
            </div>
            <div class="career-cloud">
                @foreach($careers as $i => $career)
                    <div class="career-tile @if($i === 0) career-tile--feat @else c-s @endif rv rv-d{{ min($i % 4 + 1, 4) }}">
                        <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 7a2 2 0 0 1 2-2h4l2 3h9a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/></svg></span>
                        <span class="nm">{{ $career }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 8. PROGRAMME STRUCTURE (accordions) ============ --}}
    @if($structure->count())
    <section class="structure section" aria-label="Programme structure">
        <div class="container">
            <div class="sec-head center rv">
                <span class="kicker">{{ $chrome->structure_label ?? 'Programme Structure' }}</span>
                <h2>{{ $chrome->structure_heading ?? 'Your Journey,' }} <em>Year by Year</em></h2>
                <p>{!! html_filled($chrome->structure_intro ?? null) ? rich_html($chrome->structure_intro ?? null) : 'A structured curriculum that builds from foundations through to advanced study.' !!}</p>
            </div>
            <div class="struct-list">
                @foreach($structure as $i => $stage)
                <details class="struct-item rv" @if($i === 0) open @endif>
                    <summary><span class="y-num">Y{{ $i + 1 }}</span><span class="y-main"><div class="y-lab">Year {{ $i + 1 }}</div><div class="y-title">{{ $stage['title'] ?? '' }}</div></span><span class="plus"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></span></summary>
                    <div class="struct-mods">
                        @if(!empty($stage['subtitle']))<div class="m-lab">{{ $stage['subtitle'] }}</div>@endif
                        <ul class="mod-list">
                            @foreach(($stage['modules'] ?? []) as $m)
                                <li class="mod-row">{{ is_array($m) ? ($m['title'] ?? '') : $m }}</li>
                            @endforeach
                        </ul>
                    </div>
                </details>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 9. ABOUT GAU (Cinematic Dark) ============ --}}
    @if(filled(data_get($university, 'name')))
    <section class="gau section" aria-label="About the awarding university">
        <div class="tex-grid"></div>
        <div class="container gau-grid">
            <div class="gau-photo rv">
                @if($uniImgUrl)<img class="gau-img" src="{{ $uniImgUrl }}" alt="{{ $university->name }}" loading="lazy">@endif
                <div class="glow"></div>
                @if($university->name)<span class="logo">{{ collect(preg_split('/\s+/', $university->name))->map(fn($w) => mb_substr($w, 0, 1))->take(3)->implode('') }}</span>@endif
                @if($university->establishment)<div class="est"><div class="k">Established</div><div class="v">{{ str_replace('Established ', '', $university->establishment) }}</div></div>@endif
            </div>
            <div class="gau-copy rv rv-d1">
                <span class="kicker">{{ $chrome->university_label ?? 'The University' }}</span>
                <h2>{{ $chrome->university_heading ?? 'A Globally Connected' }} <em>University</em></h2>
                @if($university->description)<p>{!! rich_html($university->description ?? null) !!}</p>@endif
                @if($university->establishment)
                <div class="gau-metrics">
                    <div class="gau-metric"><div class="num">{{ str_replace('Established ', '', $university->establishment) }}<small>+</small></div><div class="lbl">Established</div></div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 10. ACCREDITATION (logo grids) ============ --}}
    @if($accreditationGroups->count())
    <section class="accred section tex-grid" aria-label="Accreditation and recognition">
        <div class="container">
            <div class="sec-head rv">
                <span class="kicker">{{ $chrome->accreditation_label ?? 'Accreditation' }}</span>
                <h2>{{ $chrome->accreditation_heading ?? 'Accreditation &amp;' }} <em>Recognition</em></h2>
            </div>
            @php
                $accItems = $accreditationGroups->flatMap(function ($g) {
                    return collect($g['items'] ?? [])->map(fn ($item) => [
                        'group' => $g['group'] ?? null,
                        'name'  => $item['name'] ?? '',
                        'logo'  => $item['logo'] ?? null,
                    ]);
                })->values();
            @endphp
            @if($accItems->count())
            <div class="acc-board rv" data-acc-board>
                <div class="acc-board-head">
                    <!-- <span class="acc-caption">{{ $accItems->count() }} {{ Str::plural('accreditation', $accItems->count()) }} across {{ $accreditationGroups->count() }} {{ Str::plural('category', $accreditationGroups->count()) }}</span> -->
                    <div class="acc-nav">
                        <button type="button" class="acc-btn" data-acc-prev aria-label="Show previous accreditations"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
                        <button type="button" class="acc-btn" data-acc-next aria-label="Show next accreditations"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg></button>
                    </div>
                </div>
                <div class="acc-rail-mask">
                    <ul class="acc-rail" data-acc-rail>
                        @php $prevGroup = null; @endphp
                        @foreach($accItems as $item)
                            <li class="acc-tile @if($prevGroup !== null && $prevGroup === $item['group']) is-same-group @endif">
                                @if(!empty($item['group']))<span class="acc-eyebrow">{{ $item['group'] }}</span>@endif
                                <span class="acc-plate">{!! $renderLogoChip($item['name'] ?? '', $item['logo'] ?? null, 'acc-plate-in', 'acc-plate-fallback', 3) !!}</span>
                                @if(!empty($item['name']))<span class="acc-name">{{ $item['name'] }}</span>@endif
                            </li>
                            @php $prevGroup = $item['group']; @endphp
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- ============ 11. WHY MAVERICK (3x3 grid) ============ --}}
    @if($support->count())
    <section class="maverick section tex-mesh" aria-label="Why study through Maverick">
        <div class="container">
            <div class="sec-head center rv">
                <span class="kicker">{{ $chrome->partner_label ?? 'Your Learning Partner' }}</span>
                <h2>{{ $chrome->partner_heading ?? 'Why Study Through' }} <em>Maverick?</em></h2>
                <p>{!! html_filled($chrome->partner_intro ?? null) ? rich_html($chrome->partner_intro ?? null) : 'Students receive:' !!}</p>
            </div>
            <div class="mav-grid">
                @foreach($support as $i => $s)
                    <div class="mav-card rv rv-d{{ min($i % 3 + 1, 3) }}">
                        <span class="ic"><i data-lucide="{{ $i % 2 === 0 ? 'book-open-check' : 'users-round' }}"></i></span>
                        <div class="nm">{{ $s }}</div>
                        <div class="ds">Support designed to help you succeed at every stage.</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 12. SUCCESS STORIES (slider) ============ --}}
    @if($testimonials->count())
    <section class="stories section" aria-label="Student success stories">
        <div class="container">
            <div class="sec-head rv">
                <span class="kicker">{{ $chrome->stories_label ?? 'Stories' }}</span>
                <h2>{{ $chrome->stories_heading ?? 'Student Success' }} <em>Stories</em></h2>
            </div>
            <div class="story-shell" id="storyShell">
                <div class="story-window" tabindex="0" role="group" aria-label="Student success stories carousel">
                    <div class="story-track" id="storyTrack">
                        @foreach($testimonials as $t)
                        @php
                            $thumb = $storyThumb($t);
                            $embed = $embedUrl($t['video'] ?? null);
                            $storyInitials = $initials($t['name'] ?? '');
                        @endphp
                        <div class="story-card">
                            <div class="story-media">
                                <div class="art" aria-hidden="true">@if($storyInitials)<span class="art-mono">{{ $storyInitials }}</span>@endif<svg class="art-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
                                @if($thumb)
                                    <img class="story-thumb" src="{{ $thumb['src'] }}" alt="{{ $t['name'] ?? 'Student story' }}" loading="lazy" @if($thumb['retry']) data-retry="{{ $thumb['retry'] }}" @endif onerror="if(this.dataset.retry){this.src=this.dataset.retry;delete this.dataset.retry;}else{this.closest('.story-media').classList.add('no-thumb');}">
                                    <span class="story-shade" aria-hidden="true"></span>
                                @endif
                                <span class="tag">@if(!empty($t['category'])){{ $t['category'] }} · @endif@if(!empty($t['video']))Video @else Story @endif</span>
                                @if($embed)<div class="play"><a href="{{ $t['video'] }}" class="pp" target="_blank" rel="noopener" data-video-embed="{{ $embed }}" data-video-title="{{ $t['name'] ?? 'Student story' }}" aria-label="Play {{ $t['name'] ?? 'student' }}'s video"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></a></div>@endif
                            </div>
                            <div class="story-body">
                                <div class="story-by">
                                    <span class="story-ava">{{ $storyInitials }}</span>
                                    <div><div class="nm">{{ $t['name'] ?? '' }}</div>
                                    <div class="rl">@if(!empty($t['role'])){{ $t['role'] }}@endif@if(!empty($t['country'])) · {{ $t['country'] }}@endif</div></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @if($testimonials->count() > 1)
                <div class="story-nav">
                    <button type="button" class="story-btn" id="storyPrev" aria-label="Previous stories" aria-controls="storyTrack"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
                    <button type="button" class="story-btn" id="storyNext" aria-label="Next stories" aria-controls="storyTrack"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg></button>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 13. FEES (Cinematic Dark) ============ --}}
    @if($fees->count())
    <section class="fees section" aria-label="Fees and scholarships">
        <div class="tex-grid"></div>
        <div class="container fees-grid">
            <div class="rv">
                <span class="kicker">Fees &amp; Scholarships</span>
                <h2 class="d" style="font-family:var(--font-display);font-size:clamp(28px,3.4vw,42px);line-height:1.06;letter-spacing:-.03em;color:#fff;margin:14px 0 14px">Fees &amp; <em style="color:var(--color-mba-red)">Scholarships</em></h2>
                <p style="font-size:16px;line-height:1.7;color:rgba(255,255,255,.72);max-width:46ch">{!! html_filled($chrome->fees_intro ?? null) ? rich_html($chrome->fees_intro ?? null) : 'Fee structure varies by intake and study mode. Select any option to receive the full details.' !!}</p>
                <div class="fee-list">
                    @foreach($fees as $fee)
                        <a href="#enquire" class="fee-chip"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 15h4M7 11h10"/></svg></span><div><span class="nm">{{ $fee }}</span><span class="rm">{{ $chrome->fees_request_label ?? 'View details' }} →</span></div></a>
                    @endforeach
                </div>
            </div>
            <div class="fees-side rv rv-d1">
                <div class="k">Admissions</div>
                <h3>Request the full fee structure</h3>
                <p>Speak to our admissions team for a personalised breakdown based on your intake and study mode.</p>
                <a href="{{ route('contact') }}" class="fees-cta">Request Fee Structure <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                <p class="fee-note">No commitment required. We'll connect you with an advisor.</p>
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 14. FAQ (accordion + organic dividers) ============ --}}
    @if($faqs->count())
    <section class="faq section" aria-label="Frequently asked questions">
        <div class="faq-divider top"><svg viewBox="0 0 1440 80" preserveAspectRatio="none"><path d="M0,40 Q360,80 720,40 T1440,40 V0 H0 Z" fill="#071444" opacity="1"/></svg></div>
        <div class="faq-divider bot"><svg viewBox="0 0 1440 80" preserveAspectRatio="none"><path d="M0,40 Q360,0 720,40 T1440,40 V80 H0 Z" fill="#071444" opacity="1"/></svg></div>
        <div class="container">
            <div class="sec-head center rv">
                <span class="kicker">Questions</span>
                <h2>{{ $chrome->faq_heading ?? 'Frequently Asked' }} <em>Questions</em></h2>
            </div>
            <div class="faq-wrap">
                @foreach($faqs as $i => $faq)
                <details class="faq-item rv" @if($i === 0) open @endif><summary>{{ $faq->question }}<span class="plus"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></span></summary><div class="faq-ans">{!! rich_html($faq->answer ?? null) !!}</div></details>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ 15. ENQUIRY FORM ============ --}}
    <section class="enquiry section" id="enquire" aria-label="Enquire about this programme">
        <div class="container enq-grid">
            <div class="enq-copy rv">
                <span class="kicker">Enquire</span>
                <h2 class="d" style="font-size:clamp(28px,3.4vw,42px);line-height:1.06;letter-spacing:-.03em;margin:14px 0 18px">{{ $chrome->enquiry_heading ?? 'Enquire About This' }} <em>Programme</em></h2>
                <p>{{ $chrome->enquiry_subheading ?? 'Share a few details and our admissions team will reach out to guide you through the next steps.' }}</p>
                <div class="enq-points">
                    <div class="enq-point"><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></span>Personal eligibility review</div>
                    <div class="enq-point"><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></span>Guidance on fees &amp; instalments</div>
                    <div class="enq-point"><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></span>Application &amp; study-mode support</div>
                </div>
                <div class="enq-trust">
                    <span class="trust-badge"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3 7 7 1-5 5 1 7-6-4-6 4 1-7-5-5 7-1z"/></svg>Flexible study</span>
                    @if($reviews->count())<span class="trust-badge"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3 7 7 1-5 5 1 7-6-4-6 4 1-7-5-5 7-1z"/></svg>{{ $reviews->count() }} Google reviews</span>@endif
                </div>
            </div>
            <form class="enq-form rv rv-d1" action="{{ route('programs.enquire') }}" method="POST">
                @csrf
                <input type="hidden" name="programme" value="{{ $program->title }}">
                <h3>Request a call back</h3>
                <p class="fs">Fields marked * are required.</p>
                <div class="f-row">
                    <div class="fld"><label for="pd-name">Full Name *</label><input id="pd-name" name="name" type="text" required></div>
                    <div class="fld"><label for="pd-email">Email *</label><input id="pd-email" name="email" type="email" required></div>
                </div>
                <div class="f-row">
                    <div class="fld"><label for="pd-phone">Phone *</label><input id="pd-phone" name="phone" type="tel" required></div>
                    <div class="fld"><label for="pd-country">Country</label><input id="pd-country" name="country" type="text"></div>
                </div>
                <div class="fld"><label for="pd-study-mode">Preferred Study Mode</label>
                    <select id="pd-study-mode" name="study_mode"><option value="">Select study mode</option><option>Online</option><option>Hybrid</option><option>Part-time</option></select>
                </div>
                <div class="fld"><label for="pd-qualification">Highest Qualification</label>
                    <select id="pd-qualification" name="qualification"><option value="">Select qualification</option><option value="high-school">High School / Secondary</option><option value="diploma">Diploma</option><option value="bachelor">Bachelor's Degree</option><option value="master">Master's Degree</option><option value="other">Other</option></select>
                </div>
                <div class="fld"><label for="pd-message">Message</label><textarea id="pd-message" name="message" rows="3"></textarea></div>
                <button type="submit" class="enq-submit">Submit Enquiry</button>
            </form>
        </div>
    </section>

    {{-- ============ 16. REVIEWS (Google rating) ============ --}}
    @if($reviews->count())
    <section class="reviews section tex-grid" aria-label="Student reviews">
        <div class="container">
            <div class="sec-head center rv">
                <span class="kicker">Reviews</span>
                <h2>Student <em>Reviews</em></h2>
            </div>
            @php
                $avg = round($reviews->avg('rating'), 1);
                $fullStars = (int) round($avg);
            @endphp
            <div class="rev-top rv">
                <div class="rating-badge">
                    <div class="glogo">G</div>
                    <div class="score">{{ $avg }}</div>
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)<svg viewBox="0 0 24 24"><path d="M12 2l3 7 7 1-5 5 1 7-6-4-6 4 1-7-5-5 7-1z"/></svg>@endfor
                    </div>
                    <div class="based">Based on {{ $reviews->count() }} student reviews</div>
                </div>
            </div>
            <div class="rev-flow">
                @foreach($reviews as $r)
                <div class="rev-card">
                    <div class="head"><span class="ava">@php $avatarUrl = media_url($r['avatar'] ?? null); @endphp
                        @if($avatarUrl)
                            <img class="ava-img" src="{{ $avatarUrl }}" alt="{{ $r['name'] ?? '' }}" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false">
                            <span class="ava-fallback" hidden>{{ $initials($r['name'] ?? '') }}</span>
                        @else
                            <span class="ava-fallback">{{ $initials($r['name'] ?? '') }}</span>
                        @endif
                    </span><div><div class="nm">{{ $r['name'] ?? '' }}</div><div class="rl" style="font-size:12px;color:var(--muted)">Student</div></div></div>
                    <div class="stars">@for($i = 1; $i <= ($r['rating'] ?? 5); $i++)<svg viewBox="0 0 24 24"><path d="M12 2l3 7 7 1-5 5 1 7-6-4-6 4 1-7-5-5 7-1z"/></svg>@endfor</div>
                    @if(!empty($r['review']))
                    <div class="q-wrap" data-rev-clamp>
                        <div class="q" id="rev-q-{{ $loop->index }}">{!! $r['review'] !!}</div>
                        <button type="button" class="rev-more" aria-expanded="false" aria-controls="rev-q-{{ $loop->index }}">
                            <span class="rev-more-label">Read more</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ FINAL CTA ============ --}}
    <section class="final" aria-label="Call to action">
        <div class="container">
            <div class="final-card rv">
                <div class="glow" aria-hidden="true"></div>
                <div class="final-inner">
                    <h2 class="d">{{ $chrome->final_cta_heading ?? 'Ready to Begin Your' }} <em>Journey?</em></h2>
                    <p>{!! html_filled($chrome->final_cta_body ?? null) ? rich_html($chrome->final_cta_body ?? null) : 'Speak to our admissions team today and take the first step toward a globally recognised degree.' !!}</p>
                    <div class="ctas">
                        <a href="#enquire" class="btn btn-white">Apply Now<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                        <a href="#enquire" class="btn btn-outline">Enquire Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FLOATING WHATSAPP ============ --}}
    <!-- <a class="whatsapp" href="{{ route('contact') }}" aria-label="Chat on WhatsApp"><svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg></a> -->

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Accordions: close others in same group (structure, FAQ)
    document.querySelectorAll('.struct-item, .faq-item').forEach(details => {
        details.addEventListener('toggle', () => {
            if (!details.open) return;
            const group = details.parentElement;
            group.querySelectorAll('details[open]').forEach(d => { if (d !== details) d.open = false; });
        });
    });

    // Reveal-on-scroll (vanilla, respects reduced-motion, never hides content without JS)
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealTargets = document.querySelectorAll('.page-pd .rv');
    if (revealTargets.length && !prefersReduced && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) { entry.target.classList.add('in'); io.unobserve(entry.target); }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
        revealTargets.forEach((el) => io.observe(el));
    } else if (prefersReduced) {
        revealTargets.forEach((el) => el.classList.add('in'));
    }
    // Fail-open: never leave content hidden
    window.setTimeout(() => { document.querySelectorAll('.page-pd .rv:not(.in)').forEach(el => el.classList.add('in')); }, 3000);

    // Reviews: 10-line clamp with expand/collapse, one open at a time
    const revWraps = Array.from(document.querySelectorAll('[data-rev-clamp]'));
    if (revWraps.length) {
        const clampHeight = q => {
            const lines = parseInt(getComputedStyle(q).getPropertyValue('--rev-lines'), 10) || 10;
            const lh = parseFloat(getComputedStyle(q).lineHeight) || 22;
            return Math.round(lines * lh);
        };

        const collapse = wrap => {
            const q = wrap.querySelector('.q');
            const btn = wrap.querySelector('.rev-more');
            if (!q || !wrap.classList.contains('is-open')) return;
            q.style.maxHeight = q.scrollHeight + 'px';
            // Force a reflow so the transition runs from the measured height
            void q.offsetHeight;
            wrap.classList.remove('is-open');
            q.style.maxHeight = clampHeight(q) + 'px';
            if (btn) {
                btn.setAttribute('aria-expanded', 'false');
                const label = btn.querySelector('.rev-more-label');
                if (label) label.textContent = 'Read more';
            }
        };

        const expand = wrap => {
            const q = wrap.querySelector('.q');
            const btn = wrap.querySelector('.rev-more');
            if (!q) return;
            revWraps.forEach(other => { if (other !== wrap) collapse(other); });
            wrap.classList.add('is-open');
            q.style.maxHeight = q.scrollHeight + 'px';
            if (btn) {
                btn.setAttribute('aria-expanded', 'true');
                const label = btn.querySelector('.rev-more-label');
                if (label) label.textContent = 'Show less';
            }
        };

        revWraps.forEach(wrap => {
            const q = wrap.querySelector('.q');
            const btn = wrap.querySelector('.rev-more');
            if (!q || !btn) return;

            wrap.classList.add('is-clamped');
            if (q.scrollHeight <= q.clientHeight + 2) {
                wrap.classList.remove('is-clamped');
                return;
            }

            // Once the transition settles, drop the cap so reflows can't re-clip the text
            q.addEventListener('transitionend', e => {
                if (e.propertyName !== 'max-height') return;
                if (wrap.classList.contains('is-open')) q.style.maxHeight = 'none';
                else q.style.maxHeight = '';
            });

            btn.addEventListener('click', () => {
                if (wrap.classList.contains('is-open')) collapse(wrap);
                else expand(wrap);
            });
        });
    }

    // Accreditation rail: arrows + drag, only when the logos actually overflow
    document.querySelectorAll('[data-acc-board]').forEach(board => {
        const rail = board.querySelector('[data-acc-rail]');
        if (!rail) return;
        const prev = board.querySelector('[data-acc-prev]');
        const next = board.querySelector('[data-acc-next]');

        const step = () => {
            const tile = rail.querySelector('.acc-tile');
            const gap = parseFloat(getComputedStyle(rail).columnGap) || 14;
            return tile ? tile.getBoundingClientRect().width + gap : rail.clientWidth * 0.8;
        };
        const syncArrows = () => {
            const max = rail.scrollWidth - rail.clientWidth;
            if (prev) prev.disabled = rail.scrollLeft <= 1;
            if (next) next.disabled = rail.scrollLeft >= max - 1;
        };
        const measure = () => {
            board.classList.toggle('is-scrollable', rail.scrollWidth > rail.clientWidth + 1);
            syncArrows();
        };

        if (prev) prev.addEventListener('click', () => rail.scrollBy({ left: -step(), behavior: prefersReduced ? 'auto' : 'smooth' }));
        if (next) next.addEventListener('click', () => rail.scrollBy({ left: step(), behavior: prefersReduced ? 'auto' : 'smooth' }));
        rail.addEventListener('scroll', syncArrows, { passive: true });
        window.addEventListener('resize', measure);
        rail.querySelectorAll('img').forEach(img => {
            if (!img.complete) img.addEventListener('load', measure, { once: true });
        });

        // Pointer drag-to-scroll (threshold keeps clicks/links intact)
        let dragging = false, startX = 0, startScroll = 0, moved = 0;
        rail.addEventListener('pointerdown', e => {
            if (e.pointerType === 'touch' || !board.classList.contains('is-scrollable')) return;
            dragging = true; moved = 0;
            startX = e.clientX;
            startScroll = rail.scrollLeft;
        });
        rail.addEventListener('pointermove', e => {
            if (!dragging) return;
            const dx = e.clientX - startX;
            if (Math.abs(dx) > 4) {
                if (!moved) board.classList.add('is-dragging');
                moved = Math.abs(dx);
                rail.scrollLeft = startScroll - dx;
            }
        });
        const endDrag = () => {
            if (!dragging) return;
            dragging = false;
            board.classList.remove('is-dragging');
        };
        rail.addEventListener('pointerup', endDrag);
        rail.addEventListener('pointercancel', endDrag);
        rail.addEventListener('pointerleave', endDrag);

        measure();
    });

    // Story slider
    const track = document.getElementById('storyTrack');
    if (track) {
        const shell = document.getElementById('storyShell');
        const cards = track.children.length;
        const next = document.getElementById('storyNext');
        const prev = document.getElementById('storyPrev');
        const perView = () => (window.innerWidth > 1000 ? 3 : window.innerWidth > 640 ? 2 : 1);
        let si = 0;

        const update = () => {
            const v = perView();
            const max = Math.max(0, cards - v);
            si = Math.min(Math.max(si, 0), max);
            track.style.transform = si ? `translateX(-${si * (100 / v)}%)` : '';
            if (shell) shell.classList.toggle('is-scrollable', max > 0);
            [[prev, si <= 0], [next, si >= max]].forEach(([btn, off]) => {
                if (!btn) return;
                btn.disabled = off;
                btn.setAttribute('aria-disabled', off ? 'true' : 'false');
            });
        };
        const go = delta => { si += delta; update(); };

        if (next) next.addEventListener('click', () => go(1));
        if (prev) prev.addEventListener('click', () => go(-1));
        window.addEventListener('resize', update);

        const win = track.parentElement;
        if (win) win.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight') { go(1); e.preventDefault(); }
            else if (e.key === 'ArrowLeft') { go(-1); e.preventDefault(); }
        });

        update();
    }

    // Video modal for story play buttons
    const videoTriggers = document.querySelectorAll('[data-video-embed]');
    if (videoTriggers.length) {
        let overlay = null, lastFocus = null;

        const close = () => {
            if (!overlay) return;
            overlay.remove();
            overlay = null;
            document.body.style.overflow = '';
            document.removeEventListener('keydown', onKeydown);
            if (lastFocus) lastFocus.focus();
        };
        function onKeydown(e) {
            if (!overlay) return;
            if (e.key === 'Escape') { close(); return; }
            if (e.key !== 'Tab') return;
            const focusable = overlay.querySelectorAll('button, iframe');
            if (!focusable.length) return;
            const first = focusable[0], last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) { last.focus(); e.preventDefault(); }
            else if (!e.shiftKey && document.activeElement === last) { first.focus(); e.preventDefault(); }
        }

        const open = (src, title) => {
            close();
            lastFocus = document.activeElement;
            overlay = document.createElement('div');
            overlay.className = 'pd-vmodal';
            overlay.setAttribute('role', 'dialog');
            overlay.setAttribute('aria-modal', 'true');
            overlay.setAttribute('aria-label', title || 'Video');
            overlay.innerHTML =
                '<div class="pd-vmodal-frame">' +
                    '<button type="button" class="pd-vmodal-close" aria-label="Close video">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>' +
                    '</button>' +
                    '<div class="pd-vmodal-media"></div>' +
                '</div>';

            const frame = document.createElement('iframe');
            frame.src = src;
            frame.title = title || 'Video';
            frame.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            frame.allowFullscreen = true;
            frame.setAttribute('frameborder', '0');
            overlay.querySelector('.pd-vmodal-media').appendChild(frame);

            overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
            overlay.querySelector('.pd-vmodal-close').addEventListener('click', close);
            document.addEventListener('keydown', onKeydown);
            document.body.style.overflow = 'hidden';
            document.body.appendChild(overlay);
            overlay.querySelector('.pd-vmodal-close').focus();
        };

        videoTriggers.forEach(trigger => {
            trigger.addEventListener('click', e => {
                const src = trigger.dataset.videoEmbed;
                if (!src) return;
                e.preventDefault();
                open(src, trigger.dataset.videoTitle);
            });
        });
    }
});
</script>
@endpush

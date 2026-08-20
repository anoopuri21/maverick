@extends('layouts.app')

@section('title', ($page->seo['meta_title'] ?? 'Global Opportunities').' - Maverick Business Academy')
@section('meta_description', $page->seo['meta_description'] ?? 'Explore global opportunities — student exchange and edutainment experiences around the world.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-pathways.css') }}">
@endpush

@push('head')
    @include('partials.seo-meta', ['seo' => (object)($page->seo ?? [])])
@endpush

@section('content')
<div class="gpw">

    <section class="gpw-hero">
        <div class="glow"></div>
        <div class="container">
            @if(filled($page->eyebrow))<span class="gpw-eyebrow">{{ $page->eyebrow }}</span>@endif
            <h1>{{ $page->heading }}<em>{{ $page->heading_italic }}</em></h1>
            @if(filled($page->intro))<div class="lead">{!! $page->intro !!}</div>@endif
        </div>
    </section>

    <section class="gpw-list">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">Global Opportunities</div>
                <h2 class="d">{{ $page->title ?? 'Global Opportunities' }}</h2>
            </div>
            @if(count($page->items_list))
            <div class="gpw-cards">
                @foreach($page->items_list as $i => $item)
                <div class="gpw-card">
                    <div class="card-top">
                        <span class="card-ic">
                            @if(!empty($item['icon']))
                            <i data-lucide="{{ $item['icon'] }}"></i>
                            @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7 7 1-5 5 1 7-6-4-6 4 1-7-5-5 7-1z"/></svg>
                            @endif
                        </span>
                        <span class="card-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="d">{{ $item['title'] ?? '' }}</h3>
                    @if(!empty($item['desc']))<p>{{ $item['desc'] }}</p>@endif
                    @if(!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="card-link">Explore
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    @include('sections.final-cta')
</div>
@endsection

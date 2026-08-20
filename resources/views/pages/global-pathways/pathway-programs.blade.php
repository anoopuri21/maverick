@extends('layouts.app')

@section('title', ($page->seo['meta_title'] ?? 'Pathway Programs').' - Maverick Business Academy')
@section('meta_description', $page->seo['meta_description'] ?? 'Explore our flexible pathway programs designed to connect learners with globally recognised degrees.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-pathways.css') }}">
@endpush

@push('head')
    @include('partials.seo-meta', ['seo' => (object)($page->seo ?? [])])
@endpush

@section('content')
<div class="gpw">

    {{-- Editorial Hero --}}
    <section class="gpw-hero">
        <div class="glow"></div>
        <div class="container">
            @if(filled($page->eyebrow))
            <span class="gpw-eyebrow">{{ $page->eyebrow }}</span>
            @endif
            <h1>{{ $page->heading }}<em>{{ $page->heading_italic }}</em></h1>
            @if(filled($page->intro))
            <div class="lead">{!! $page->intro !!}</div>
            @endif
        </div>
    </section>

    {{-- Intro split --}}
    @if(filled($page->image_url) || filled($page->title))
    <section class="gpw-intro">
        <div class="container gpw-intro-grid">
            @if(filled($page->title))
            <div class="gpw-intro-copy">
                <h2 class="d">{{ $page->title }}</h2>
                <div class="body">{!! $page->intro ?? '' !!}</div>
            </div>
            @endif
            @if(filled($page->image_url))
            <div class="gpw-intro-media">
                <img src="{{ $page->image_url }}" alt="{{ $page->title }}" loading="lazy">
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Numbered pathway list --}}
    @if(count($page->items_list))
    <section class="gpw-list">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">Pathway Programs</div>
                <h2 class="d">Pathways to a <em>Global Degree</em></h2>
            </div>
            <div class="gpw-items">
                @foreach($page->items_list as $i => $item)
                <div class="gpw-item">
                    <span class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="info">
                        <h3 class="d">{{ $item['title'] ?? '' }}</h3>
                        @if(!empty($item['desc']))
                        <p>{{ $item['desc'] }}</p>
                        @endif
                    </div>
                    @if(!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="link">Explore
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('sections.final-cta')
</div>
@endsection

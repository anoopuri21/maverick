@extends('layouts.app')

@section('title', 'Global Pathway - Maverick Business Academy')
@section('meta_description', 'Explore global pathways and opportunities — flexible routes to internationally recognised degrees and real-world global experiences.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/global-pathways.css') }}">
@endpush

@section('content')
<div class="gpw">

    {{-- Hero --}}
    <section class="gpw-hero">
        <div class="glow"></div>
        <div class="container">
            <span class="gpw-eyebrow">Global Pathway</span>
            <h1>Pathways &amp; <em>Opportunities</em></h1>
            <p class="lead">Flexible routes to globally recognised degrees and real-world international experiences.</p>
        </div>
    </section>

    {{-- Tabbed sections --}}
    <div class="container gpw-tabs" style="padding-top:36px" x-data="{ tab: 'opportunities' }">
        <div class="gpw-tabbar" style="display:flex;gap:10px;flex-wrap:wrap;border-bottom:1px solid var(--gp-line);padding-bottom:16px;margin-bottom:32px">
            <button type="button" @click="tab='opportunities'"
                :class="tab==='opportunities' ? 'gpw-tab-active' : ''"
                class="gpw-tab">Global Opportunities</button>
            <button type="button" @click="tab='pathways'"
                :class="tab==='pathways' ? 'gpw-tab-active' : ''"
                class="gpw-tab">Global Pathways</button>
        </div>

        {{-- Section: Global Opportunities --}}
        <div x-show="tab==='opportunities'" x-cloak id="opportunities">
            @if($opportunities)
            <div class="gpw-list" style="padding:0 0 48px">
                <div class="sec-head">
                    <div class="kicker">Global Opportunities</div>
                    <h2 class="d">{{ $opportunities->heading }}<em>{{ $opportunities->heading_italic }}</em></h2>
                    @if(filled($opportunities->intro))
                    <div class="gpw-intro-copy" style="margin-top:12px;max-width:60ch">
                        <div class="body">{!! $opportunities->intro !!}</div>
                    </div>
                    @endif
                </div>
                <div class="gpw-items">
                    @foreach($opportunities->items_list as $i => $item)
                    <div class="gpw-item">
                        <span class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="info">
                            <h3 class="d">{{ $item['title'] ?? '' }}</h3>
                            @if(!empty($item['desc']))<p>{{ $item['desc'] }}</p>@endif
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
            @endif
        </div>

        {{-- Section: Global Pathways --}}
        <div x-show="tab==='pathways'" x-cloak id="pathways">
            @if($pathways)
            <div class="gpw-list" style="padding:0 0 48px">
                <div class="sec-head">
                    <div class="kicker">Global Pathways</div>
                    <h2 class="d">{{ $pathways->heading }}<em>{{ $pathways->heading_italic }}</em></h2>
                    @if(filled($pathways->intro))
                    <div class="gpw-intro-copy" style="margin-top:12px;max-width:60ch">
                        <div class="body">{!! $pathways->intro !!}</div>
                    </div>
                    @endif
                </div>
                <div class="gpw-items">
                    @foreach($pathways->items_list as $i => $item)
                    <div class="gpw-item">
                        <span class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="info">
                            <h3 class="d">{{ $item['title'] ?? '' }}</h3>
                            @if(!empty($item['desc']))<p>{{ $item['desc'] }}</p>@endif
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
            @endif
        </div>
    </div>

    @include('sections.final-cta')
</div>
@endsection

@push('styles')
<style>
[x-cloak]{display:none!important}
.gpw-tab{background:#fff;color:var(--gp-ink);border:1px solid var(--gp-line);border-radius:999px;padding:10px 22px;font-size:14px;font-weight:600;cursor:pointer;transition:all .25s ease}
.gpw-tab:hover{background:rgba(15,41,131,.05)}
.gpw-tab-active{background:var(--gp-blue);color:#fff;border-color:var(--gp-blue)}
</style>
@endpush

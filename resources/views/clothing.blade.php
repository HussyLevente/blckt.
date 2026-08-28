@extends('layout')

@section('title', __('Clothing | blckt. — Premium Streetwear, Hungarian-Made'))
@section('meta_description', __('Graphic-led oversized tees in 180 gsm combed ring-spun cotton, designed in-house in Budapest and printed in small batches. Unisex fit, built for the fifth wash.'))
@section('meta_image', 'assets/imgs/brand/blckt_coll_main.webp')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/shop.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@push('preload')
    <link rel="preload" as="image" href="{{ asset('assets/imgs/brand/blckt_coll_main.webp') }}" fetchpriority="high">
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [['label' => __('Clothing')]]])
    </div>

    <section class="shop-hero shell" aria-labelledby="shop-title">
        <div class="shop-hero-body">
            <span class="t8 ink-faint">{{ __('blckt. collection') }}</span>

            <h1 class="t2 optical-left" id="shop-title" style="margin-top: var(--space-6)">
                <span class="split-line" data-split="words">{{ __('Wearable,') }}</span>
                <span class="split-line" data-split="words">{{ __('not disposable.') }}</span>
            </h1>

            <p class="t5 shop-hero-lede" data-reveal style="--reveal-index: 2">{{ __('Heavyweight cotton, graphic-led, printed in small batches in Hungary. Every piece is designed in-house — no outsourced taste, no seasonal filler.') }}</p>

            <div class="shop-hero-actions" data-reveal style="--reveal-index: 3">
                <a href="{{ route('clothing.collection') }}" class="btn btn-solid" data-magnetic="0.25">{{ __('Shop the collection') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            </div>
        </div>

        <div class="shop-hero-figure" data-unveil>
            <img src="{{ asset('assets/imgs/brand/blckt_coll_main.webp') }}" alt="{{ __('The blckt. clothing collection') }}" {!! \App\Support\Media::sizeAttrs('assets/imgs/brand/blckt_coll_main.webp') !!} fetchpriority="high" decoding="async">
        </div>
    </section>

    {{-- Anyagjellemzok: rovid, tenyszeru valaszok, amiket a kereso is ki tud emelni. --}}
    <section class="section shell" aria-labelledby="spec-title">
        <h2 class="visually-hidden" id="spec-title">{{ __('Fabric and fit') }}</h2>

        <dl class="spec-row" data-reveal-group>
            <div class="spec">
                <dt>{{ __('Fabric') }}</dt>
                <dd>{{ __('100% combed ring-spun cotton') }}</dd>
            </div>
            <div class="spec">
                <dt>{{ __('Weight') }}</dt>
                <dd>{{ __('180 gsm heavyweight jersey') }}</dd>
            </div>
            <div class="spec">
                <dt>{{ __('Fit') }}</dt>
                <dd>{{ __('Unisex oversized') }}</dd>
            </div>
            <div class="spec">
                <dt>{{ __('Made in') }}</dt>
                <dd>{{ __('Hungary') }}</dd>
            </div>
        </dl>
    </section>

    <section class="statement" style="border-bottom: 0">
        <div class="shell">
            <div class="statement-inner">
                <h2 class="t2 optical-left">
                    <span class="split-line" data-split="words">{{ __('Designed for the') }}</span>
                    <span class="split-line" data-split="words">{{ __('fifth wash, not') }}</span>
                    <span class="split-line" data-split="words">{{ __('the first photo.') }}</span>
                </h2>
                <span class="t8 statement-mark" data-reveal data-drift style="--reveal-index: 3">blckt.&trade;</span>
            </div>
        </div>
    </section>

    <section class="section shell">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('The collection') }}</span>
                <h2 class="t2 section-head-title">{{ __('Five pieces, no filler.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Each design runs once. When a batch sells out it does not come back.') }}</p>
        </header>

        <div class="actions" data-reveal>
            <a href="{{ route('clothing.collection') }}" class="btn">{{ __('Browse everything') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
        </div>
    </section>
@endsection

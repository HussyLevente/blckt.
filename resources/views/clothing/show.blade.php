@extends('layout')

@section('title', $product['name'].' — '.$product['subtitle'].' | blckt. Clothing')
@section('meta_description', __(':name — :subtitle. :price. Premium oversized tee in 180 gsm combed ring-spun cotton, designed in-house and made in Hungary.', ['name' => $product['name'], 'subtitle' => $product['subtitle'], 'price' => $product['price']]))
@section('meta_image', $product['thumbnail'])
@section('og_type', 'product')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/shop.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@push('schema')
    {{-- Termek-adat. A keszlet szandekosan PreOrder: a darabok meg nem
         vasarolhatok meg, es a keresonek sem szabad ugy jeloljuk, mintha
         azok lennenek. --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        '@id' => route('clothing.show', $product['slug']).'#product',
        'name' => $product['name'].' — '.$product['subtitle'],
        'sku' => 'BLCKT-'.mb_strtoupper($product['slug']),
        'image' => array_map(fn ($i) => asset($i), $product['images']),
        'description' => __('Premium unisex oversized tee in 180 gsm combed ring-spun cotton. Designed in-house in Budapest and printed in small batches in Hungary.'),
        'color' => $product['color'],
        'material' => __('100% combed ring-spun cotton'),
        'brand' => ['@type' => 'Brand', 'name' => 'blckt.'],
        'manufacturer' => ['@id' => url('/').'#studio'],
        'countryOfOrigin' => ['@type' => 'Country', 'name' => 'Hungary'],
        'size' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        'offers' => [
            '@type' => 'Offer',
            'url' => route('clothing.show', $product['slug']),
            'price' => preg_replace('/[^0-9.]/', '', $product['price']),
            'priceCurrency' => 'EUR',
            'availability' => 'https://schema.org/PreOrder',
            'seller' => ['@id' => url('/').'#studio'],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [
            ['label' => __('Clothing'), 'url' => url('/clothing')],
            ['label' => __('The collection'), 'url' => route('clothing.collection')],
            ['label' => $product['name']],
        ]])
    </div>

    <div class="shell" style="padding-bottom: var(--space-24)">
        <div class="product-layout">

            <div class="product-gallery" data-reveal-group="tight">
                @foreach ($product['images'] as $i => $image)
                    <button type="button" class="product-gallery-item" aria-label="{{ __('Open image :n full size', ['n' => $i + 1]) }}">
                        <img
                            src="{{ asset($image) }}"
                            alt="{{ $product['name'] }} — {{ __('view :n', ['n' => $i + 1]) }}"
                            {!! \App\Support\Media::sizeAttrs($image) !!}
                            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                            @if ($i === 0) fetchpriority="high" @endif
                            decoding="async"
                        >
                    </button>
                @endforeach
            </div>

            <div class="product-panel">
                <span class="t8 ink-faint">{{ __('blckt. collection') }}</span>

                <h1 class="t3" style="margin-top: var(--space-4)">{{ $product['name'] }}</h1>
                <p class="t5 ink-muted" style="margin-top: var(--space-3)">{{ $product['subtitle'] }}</p>

                <p class="product-price">{{ $product['price'] }}</p>

                <div class="product-block">
                    <span class="product-block-title">{{ __('Size') }}</span>
                    <div class="sizes">
                        @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            <button type="button" class="size" aria-pressed="false">{{ $size }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="product-block">
                    <span class="product-block-title">{{ __('Colour') }}</span>
                    <div class="colour">
                        <span class="colour-chip">
                            <img src="{{ asset($product['thumbnail']) }}" alt="" {!! \App\Support\Media::sizeAttrs($product['thumbnail']) !!} loading="lazy" decoding="async">
                        </span>
                        <span class="t7">{{ $product['color'] }}</span>
                    </div>
                </div>

                <button type="button" class="btn btn-solid product-buy" disabled>{{ __('Coming soon') }}</button>
                <p class="t8 product-note">{{ __('Not on sale yet — the first batch is in production.') }}</p>

                {{-- Rovid, tenyszeru valaszok: ezeket a keresok es a
                     valaszmotorok is kozvetlenul ki tudjak emelni. --}}
                <div class="accordion" style="margin-top: var(--space-12)">
                    <div class="accordion-item">
                        <button type="button" class="accordion-trigger">
                            {{ __('Materials') }} <span class="accordion-icon" aria-hidden="true"></span>
                        </button>
                        <div class="accordion-panel"><div>
                            <p>{{ __('100% combed ring-spun cotton, 180 gsm heavyweight jersey. Printed in small batches in Hungary.') }}</p>
                        </div></div>
                    </div>
                    <div class="accordion-item">
                        <button type="button" class="accordion-trigger">
                            {{ __('Washing') }} <span class="accordion-icon" aria-hidden="true"></span>
                        </button>
                        <div class="accordion-panel"><div>
                            <p>{{ __('Machine wash cold, inside out. Do not bleach. Tumble dry low.') }}</p>
                        </div></div>
                    </div>
                    <div class="accordion-item">
                        <button type="button" class="accordion-trigger">
                            {{ __('Fit') }} <span class="accordion-icon" aria-hidden="true"></span>
                        </button>
                        <div class="accordion-panel"><div>
                            <p>{{ __('Unisex oversized fit. True to size — size up for an even roomier fit.') }}</p>
                        </div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ \App\Support\Asset::url('assets/js/accordion.js') }}" defer></script>
    <script src="{{ \App\Support\Asset::url('assets/js/clothing-products.js') }}" defer></script>
@endpush

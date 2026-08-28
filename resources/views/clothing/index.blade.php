@extends('layout')

@section('title', __('Shop the Collection | blckt. Clothing'))
@section('meta_description', __('Every blckt. piece in one place — premium oversized tees in 180 gsm cotton, graphic-led and made in Hungary.'))
@section('meta_image', 'assets/imgs/brand/blckt_coll_main.webp')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/shop.css') }}">
@endpush

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => __('blckt. collection'),
        'url' => route('clothing.collection'),
        'isPartOf' => ['@id' => url('/').'#website'],
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => count($products),
            'itemListElement' => array_map(fn ($i, $p) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => route('clothing.show', $p['slug']),
                'name' => $p['name'],
            ], array_keys($products), $products),
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [
            ['label' => __('Clothing'), 'url' => url('/clothing')],
            ['label' => __('The collection')],
        ]])
    </div>

    <section class="page-head shell" aria-labelledby="collection-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('blckt. collection') }}</span>
        <h1 class="t1 page-head-title optical-left" id="collection-title">
            <span class="split-line" data-split="words">{{ __('Everything') }}</span>
            <span class="split-line" data-split="words">{{ __('in one place.') }}</span>
        </h1>
    </section>

    <section class="section-tight shell">
        <div class="shop-tools">
            <p class="t8 ink-faint">{{ __(':n pieces', ['n' => count($products)]) }}</p>

            <div class="shop-search">
                <label for="product-search" class="visually-hidden">{{ __('Search the collection') }}</label>
                <input type="search" id="product-search" placeholder="{{ __('Search…') }}" autocomplete="off">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
        </div>

        <div class="product-grid" data-reveal-group="tight">
            @foreach ($products as $product)
                <a href="{{ route('clothing.show', $product['slug']) }}" class="product-card" data-name="{{ mb_strtolower($product['name']) }}">
                    <div class="product-card-figure">
                        @if ($product['is_new'])
                            <span class="product-badge">{{ __('New') }}</span>
                        @endif
                        <img src="{{ asset($product['thumbnail']) }}" alt="{{ $product['name'] }} — {{ $product['subtitle'] }}" {!! \App\Support\Media::sizeAttrs($product['thumbnail']) !!} loading="lazy" decoding="async">
                    </div>
                    <div class="product-card-body">
                        <span class="t7 product-card-name">{{ $product['name'] }}</span>
                        <span class="t8 product-card-price">{{ $product['price'] }}</span>
                    </div>
                </a>
            @endforeach

            @for ($i = 0; $i < $placeholderCount; $i++)
                <div class="product-card product-card-placeholder" aria-hidden="true">
                    <div class="product-card-figure">
                        <span>{{ __('Coming soon') }}</span>
                    </div>
                </div>
            @endfor
        </div>

        <p class="t5 products-empty" id="products-empty" hidden>{{ __('No products match your search.') }}</p>
    </section>
@endsection

@push('scripts')
    <script src="{{ \App\Support\Asset::url('assets/js/clothing-products.js') }}" defer></script>
@endpush

@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/website-project.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/clothing-products.css') }}">
@endpush

@section('content')
    <section class="content-section product-detail-section reveal">
        <a href="{{ route('clothing.collection') }}" class="project-back-btn">&larr; {{ __('blckt. collection') }}</a>

        <div class="product-detail-header">
            <h1 class="product-detail-title">{{ $product['name'] }} &mdash; {{ $product['subtitle'] }}</h1>
            <span class="product-detail-collection">{{ __('blckt. collection') }}</span>
        </div>

        <div class="project-gallery-grid product-detail-gallery">
            @foreach ($product['images'] as $image)
                <button type="button" class="project-gallery-item">
                    <img src="{{ asset($image) }}" alt="{{ $product['name'] }}" loading="lazy">
                    <span class="project-gallery-expand">&#10530;</span>
                </button>
            @endforeach
        </div>

        <div class="product-info-card">
            <h2 class="product-detail-title">{{ $product['name'] }} &mdash; {{ $product['subtitle'] }}</h2>
            <span class="product-detail-collection">{{ __('blckt. collection') }}</span>

            <p class="product-price-line">{{ __('Full price:') }} <strong>{{ $product['price'] }}</strong></p>

            <div class="product-section">
                <h3 class="product-section-title">{{ __('Available sizes') }}</h3>
                <div class="size-swatches">
                    @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                        <button type="button" class="size-swatch">{{ $size }}</button>
                    @endforeach
                </div>
                <a href="#" class="size-guide-link">{{ __('Size guide') }}</a>
            </div>

            <div class="product-section">
                <h3 class="product-section-title">{{ __('Available colors') }}</h3>
                <div class="color-swatches">
                    <div class="color-swatch-item">
                        <img src="{{ asset($product['thumbnail']) }}" alt="{{ $product['color'] }}" class="color-swatch is-selected">
                        <span class="color-swatch-label">{{ __('Selected') }}: {{ $product['color'] }}</span>
                    </div>
                </div>
            </div>

            <div class="product-accordion">
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Materials') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('100% combed ring-spun cotton, 180 gsm heavyweight jersey.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Washing method') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('Machine wash cold, inside out. Do not bleach. Tumble dry low.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('More details') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('Unisex oversized fit. Printed in small batches. True to size — size up for an even roomier fit.') }}</p>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-pill buy-btn" disabled>{{ __('Coming soon') }}</button>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
    <script src="{{ asset('assets/js/clothing-products.js') }}"></script>
@endpush

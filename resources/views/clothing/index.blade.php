@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/clothing-products.css') }}">
@endpush

@section('content')
    <section class="content-section products-header-section reveal">
        <h1 class="products-title">{{ __('blckt. collection') }}</h1>

        <div class="products-search">
            <input type="text" id="product-search" placeholder="{{ __('Search...') }}" autocomplete="off">
            <svg class="products-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>

        <div class="products-grid">
            @foreach ($products as $product)
                <a href="{{ route('clothing.show', $product['slug']) }}" class="product-card" data-name="{{ strtolower($product['name']) }}">
                    @if ($product['is_new'])
                        <span class="product-badge">{{ __('NEW') }}</span>
                    @endif
                    <div class="product-card-image">
                        <img src="{{ asset($product['thumbnail']) }}" alt="{{ $product['name'] }}" loading="lazy">
                    </div>
                    <span class="product-card-price">{{ $product['price'] }}</span>
                </a>
            @endforeach

            @for ($i = 0; $i < $placeholderCount; $i++)
                <div class="product-card product-card-placeholder">
                    <span>{{ __('Coming soon') }}</span>
                </div>
            @endfor
        </div>

        <p class="products-empty" id="products-empty" hidden>{{ __('No products match your search.') }}</p>

        <div class="center-button">
            <button type="button" class="btn-pill">{{ __('more') }}</button>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
    <script src="{{ asset('assets/js/clothing-products.js') }}"></script>
@endpush

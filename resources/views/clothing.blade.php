@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/clothing.css') }}">
@endpush

@section('content')
    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('You have so much to choose from.') }}</h2>
                <span class="manifesto-brand">{{ __('But hey, no rush.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section collection-section reveal">
        <div class="collection-grid">
            <div class="collection-left">
                <h2 class="collection-title">{{ __('blckt. collection') }}</h2>
                <p class="collection-text">{{ __('Standard issue is dead. blckt. engineers premium apparel designed to outlast the hype. No compromises. No cutting corners. Just top-tier quality that speaks for itself. Drop the dead weight and elevate your uniform.') }}</p>
                <a href="{{ route('clothing.collection') }}" class="btn-pill">{{ __('check out blckt.') }}</a>
            </div>
            <div class="collection-right">
                <img src="{{ asset('assets/imgs/brand/blckt_coll_main.png') }}" alt="blckt. collection" class="collection-image">
            </div>
        </div>

        <div class="slider-container">
            <div class="slider-wrapper">
                <button class="slider-btn prev-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Prev"></button>
                <div class="slider-viewport">
                    <div class="slider-items">
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_hollyweed.png') }}" alt="Hollyweed"></div></div>
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_ratio.png') }}" alt="Ratio"></div></div>
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_agapiti.png') }}" alt="Agapiti"></div></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                    </div>
                </div>
                <button class="slider-btn next-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Next"></button>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/slider.js') }}"></script>
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
@endpush

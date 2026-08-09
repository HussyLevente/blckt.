@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endpush

@section('content')
    <div class="hero-pin">
        <section class="hero-section">
            <div class="hero-content">
                <span class="hero-text">blckt.</span>
                <img src="{{ asset('assets/imgs/brand/blckt_mainpage_hero_image.png') }}" alt="blckt hero" class="hero-image">
            </div>
        </section>
    </div>

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('We build your future so you don’t have to.') }}</h2>
                <span class="manifesto-brand">blckt.&trade;</span>
            </div>
        </section>
    </div>

    <section class="content-section websites-section reveal">
        <div class="text-and-logos">
            <div class="text-content">
                <p>{{ __('We are not only designing your websites but actually make them with precision and functionality.') }}</p>
                <p style="margin-top:150px">{{ __('Here is the most recent one we did.') }}</p>
            </div>
            <div class="floating-logos">
                <img src="{{ asset('assets/imgs/tools/figma_logo.png') }}" alt="Figma" class="logo-figma">
                <img src="{{ asset('assets/imgs/tools/vs_logo.png') }}" alt="VS Code" class="logo-vscode">
                <img src="{{ asset('assets/imgs/tools/js_logo.png') }}" alt="JavaScript" class="logo-js">
            </div>
        </div>

        <div class="slider-container">
            <h3 class="slider-title">Paradise <img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="" class="arrow-icon"></h3>
            <div class="slider-wrapper">
                <button class="slider-btn prev-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Prev"></button>
                <div class="slider-viewport">
                    <div class="slider-items">
                        <div class="slider-item"><img src="{{ asset('assets/imgs/websites/paradise/paradise_promo_1.jpg') }}" alt="Paradise 1"></div>
                        <div class="slider-item"><img src="{{ asset('assets/imgs/websites/paradise/paradise_promo_2.jpg') }}" alt="Paradise 2"></div>
                        <div class="slider-item"><img src="{{ asset('assets/imgs/websites/paradise/paradise_promo_3.jpg') }}" alt="Paradise 3"></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                    </div>
                </div>
                <button class="slider-btn next-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Next"></button>
            </div>
            <div class="center-button">
                <a href="/websites" class="btn-pill">{{ __('all websites') }}</a>
            </div>
        </div>
    </section>

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('Do you like aesthetic colors and patterns?') }}</h2>
                <span class="manifesto-brand">{{ __('Of course you do.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section clothing-section reveal">
        <div class="text-and-logos">
            <div class="text-content">
                <p class="mt-4">{{ __('That\'s why we make high quality clothes that function for long time. No matter your everywhere clothing.') }}</p>
                <p class="mt-4">{{ __('Here are some of the recommend. Click "all clothing" to see more.') }}</p>
            </div>
            <div class="floating-clothing">
                <img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_hollyweed.png') }}" alt="Hollyweed" class="clothing-1">
                <img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_ratio.png') }}" alt="Ratio" class="clothing-2">
                <img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_agapiti.png') }}" alt="Agapiti" class="clothing-3">
            </div>
        </div>

        <div class="slider-container">
            <h3 class="slider-title">{{ __('blckt. collection') }} <img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="" class="arrow-icon"></h3>
            <div class="slider-wrapper">
                <button class="slider-btn prev-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Prev"></button>
                <div class="slider-viewport">
                    <div class="slider-items">
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo3d_agapiti1.png') }}" alt="Agapiti 1"></div></div>
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo3d_agapiti2.png') }}" alt="Agapiti 2"></div></div>
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo3d_agapiti3.png') }}" alt="Agapiti 3"></div></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                    </div>
                </div>
                <button class="slider-btn next-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Next"></button>
            </div>
            <div class="center-button">
                <a href="/clothing" class="btn-pill">{{ __('all clothing') }}</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/slider.js') }}"></script>
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
@endpush

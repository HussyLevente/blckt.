@extends('layout')

@section('title', __('blckt. | Custom Websites & Clothing Design, Budapest'))
@section('meta_description', __('I design and build custom websites and premium streetwear from Budapest. No templates, real code, one person start to finish.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endpush

@push('preload')
    <link rel="preload" as="image" href="{{ asset('assets/imgs/brand/blckt_mainpage_hero_image.webp') }}" fetchpriority="high">
@endpush

@section('content')
    <div class="hero-pin">
        <section class="hero-section">
            <div class="hero-content">
                <span class="hero-text">blckt.</span>
                <img src="{{ asset('assets/imgs/brand/blckt_mainpage_hero_image.webp') }}" alt="blckt hero" class="hero-image" fetchpriority="high">
            </div>
        </section>
    </div>

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('I build your future so you don’t have to.') }}</h2>
                <span class="manifesto-brand">blckt.&trade;</span>
            </div>
        </section>
    </div>

    <section class="content-section websites-section reveal">
        <div class="text-and-logos">
            <div class="text-content">
                <p>{{ __('I am not only designing your websites but actually make them with precision and functionality.') }}</p>
                <p style="margin-top:150px">{{ __('Here is the most recent one I did.') }}</p>
            </div>
            <div class="floating-logos">
                <img src="{{ asset('assets/imgs/tools/figma_logo.png') }}" alt="Figma" class="logo-figma" loading="lazy" decoding="async">
                <img src="{{ asset('assets/imgs/tools/vs_logo.png') }}" alt="VS Code" class="logo-vscode" loading="lazy" decoding="async">
                <img src="{{ asset('assets/imgs/tools/js_logo.png') }}" alt="JavaScript" class="logo-js" loading="lazy" decoding="async">
            </div>
        </div>

        <div class="slider-container">
            <h3 class="slider-title">Paradise <img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="" class="arrow-icon" loading="lazy" decoding="async"></h3>
            <div class="slider-wrapper">
                <button class="slider-btn prev-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Prev" loading="lazy" decoding="async"></button>
                <div class="slider-viewport">
                    <div class="slider-items">
                        <div class="slider-item"><img src="{{ asset('assets/imgs/websites/paradise/paradise_promo_1.webp') }}" alt="Paradise 1" loading="lazy" decoding="async"></div>
                        <div class="slider-item"><img src="{{ asset('assets/imgs/websites/paradise/paradise_promo_2.webp') }}" alt="Paradise 2" loading="lazy" decoding="async"></div>
                        <div class="slider-item"><img src="{{ asset('assets/imgs/websites/paradise/paradise_promo_3.webp') }}" alt="Paradise 3" loading="lazy" decoding="async"></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                    </div>
                </div>
                <button class="slider-btn next-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Next" loading="lazy" decoding="async"></button>
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
                <p class="mt-4">{{ __('That\'s why I make high quality clothes that function for long time. No matter your everywhere clothing.') }}</p>
                <p class="mt-4">{{ __('Here are some of the recommend. Click "all clothing" to see more.') }}</p>
            </div>
            <div class="floating-clothing">
                <img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_hollyweed.webp') }}" alt="Hollyweed" class="clothing-1" loading="lazy" decoding="async">
                <img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_ratio.webp') }}" alt="Ratio" class="clothing-2" loading="lazy" decoding="async">
                <img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo_agapiti.webp') }}" alt="Agapiti" class="clothing-3" loading="lazy" decoding="async">
            </div>
        </div>

        <div class="slider-container">
            <h3 class="slider-title">{{ __('blckt. collection') }} <img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="" class="arrow-icon" loading="lazy" decoding="async"></h3>
            <div class="slider-wrapper">
                <button class="slider-btn prev-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Prev" loading="lazy" decoding="async"></button>
                <div class="slider-viewport">
                    <div class="slider-items">
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo3d_agapiti1.webp') }}" alt="Agapiti 1" loading="lazy" decoding="async"></div></div>
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo3d_agapiti2.webp') }}" alt="Agapiti 2" loading="lazy" decoding="async"></div></div>
                        <div class="slider-item"><div class="slider-image"><img src="{{ asset('assets/imgs/clothing/collection/blckt_coll_promo3d_agapiti3.webp') }}" alt="Agapiti 3" loading="lazy" decoding="async"></div></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                        <div class="slider-item placeholder"><span>{{ __('Coming soon') }}</span></div>
                    </div>
                </div>
                <button class="slider-btn next-btn"><img src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="Next" loading="lazy" decoding="async"></button>
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

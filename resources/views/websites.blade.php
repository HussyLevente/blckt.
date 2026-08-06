@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
@endpush

@section('content')
    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('All our websites listed below for you to check out.') }}</h2>
                <span class="manifesto-brand">{{ __('Take a closer look.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section site-section reveal">
        <div class="site-grid">
            <div class="site-left">
                <img src="{{ asset('assets/imgs/paradise_logo.png') }}" alt="Paradise" class="site-logo">
                <p class="site-text">{{ __('Paradise is your ultimate portal to the planet’s most breathtaking destinations. Whether you want to scale ancient ruins, get lost in neon-lit cities, or set sail on a massive Royal Caribbean cruise ship, your next adventure starts right here. Stop dreaming and start packing.') }}</p>
                <a href="{{ route('websites.show', 'paradise') }}" class="btn-pill">{{ __('check out') }}</a>
            </div>
            <div class="site-right">
                <img src="{{ asset('assets/imgs/paradise_minis1.png') }}" alt="Paradise preview 1" class="site-mini site-mini-1">
                <img src="{{ asset('assets/imgs/paradise_minis2.png') }}" alt="Paradise preview 2" class="site-mini site-mini-2">
                <img src="{{ asset('assets/imgs/paradise_minis3.png') }}" alt="Paradise preview 3" class="site-mini site-mini-3">
            </div>
        </div>
    </section>

    <section class="content-section site-section reveal">
        <div class="site-grid">
            <div class="site-left">
                <img src="{{ asset('assets/imgs/palesso_logo.png') }}" alt="Palesso" class="site-logo">
                <p class="site-text">{{ __('Palesso is your destination for premium fashion and curated lifestyle pieces. From cutting-edge men’s and women’s apparel to statement bags and exclusive home decor, we have everything you need to elevate your look. Featuring seamless in-store collection and custom personalization, looking flawless has never been this efficient. Suit up.') }}</p>
                <a href="{{ route('websites.show', 'palesso') }}" class="btn-pill">{{ __('check out') }}</a>
            </div>
            <div class="site-right">
                <img src="{{ asset('assets/imgs/palesso_minis1.png') }}" alt="Palesso preview 1" class="site-mini site-mini-1">
                <img src="{{ asset('assets/imgs/palesso_minis2.png') }}" alt="Palesso preview 2" class="site-mini site-mini-2">
                <img src="{{ asset('assets/imgs/palesso_minis3.png') }}" alt="Palesso preview 3" class="site-mini site-mini-3">
            </div>
        </div>
    </section>

    <section class="content-section contact-cta-section reveal">
        <div class="contact-grid">
            <div class="contact-left">
                <h2 class="contact-title">{{ __('Ready to own something remarkable?') }}</h2>
                <p class="contact-text">{{ __('Tell us about your industry, budget, and timeline. We’ll match you with the right site — or scope something new from the ground up.') }}</p>
                <p class="contact-email">{{ __('Email: blckt.websites@gmail.com') }}</p>
                <p class="contact-response">{{ __('Response time: 24 Hours') }}</p>
            </div>
            <div class="contact-right">
                @include('partials.contact-form')
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
@endpush

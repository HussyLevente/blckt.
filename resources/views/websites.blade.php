@extends('layout')

@section('title', __('Websites | blckt. — Custom Web Design & Development'))
@section('meta_description', __('Custom-built websites for small businesses — landing pages, multi-page sites, and webshops. Real code, fast load times, one developer from first call to launch.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
@endpush

@section('content')
    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('All my websites listed below for you to check out.') }}</h2>
                <span class="manifesto-brand">{{ __('Take a closer look.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section site-section reveal">
        <div class="site-grid">
            <div class="site-left">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/paradise/paradise_logo.png') }}" alt="Paradise" class="site-logo">
                <p class="site-text">{{ __('Paradise is your ultimate portal to the planet’s most breathtaking destinations. Whether you want to scale ancient ruins, get lost in neon-lit cities, or set sail on a massive Royal Caribbean cruise ship, your next adventure starts right here. Stop dreaming and start packing.') }}</p>
                <a href="{{ route('websites.show', 'paradise') }}" class="btn-pill">{{ __('check out') }}</a>
            </div>
            <div class="site-right">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/paradise/paradise_minis1.jpg') }}" alt="Paradise preview 1" class="site-mini site-mini-1">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/paradise/paradise_minis2.jpg') }}" alt="Paradise preview 2" class="site-mini site-mini-2">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/paradise/paradise_minis3.jpg') }}" alt="Paradise preview 3" class="site-mini site-mini-3">
            </div>
        </div>
    </section>

    <section class="content-section site-section reveal">
        <div class="site-grid">
            <div class="site-left">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/palesso/palesso_logo.png') }}" alt="Palesso" class="site-logo">
                <p class="site-text">{{ __('Palesso is your destination for premium fashion and curated lifestyle pieces. From cutting-edge men’s and women’s apparel to statement bags and exclusive home decor, we have everything you need to elevate your look. Featuring seamless in-store collection and custom personalization, looking flawless has never been this efficient. Suit up.') }}</p>
                <a href="{{ route('websites.show', 'palesso') }}" class="btn-pill">{{ __('check out') }}</a>
            </div>
            <div class="site-right">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/palesso/palesso_minis1.jpg') }}" alt="Palesso preview 1" class="site-mini site-mini-1">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/palesso/palesso_minis2.jpg') }}" alt="Palesso preview 2" class="site-mini site-mini-2">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/palesso/palesso_minis3.jpg') }}" alt="Palesso preview 3" class="site-mini site-mini-3">
            </div>
        </div>
    </section>

    <section class="content-section site-section reveal">
        <div class="site-grid">
            <div class="site-left">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/kepszakadas/kepszakadas_logo.png') }}" alt="Képszakadás" class="site-logo">
                <p class="site-text">{{ __('Képszakadás Drinking Game is your ultimate arsenal for escalating the night with your crew. Featuring a sleek, modern design, this expanded collection of drinking card games and minigames delivers everything your squad needs to keep the party moving. Optimized for a flawless mobile experience directly from your browser—zero downloads required, instant deployment. Fill your glasses. Let’s get to work.') }}</p>
                <a href="{{ route('websites.show', 'kepszakadas') }}" class="btn-pill">{{ __('check out') }}</a>
            </div>
            <div class="site-right">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/kepszakadas/kepszakadas_minis1.jpg') }}" alt="Képszakadás preview 1" class="site-mini site-mini-1">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/kepszakadas/kepszakadas_minis2.jpg') }}" alt="Képszakadás preview 2" class="site-mini site-mini-2">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/kepszakadas/kepszakadas_minis3.jpg') }}" alt="Képszakadás preview 3" class="site-mini site-mini-3">
            </div>
        </div>
    </section>

    <section class="content-section site-section reveal">
        <div class="site-grid">
            <div class="site-left">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/juiced/juiced_logo.png') }}" alt="Juiced" class="site-logo site-logo-invert">
                <p class="site-text">{{ __('Juiced is where taste meets productivity — a flavour-first storefront for bold, ready-to-drink refreshers and flavour sticks. Every drink gets its own full-bleed, colour-shifting moment instead of getting buried in a spec-sheet grid. Browse by flavour, mix plain water into something worth drinking, and find your next favourite in seconds.') }}</p>
                <a href="{{ route('websites.show', 'juiced') }}" class="btn-pill">{{ __('check out') }}</a>
            </div>
            <div class="site-right">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/juiced/juiced_minis1.jpg') }}" alt="Juiced preview 1" class="site-mini site-mini-1">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/juiced/juiced_minis2.jpg') }}" alt="Juiced preview 2" class="site-mini site-mini-2">
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/juiced/juiced_minis3.jpg') }}" alt="Juiced preview 3" class="site-mini site-mini-3">
            </div>
        </div>
    </section>

    <section class="content-section contact-cta-section reveal">
        <div class="contact-grid">
            <div class="contact-left">
                <h2 class="contact-title">{{ __('Ready to own something remarkable?') }}</h2>
                <p class="contact-text">{{ __('Tell me about your industry, budget, and timeline. I’ll match you with the right site — or scope something new from the ground up.') }}</p>
                <a href="mailto:hello@blckt.hu" class="contact-email">{{ __('Email: hello@blckt.hu') }}</a>
                <a href="https://wa.me/36302552432" target="_blank" rel="noopener" class="contact-email contact-whatsapp">{{ __('Message on WhatsApp') }}</a>
                <p class="contact-response">{{ __('Response time: 24 Hours') }}</p>
            </div>
            <div class="contact-right">
                @include('partials.contact-form')
            </div>
        </div>
    </section>

    <div class="sticky-cta-bar">
        <a href="/contact" class="btn-pill">{{ __('Get a quote') }} <span aria-hidden="true">&#8594;</span></a>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
@endpush

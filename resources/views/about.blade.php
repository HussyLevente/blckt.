@extends('layout')

@section('title', __('About | blckt. — Levente Hussy, Budapest'))
@section('meta_description', __('I’m Levente Hussy, a 21-year-old solo web designer and developer based in Budapest, studying Economic Informatics at BGE.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
@endpush

@section('content')
    <section class="content-section about-hero-section reveal">
        <span class="section-eyebrow">{{ __('About') }}</span>
        <h1 class="about-hero-title" data-anim="up">{{ __('I am blckt.') }}</h1>
        <p class="about-hero-subtitle" data-anim="up" data-anim-delay="150">{{ __('A solo studio from Hungary building clothes and websites that don’t apologize for existing.') }}</p>
    </section>

    <section class="content-section about-origin-section reveal">
        <span class="section-eyebrow">{{ __('Origin') }}</span>
        <div class="origin-grid">
            <h2 class="origin-title" data-anim="left">{{ __('Started in a bedroom. Still feels that way.') }}</h2>
            <div class="origin-text" data-anim="right" data-anim-delay="120">
                <p>{{ __('blckt. launched in 2026 as an experiment — what happens if you apply the same obsessive attention to detail to a t-shirt that you’d apply to a software product?') }}</p>
                <p>{{ __('The answer turned out to be: something people actually want. So I kept going. The clothing led to the websites. The websites led back to better clothing. Now it’s both, permanently, by design.') }}</p>
                <p>{{ __('The name blckt. is intentionally stripped. No vowels, no fuss. A mark, not a word.') }}</p>
            </div>
        </div>
    </section>

    <section class="content-section about-stats-section reveal">
        <div class="stats-grid stats-grid-divided" data-anim-stagger="140">
            <div class="stat-item">
                <span class="stat-number" data-target="2026" data-suffix="">0</span>
                <span class="stat-label">{{ __('founded') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="12" data-suffix="+">0</span>
                <span class="stat-label">{{ __('websites designed') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="8" data-suffix="">0</span>
                <span class="stat-label">{{ __('clothing designs') }}</span>
            </div>
        </div>
    </section>

    <section class="content-section about-work-section reveal">
        <span class="section-eyebrow">{{ __('What I do') }}</span>
        <div class="work-grid" data-anim-stagger="160">
            <a href="/clothing" class="work-card">
                <span class="work-card-index">01 — {{ __('Clothing') }}</span>
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/brand/blckt_coll_main.png') }}" alt="blckt. clothing">
                <div class="work-card-body">
                    <h3>{{ __('Apparel that actually says something.') }}</h3>
                    <p>{{ __('blckt. clothing started as a personal project and became a micro-collection of premium oversized tees. Graphic-led, culture-literate, Hungarian-made. Every piece is designed in-house — no outsourced taste.') }}</p>
                    <span class="btn-pill work-card-btn">{{ __('shop collection') }}</span>
                </div>
            </a>
            <a href="/websites" class="work-card">
                <span class="work-card-index">02 — {{ __('Websites') }}</span>
                <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/websites/juiced/juiced_whole1.jpg') }}" alt="blckt. websites">
                <div class="work-card-body">
                    <h3>{{ __('Websites people actually want to visit.') }}</h3>
                    <p>{{ __('I design and build custom websites for brands that care about the details. No templates, no bloat. I use Figma to design, write real code to build, and hand over something you’re proud to share.') }}</p>
                    <span class="btn-pill work-card-btn">{{ __('see my work') }}</span>
                </div>
            </a>
        </div>
    </section>

    <section class="content-section about-values-section reveal">
        <span class="section-eyebrow">{{ __('Values') }}</span>
        <div class="values-grid">
            <h2 class="values-title" data-anim="left">{{ __('Three things I won’t compromise on.') }}</h2>
            <div class="values-accordion" data-anim="up" data-anim-delay="120">
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('No filler.') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('If it doesn’t earn its place on the page or on the shirt, it doesn’t ship. No stock photography, no lorem ipsum, no decoration for decoration’s sake.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Built to last.') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('Heavyweight cotton. Real code, not page-builder duct tape. I design for the fifth wash and the fifth deploy, not just the first screenshot.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Small on purpose.') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('I turn down more work than I take. Every project gets my full attention because there is never five other projects competing for it.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section about-team-section reveal">
        <span class="section-eyebrow">{{ __('The founder') }}</span>
        <div class="team-grid">
            <div class="team-member">
                <div class="team-avatar team-avatar-logos">
                    <div class="floating-logos team-floating-logos" data-parallax="0.14">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/tools/figma_logo.png') }}" alt="Figma" class="logo-figma">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/tools/vs_logo.png') }}" alt="VS Code" class="logo-vscode">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/tools/js_logo.png') }}" alt="JavaScript" class="logo-js">
                    </div>
                </div>
                <span class="team-role">{{ __('Founder') }}</span>
                <h3 class="team-title">Levente Hussy</h3>
                <p>{{ __('21 years old, studying Economic Informatics at Budapest Business School (BGE). Design, code, clothing, and websites — all one person, on purpose.') }}</p>
            </div>
        </div>
    </section>

    <section class="content-section about-cta-section reveal">
        <h2 class="about-cta-title">{{ __('Let’s make something remarkable.') }}</h2>
    </section>

    <section class="content-section about-contact-section reveal">
        <div class="contact-right about-contact-card">
            @include('partials.contact-form')
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
    <script src="{{ asset('assets/js/about.js') }}"></script>
@endpush

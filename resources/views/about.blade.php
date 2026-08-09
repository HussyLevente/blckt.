@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
@endpush

@section('content')
    <section class="content-section about-hero-section reveal">
        <span class="section-eyebrow">{{ __('About') }}</span>
        <h1 class="about-hero-title">{{ __('We are blckt.') }}</h1>
        <p class="about-hero-subtitle">{{ __('A two-headed studio from Hungary building clothes and websites that don’t apologize for existing.') }}</p>
    </section>

    <section class="content-section about-origin-section reveal">
        <span class="section-eyebrow">{{ __('Origin') }}</span>
        <div class="origin-grid">
            <h2 class="origin-title">{{ __('Started in a bedroom. Still feels that way.') }}</h2>
            <div class="origin-text">
                <p>{{ __('blckt. launched in 2024 as an experiment — what happens if you apply the same obsessive attention to detail to a t-shirt that you’d apply to a software product?') }}</p>
                <p>{{ __('The answer turned out to be: something people actually want. So we kept going. The clothing led to the websites. The websites led back to better clothing. Now it’s both, permanently, by design.') }}</p>
                <p>{{ __('The name blckt. is intentionally stripped. No vowels, no fuss. A mark, not a word.') }}</p>
            </div>
        </div>
    </section>

    <section class="content-section about-stats-section reveal">
        <div class="stats-grid stats-grid-divided">
            <div class="stat-item">
                <span class="stat-number" data-target="2024" data-suffix="">0</span>
                <span class="stat-label">{{ __('founded') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="12" data-suffix="+">0</span>
                <span class="stat-label">{{ __('websites delivered') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="8" data-suffix="">0</span>
                <span class="stat-label">{{ __('clothing designs') }}</span>
            </div>
        </div>
    </section>

    <section class="content-section about-work-section reveal">
        <span class="section-eyebrow">{{ __('What we do') }}</span>
        <div class="work-grid">
            <a href="/clothing" class="work-card">
                <span class="work-card-index">01 — {{ __('Clothing') }}</span>
                <img src="{{ asset('assets/imgs/brand/blckt_coll_main.png') }}" alt="blckt. clothing">
                <div class="work-card-body">
                    <h3>{{ __('Apparel that actually says something.') }}</h3>
                    <p>{{ __('blckt. clothing started as a personal project and became a micro-collection of premium oversized tees. Graphic-led, culture-literate, Hungarian-made. Every piece is designed in-house — no outsourced taste.') }}</p>
                    <span class="btn-pill work-card-btn">{{ __('shop collection') }}</span>
                </div>
            </a>
            <a href="/websites" class="work-card">
                <span class="work-card-index">02 — {{ __('Websites') }}</span>
                <img src="{{ asset('assets/imgs/websites/juiced/juiced_whole1.jpg') }}" alt="blckt. websites">
                <div class="work-card-body">
                    <h3>{{ __('Websites people actually want to visit.') }}</h3>
                    <p>{{ __('We design and build custom websites for brands that care about the details. No templates, no bloat. We use Figma to design, write real code to build, and hand over something you’re proud to share.') }}</p>
                    <span class="btn-pill work-card-btn">{{ __('see our work') }}</span>
                </div>
            </a>
        </div>
    </section>

    <section class="content-section about-values-section reveal">
        <span class="section-eyebrow">{{ __('Values') }}</span>
        <div class="values-grid">
            <h2 class="values-title">{{ __('Three things we won’t compromise on.') }}</h2>
            <div class="values-accordion">
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('No filler.') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('If it doesn’t earn its place on the page or on the shirt, it doesn’t ship. No stock photography, no lorem ipsum, no decoration for decoration’s sake.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Built to last.') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('Heavyweight cotton. Real code, not page-builder duct tape. We design for the fifth wash and the fifth deploy, not just the first screenshot.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Small on purpose.') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('We turn down more work than we take. Every project gets our full attention because there is never five other projects competing for it.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section about-team-section reveal">
        <span class="section-eyebrow">{{ __('The team') }}</span>
        <div class="team-grid">
            <div class="team-member">
                <div class="team-avatar team-avatar-image">
                    <img src="{{ asset('assets/imgs/clothing/ratio/ratio_showroom_back.png') }}" alt="{{ __('Design & Clothing') }}">
                </div>
                <span class="team-role">{{ __('Design & Clothing') }}</span>
                <h3 class="team-title">{{ __('Creative direction') }}</h3>
                <p>{{ __('Visual language, garment design, brand identity. Every graphic starts here.') }}</p>
            </div>
            <div class="team-member">
                <div class="team-avatar team-avatar-logos">
                    <div class="floating-logos team-floating-logos">
                        <img src="{{ asset('assets/imgs/tools/figma_logo.png') }}" alt="Figma" class="logo-figma">
                        <img src="{{ asset('assets/imgs/tools/vs_logo.png') }}" alt="VS Code" class="logo-vscode">
                        <img src="{{ asset('assets/imgs/tools/js_logo.png') }}" alt="JavaScript" class="logo-js">
                    </div>
                </div>
                <span class="team-role">{{ __('Development') }}</span>
                <h3 class="team-title">{{ __('Engineering') }}</h3>
                <p>{{ __('Frontend, Figma-to-code, CMS integration. If it runs in a browser, this is where it gets built.') }}</p>
            </div>
            <div class="team-member">
                <div class="team-avatar team-avatar-image">
                    <img src="{{ asset('assets/imgs/websites/paradise/paradise_whole1.jpg') }}" alt="{{ __('Strategy') }}">
                </div>
                <span class="team-role">{{ __('Strategy') }}</span>
                <h3 class="team-title">{{ __('Client work') }}</h3>
                <p>{{ __('Scoping, timelines, client communication. We keep projects moving and expectations honest.') }}</p>
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

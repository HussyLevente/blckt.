@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
@endpush

@section('content')
    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('We’re not just another agency chasing trends.') }}</h2>
                <span class="manifesto-brand">{{ __('We build things that last.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section about-story-section reveal">
        <div class="about-story-grid">
            <div class="about-story-text">
                <h2 class="about-story-title">{{ __('Who we are') }}</h2>
                <p>{{ __('blckt. started as two disciplines that never got along on other teams — design and code, thread and pixels. We put them under one roof.') }}</p>
                <p>{{ __('Every project gets the same obsession, whether it ships as a t-shirt or a website: no filler, no shortcuts, just the thing done right.') }}</p>
            </div>
            <div class="floating-logos about-floating-logos">
                <img src="{{ asset('assets/imgs/figma_logo.png') }}" alt="Figma" class="logo-figma">
                <img src="{{ asset('assets/imgs/vs_logo.png') }}" alt="VS Code" class="logo-vscode">
                <img src="{{ asset('assets/imgs/js_logo.png') }}" alt="JavaScript" class="logo-js">
            </div>
        </div>
    </section>

    <section class="content-section about-stats-section reveal">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number" data-target="50" data-suffix="+">0</span>
                <span class="stat-label">{{ __('Projects shipped') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="30" data-suffix="+">0</span>
                <span class="stat-label">{{ __('Happy clients') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="5" data-suffix="">0</span>
                <span class="stat-label">{{ __('Years in the game') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="100" data-suffix="%">0</span>
                <span class="stat-label">{{ __('Obsessed with detail') }}</span>
            </div>
        </div>
    </section>

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('Two crafts. One obsession with quality.') }}</h2>
                <span class="manifesto-brand">{{ __('Clothing and code, done right.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section about-work-section reveal">
        <div class="work-grid">
            <a href="/clothing" class="work-card">
                <img src="{{ asset('assets/imgs/blckt_coll_main.png') }}" alt="blckt. clothing">
                <div class="work-card-body">
                    <h3>{{ __('Clothing') }}</h3>
                    <p>{{ __('Premium apparel engineered to outlast the hype.') }}</p>
                </div>
            </a>
            <a href="/websites" class="work-card">
                <img src="{{ asset('assets/imgs/paradise_promo_1.png') }}" alt="blckt. websites">
                <div class="work-card-body">
                    <h3>{{ __('Websites') }}</h3>
                    <p>{{ __('Precision-built sites that actually convert.') }}</p>
                </div>
            </a>
        </div>
    </section>

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('Curious what we could build together?') }}</h2>
                <span class="manifesto-brand">{{ __('Let’s talk.') }}</span>
            </div>
        </section>
    </div>

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

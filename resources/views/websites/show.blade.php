@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/website-project.css') }}">
@endpush

@section('content')
    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ $project['tagline'] }}</h2>
                <span class="manifesto-brand">{{ $project['name'] }}</span>
            </div>
        </section>
    </div>

    <section class="content-section project-overview-section reveal">
        <div class="project-overview-grid">
            <div class="project-overview-left">
                <a href="/websites" class="project-back-btn">&larr; {{ __('all websites') }}</a>
                <img src="{{ asset($project['logo']) }}" alt="{{ $project['name'] }}" class="project-logo {{ !empty($project['logo_invert']) ? 'project-logo-invert' : '' }}">
                <p class="project-summary">{!! $project['summary'] !!}</p>
            </div>
            <div class="project-overview-right">
                <div class="project-meta-item">
                    <span class="project-meta-label">{{ __('Timeline') }}</span>
                    <span class="project-meta-value">{{ $project['duration'] }}</span>
                </div>
                <div class="project-meta-item">
                    <span class="project-meta-label">{{ __('Software used') }}</span>
                    <span class="project-meta-value">{{ implode(', ', $project['tools']) }}</span>
                </div>
                <div class="project-meta-item">
                    <span class="project-meta-label">{{ __('Investment') }}</span>
                    <span class="project-meta-value">{{ $project['price'] }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section project-gallery-section reveal">
        <h2 class="project-section-title">{{ __('A closer look') }}</h2>
        <div class="project-gallery-grid">
            @foreach ($project['gallery'] as $image)
                <button type="button" class="project-gallery-item">
                    <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}" loading="lazy">
                    <span class="project-gallery-expand">&#10530;</span>
                </button>
            @endforeach
        </div>
    </section>

    <section class="content-section project-compare-section reveal">
        <div class="project-compare-grid">
            <div class="project-compare-card">
                <span class="project-compare-label">{{ __('The expectation') }}</span>
                <p>{!! $project['expectation'] !!}</p>
            </div>
            <div class="project-compare-card project-compare-card-outcome">
                <span class="project-compare-label">{{ __('The outcome') }}</span>
                <p>{!! $project['outcome'] !!}</p>
            </div>
        </div>
    </section>

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('Want something like this for your brand?') }}</h2>
                <span class="manifesto-brand">{{ __('Let’s build it.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section project-contact-section reveal">
        <div class="contact-right project-contact-card">
            @include('partials.contact-form')
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
@endpush

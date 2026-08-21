@extends('layout')

@section('title', __('Before & After | blckt. — Website Redesigns'))
@section('meta_description', __('Drag the handle and watch a dated website turn into something people actually want to use. Every blckt. rebuild, side by side with the site it replaced.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/compare.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/redesigns.css') }}">
@endpush

@section('content')
    <section class="content-section redesign-hero">
        <span class="section-eyebrow" data-anim="fade">{{ __('Before & after') }}</span>
        <h1 class="redesign-hero-title"><span class="anim-mask">{{ __('Same company.') }}</span><span class="anim-mask">{{ __('Different decade.') }}</span></h1>
        <p class="redesign-hero-text" data-anim="up" data-anim-delay="220">{{ __('On the left is the site the company had. On the right is the one I built for them. Drag the handle across and see the difference for yourself — no before/after marketing photography, just the actual screens.') }}</p>

        <div class="redesign-hero-meta" data-anim-stagger="120" data-anim-delay="340">
            <div class="redesign-hero-stat">
                <span class="redesign-hero-stat-value">{{ count($redesigns) }}</span>
                <span class="redesign-hero-stat-label">{{ __('rebuilds shown') }}</span>
            </div>
            <div class="redesign-hero-stat">
                <span class="redesign-hero-stat-value">100%</span>
                <span class="redesign-hero-stat-label">{{ __('written from scratch') }}</span>
            </div>
            <div class="redesign-hero-stat">
                <span class="redesign-hero-stat-value">1</span>
                <span class="redesign-hero-stat-label">{{ __('person, start to finish') }}</span>
            </div>
        </div>
    </section>

    @forelse ($redesigns as $index => $redesign)
        <section class="content-section redesign-item" data-anim="up">
            <div class="redesign-item-head">
                <span class="redesign-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <a href="{{ route('websites.show', $redesign['slug']) }}" class="redesign-item-link anim-underline">
                    {{ __('See the full project') }} <span aria-hidden="true">&#8594;</span>
                </a>
            </div>

            @include('partials.compare', [
                'redesign' => $redesign,
                'eyebrow' => __('Redesign'),
                'title' => $redesign['name'],
            ])
        </section>
    @empty
        <section class="content-section redesign-empty">
            <p>{{ __('No before/after pairs yet — check back soon.') }}</p>
            <a href="/websites" class="btn-pill">{{ __('all websites') }}</a>
        </section>
    @endforelse

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('Your site could be the next left-hand side.') }}</h2>
                <span class="manifesto-brand">{{ __('Let’s fix that.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section contact-cta-section reveal">
        <div class="contact-grid">
            <div class="contact-left">
                <h2 class="contact-title">{{ __('Send me your current site.') }}</h2>
                <p class="contact-text">{{ __('Paste the URL of the site you have now and tell me what bothers you about it. I’ll come back with what I would change, what it would cost, and how long it would take — before you commit to anything.') }}</p>
                <a href="mailto:hello@blckt.hu" class="contact-email">{{ __('Email: hello@blckt.hu') }}</a>
                <a href="https://wa.me/36302552432" target="_blank" rel="noopener" class="contact-email contact-whatsapp">{{ __('Message on WhatsApp') }}</a>
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
    <script src="{{ asset('assets/js/compare.js') }}"></script>
@endpush

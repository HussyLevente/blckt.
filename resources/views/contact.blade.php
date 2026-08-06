@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
@endpush

@section('content')
    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('Got a project in mind?') }}</h2>
                <span class="manifesto-brand">{{ __('Let’s make it happen.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section contact-cta-section reveal">
        <div class="contact-grid">
            <div class="contact-left">
                <h2 class="contact-title">{{ __('Let’s get in touch.') }}</h2>
                <p class="contact-text">{{ __('Whether it’s a t-shirt drop or a full site build, tell us what you’re after and we’ll take it from there. No bots, no runaround — just us reading your message and getting back to you.') }}</p>
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

@extends('layout')

@section('title', __('Contact | blckt. — Get a Quote for Your Website'))
@section('meta_description', __('Tell me about your project and I’ll get back to you within 24 hours. No bots, no runaround.'))

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
                <p class="contact-text">{{ __('Whether it’s a t-shirt drop or a full site build, tell me what you’re after and I’ll take it from there. No bots, no runaround — just me reading your message and getting back to you.') }}</p>
                <a href="mailto:blckt.websites@gmail.com" class="contact-email">{{ __('Email: blckt.websites@gmail.com') }}</a>
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
@endpush

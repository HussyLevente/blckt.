@extends('layout')

@section('title', __('Back in a moment | blckt.'))
@section('meta_description', __('The site is briefly down for maintenance. It will be back shortly.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/errors.css') }}">
@endpush

@section('content')
    <section class="content-section error-404-section">
        <span class="error-404-code">503</span>
        <h1 class="error-404-title">{{ __('Back in a moment.') }}</h1>
        <p class="error-404-text">{{ __('I am pushing an update. The site will be back in a couple of minutes — thanks for waiting.') }}</p>
        <div class="error-404-links">
            <a href="mailto:hello@blckt.hu" class="error-404-link">hello@blckt.hu</a>
            <a href="https://wa.me/36302552432" target="_blank" rel="noopener" class="error-404-link">{{ __('WhatsApp') }}</a>
        </div>
    </section>
@endsection

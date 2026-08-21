@extends('layout')

@section('title', __('Something went wrong | blckt.'))
@section('meta_description', __('Something broke on my end. Try again in a moment, or email hello@blckt.hu.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/errors.css') }}">
@endpush

@section('content')
    <section class="content-section error-404-section">
        <span class="error-404-code">500</span>
        <h1 class="error-404-title">{{ __('Something went wrong.') }}</h1>
        <p class="error-404-text">{{ __('That one is on me, not on you. Try again in a moment — if it keeps happening, send me a message and I will fix it.') }}</p>
        <div class="error-404-links">
            <a href="/" class="btn-pill">{{ __('Back to home') }}</a>
            <a href="mailto:hello@blckt.hu" class="error-404-link">hello@blckt.hu</a>
            <a href="/contact" class="error-404-link">{{ __('contact') }}</a>
        </div>
    </section>
@endsection

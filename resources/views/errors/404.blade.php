@extends('layout')

@section('title', __('Page Not Found | blckt.'))
@section('meta_description', __('This page doesn’t exist. Head back to blckt. and find what you were looking for.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/errors.css') }}">
@endpush

@section('content')
    <section class="content-section error-404-section">
        <span class="error-404-code">404</span>
        <h1 class="error-404-title">{{ __('This page doesn’t exist.') }}</h1>
        <p class="error-404-text">{{ __('The link might be broken, or the page moved. Let’s get you back on track.') }}</p>
        <div class="error-404-links">
            <a href="/" class="btn-pill">{{ __('Back to home') }}</a>
            <a href="/websites" class="error-404-link">{{ __('websites') }}</a>
            <a href="/clothing" class="error-404-link">{{ __('clothing') }}</a>
            <a href="/contact" class="error-404-link">{{ __('contact') }}</a>
        </div>
    </section>
@endsection

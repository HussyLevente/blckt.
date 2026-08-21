@extends('layout')

@section('title', __('Session expired | blckt.'))
@section('meta_description', __('Your session expired before the form was sent. Reload the page and try again.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/errors.css') }}">
@endpush

@section('content')
    <section class="content-section error-404-section">
        <span class="error-404-code">419</span>
        <h1 class="error-404-title">{{ __('Your session expired.') }}</h1>
        <p class="error-404-text">{{ __('The page sat open too long and the form token went stale. Reload and send it again — nothing you typed was lost on my side, because it never arrived.') }}</p>
        <div class="error-404-links">
            <a href="/contact" class="btn-pill">{{ __('Try again') }}</a>
            <a href="/" class="error-404-link">{{ __('Home') }}</a>
        </div>
    </section>
@endsection

@extends('layout')

@section('title', __('Page Not Found | blckt.'))
@section('meta_description', __('The link might be broken, or the page moved. Let’s get you back on track.'))
@section('robots', 'noindex, follow')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="error-page shell">
        <p class="error-code">404</p>
        <h1 class="t3" style="margin-top: var(--space-8)">{{ __('This page doesn’t exist.') }}</h1>
        <p class="t5 error-message">{{ __('The link might be broken, or the page moved. Let’s get you back on track.') }}</p>

        <div class="error-actions">
            <a href="/" class="btn btn-solid">{{ __('Back to home') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            <a href="/websites" class="btn">{{ __('See the work') }}</a>
            <a href="/contact" class="btn">{{ __('Contact') }}</a>
        </div>
    </section>
@endsection

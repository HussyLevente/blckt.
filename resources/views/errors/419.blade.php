@extends('layout')

@section('title', __('Session Expired | blckt.'))
@section('meta_description', __('The page sat open too long and the security token went stale. Reload and try again.'))
@section('robots', 'noindex, follow')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="error-page shell">
        <p class="error-code">419</p>
        <h1 class="t3" style="margin-top: var(--space-8)">{{ __('Your session expired.') }}</h1>
        <p class="t5 error-message">{{ __('The page sat open too long and the security token went stale. Reload and try again.') }}</p>

        <div class="error-actions">
            <a href="/" class="btn btn-solid" data-magnetic="0.25">{{ __('Back to home') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            <a href="/websites" class="btn">{{ __('See the work') }}</a>
            <a href="/contact" class="btn">{{ __('Contact') }}</a>
        </div>
    </section>
@endsection

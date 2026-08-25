@extends('layout')

@section('title', __('Under Maintenance | blckt.'))
@section('meta_description', __('The site is briefly down for maintenance. It should return within a few minutes.'))
@section('robots', 'noindex, follow')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="error-page shell">
        <p class="error-code">503</p>
        <h1 class="t3" style="margin-top: var(--space-8)">{{ __('Back in a moment.') }}</h1>
        <p class="t5 error-message">{{ __('The site is briefly down for maintenance. It should return within a few minutes.') }}</p>

        <div class="error-actions">
            <a href="/" class="btn btn-solid">{{ __('Back to home') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            <a href="/websites" class="btn">{{ __('See the work') }}</a>
            <a href="/contact" class="btn">{{ __('Contact') }}</a>
        </div>
    </section>
@endsection

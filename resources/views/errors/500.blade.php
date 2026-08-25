@extends('layout')

@section('title', __('Server Error | blckt.'))
@section('meta_description', __('That is my fault, not yours. Try again in a moment, or email hello@blckt.hu if it keeps happening.'))
@section('robots', 'noindex, follow')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="error-page shell">
        <p class="error-code">500</p>
        <h1 class="t3" style="margin-top: var(--space-8)">{{ __('Something broke on my end.') }}</h1>
        <p class="t5 error-message">{{ __('That is my fault, not yours. Try again in a moment, or email hello@blckt.hu if it keeps happening.') }}</p>

        <div class="error-actions">
            <a href="/" class="btn btn-solid">{{ __('Back to home') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            <a href="/websites" class="btn">{{ __('See the work') }}</a>
            <a href="/contact" class="btn">{{ __('Contact') }}</a>
        </div>
    </section>
@endsection

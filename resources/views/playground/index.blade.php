@extends('layout')

@section('title', __('Template Playground | blckt. — Try a Template with Your Own Content'))
@section('meta_description', __('Put your own text, photos and brand colour into a real blckt. template before you buy it. It runs in your browser, nothing is uploaded, and your changes are kept.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/templates.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/playground.css') }}">
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [
            ['label' => __('Templates'), 'url' => route('templates.index')],
            ['label' => __('Playground')],
        ]])
    </div>

    <section class="page-head shell" aria-labelledby="pg-index-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('Playground') }}</span>

        <h1 class="t1 page-head-title optical-left" id="pg-index-title">
            <span class="mask">{{ __('Try it with') }}</span>
            <span class="mask">{{ __('your own stuff.') }}</span>
        </h1>

        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 3">{{ __('Pick a live demo below and rewrite it. Your words, your photos, your brand colour — in the real template, in your browser, before any money changes hands. Nothing is uploaded and nothing is sent to me.') }}</p>
    </section>

    <section class="section-tight shell" aria-labelledby="pg-pick-title">
        <h2 class="visually-hidden" id="pg-pick-title">{{ __('Editable demos') }}</h2>

        <div class="pg-others pg-others-wide" data-reveal-group>
            @foreach ($demos as $demo)
                <a href="{{ $demo['playground'] }}" class="pg-other">
                    <span class="t8 ink-faint">{{ $demo['template']['name'] }} &middot; {{ $demo['sector'] }}</span>
                    <span class="t3 pg-other-name">{{ $demo['name'] }}</span>
                    <span class="t6 pg-other-blurb">{{ $demo['blurb'] }}</span>
                    <span class="link-arrow t8">{{ __('Open the playground') }} <span class="arrow" aria-hidden="true">&#8594;</span></span>
                </a>
            @endforeach
        </div>

        {{-- Ket demo ugyanabbol a sablonbol: ez a lap allitasa, es itt
             lathato a legjobban, hogy nem harom kulon terv, hanem harom
             kulon tartalom ugyanabban a tervben. --}}
        <p class="t8 ink-faint" style="margin-top: var(--space-12); max-width: 76ch" data-reveal>{{ __('Some of these are the same template twice, filled in for two completely different trades. That is the whole point: the design holds, the content moves.') }}</p>
    </section>

    <section class="section shell" aria-labelledby="pg-index-note-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('The honest part') }}</span>
                <h2 class="t2 section-head-title" id="pg-index-note-title">{{ __('What this is, and what it isn’t.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('It is a fitting room, not a website builder. It answers one question — does this design work with my business in it — and stops there.') }}</p>
        </header>

        <div class="ledger" data-reveal>
            <div class="ledger-col ledger-col-get">
                <div class="ledger-head">
                    <span class="ledger-sign" aria-hidden="true">+</span>
                    <h3 class="t4">{{ __('What it does') }}</h3>
                </div>
                <ul class="ledger-list">
                    <li>{{ __('Runs the real template, not a picture of it') }}</li>
                    <li>{{ __('Takes your text, your photos and your brand colour') }}</li>
                    <li>{{ __('Keeps your changes in this browser between visits') }}</li>
                    <li>{{ __('Shows it at desktop, tablet and phone width') }}</li>
                    <li>{{ __('Costs nothing and asks for nothing') }}</li>
                </ul>
            </div>

            <div class="ledger-col ledger-col-give">
                <div class="ledger-head">
                    <span class="ledger-sign" aria-hidden="true">&minus;</span>
                    <h3 class="t4">{{ __('What it doesn’t') }}</h3>
                </div>
                <ul class="ledger-list">
                    <li>{{ __('Upload anything, anywhere — it all stays on your device') }}</li>
                    <li>{{ __('Send your version to me, or publish it') }}</li>
                    <li>{{ __('Let you move things around or change the layout') }}</li>
                    <li>{{ __('Follow you to another device or another browser') }}</li>
                    <li>{{ __('Replace the real build — that part is still my job') }}</li>
                </ul>
            </div>
        </div>

        <div class="actions actions-spaced" data-reveal>
            <a href="{{ route('templates.index') }}" class="btn btn-solid">{{ __('See all six templates') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            <a href="{{ route('services') }}" class="btn">{{ __('Or price a custom build') }}</a>
        </div>
    </section>
@endsection

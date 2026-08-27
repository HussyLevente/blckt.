@extends('layout')

@section('title', __('Saved | blckt.'))
@section('meta_description', __('The templates you saved while browsing. The list lives in your browser — nothing is uploaded and no account is needed.'))
{{-- Latogatonkent mas, es semmi kozos tartalma nincs: nincs mit indexelni. --}}
@section('robots', 'noindex, follow')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/templates.css') }}">
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [['label' => __('Saved')]]])
    </div>

    <section class="page-head shell" aria-labelledby="saved-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('Saved') }}</span>

        <h1 class="t1 page-head-title optical-left" id="saved-title">
            <span class="mask">{{ __('Your shortlist.') }}</span>
        </h1>

        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 2">{{ __('Whatever you saved while looking around, in the order you saved it. The list lives in this browser only — nothing is uploaded, and I cannot see it. Send me the names when you have decided.') }}</p>
    </section>

    <section class="section-tight shell" aria-labelledby="saved-list-title">
        <h2 class="visually-hidden" id="saved-list-title">{{ __('Saved templates') }}</h2>

        {{-- Minden sablon kimegy a jelolesbe, es a szkript hagyja lathatoan
             azokat, amik el vannak mentve. Igy a lista kiszolgalo nelkul is
             a latogatoe marad, a kartya jelolese pedig egy helyen el. --}}
        <div class="tpl-grid" data-saved-list hidden>
            @foreach ($templates as $template)
                @include('partials.template-card', ['template' => $template])
            @endforeach
        </div>

        <div class="saved-empty" data-saved-empty>
            <p class="t4">{{ __('Nothing saved yet.') }}</p>
            <p class="t6 saved-empty-note">{{ __('Open the catalogue and press Save on anything worth coming back to. It stays here until you remove it or clear your browser data.') }}</p>
            <div class="actions" style="margin-top: var(--space-8)">
                <a href="{{ route('templates.index') }}" class="btn btn-solid">{{ __('Browse the templates') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            </div>
        </div>
    </section>
@endsection


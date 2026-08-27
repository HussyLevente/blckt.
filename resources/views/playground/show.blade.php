@extends('layout')

@section('title', __('Try :name with your own content | blckt.', ['name' => $demo['template']['name']]))
@section('meta_description', __('Put your own words and photos into the :name template before you buy it. Nothing is uploaded — everything stays in your browser.', ['name' => $demo['template']['name']]))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/templates.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/playground.css') }}">
@endpush

@php
    // A szkript minden lathato szoveget innen kap, hogy a szerkeszto is a
    // lap nyelven beszeljen. A JS-ben nincs beegetett mondat.
    $pgConfig = $config + [
        'labels' => [
            'background' => __('Background photo'),
            'imageOnPage' => __('Photo on the page'),
            'hintEdit' => __('Tap any text to rewrite it. Tap any photo to swap it.'),
            'hintPreview' => __('Preview — the page behaves exactly as a visitor would see it.'),
            'editingText' => __('Type your own words. Enter to finish, Esc to undo.'),
            'textSaved' => __('Text updated.'),
            'imageSaved' => __('Photo swapped.'),
            'working' => __('Preparing your photo…'),
            'undone' => __('Last change undone.'),
            'restored' => __('Your earlier changes are back — they were waiting in this browser.'),
            'saved' => __('Saved in this browser'),
            'ready' => __('Nothing changed yet'),
            'sessionOnly' => __('Changes last until you close this tab'),
            'quota' => __('This browser is out of storage. Reset, or use fewer photos.'),
            'tooBig' => __('That file is over 25 MB. Pick a smaller one.'),
            'notImage' => __('That is not an image file.'),
            'imageFailed' => __('That image could not be read. Try a JPG or PNG.'),
            'badLink' => __('Paste a full link that starts with https://'),
            'confirmReset' => __('Put everything back the way it was? Your text and photos here will be lost.'),
        ],
    ];
@endphp

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [
            ['label' => __('Templates'), 'url' => route('templates.index')],
            ['label' => $demo['template']['name'], 'url' => $demo['template']['url']],
            ['label' => __('Playground')],
        ]])
    </div>

    {{-- ── Head ─────────────────────────────────────────────────── --}}
    <section class="page-head shell" aria-labelledby="pg-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('Playground') }} &middot; {{ $demo['template']['name'] }}</span>

        <h1 class="t1 page-head-title optical-left" id="pg-title">
            <span class="mask">{{ __('Put your own words') }}</span>
            <span class="mask">{{ __('in it. Right now.') }}</span>
        </h1>

        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 3">{{ __('This is the real :name template, running live. Rewrite any line, drop in your own photos, set your brand colour — and see whether it still looks like you. Nothing is uploaded anywhere, and nothing is sent to me.', ['name' => $demo['name']]) }}</p>
    </section>

    {{-- ── The editor ───────────────────────────────────────────── --}}
    <section class="section-tight shell" aria-labelledby="pg-editor-title" data-pg data-pg-config="{{ json_encode($pgConfig, JSON_UNESCAPED_UNICODE) }}">
        <h2 class="visually-hidden" id="pg-editor-title">{{ __('Editor') }}</h2>

        {{-- Szkript nelkul ez a lap nem tud mit adni, ezert ki is mondjuk,
             es kiadjuk a demo cimet - az szkript nelkul is megnyilik. --}}
        <noscript>
            <p class="pg-noscript">{{ __('The playground needs JavaScript. You can still open the demo itself:') }}
                <a href="{{ $demo['url'] }}" class="link-underline" target="_blank" rel="noopener">{{ $demo['name'] }}</a>
            </p>
        </noscript>

        <div class="pg-bar">
            <div class="pg-bar-group" role="group" aria-label="{{ __('Mode') }}">
                <button type="button" class="pg-btn is-active" data-pg-mode="edit" aria-pressed="true">{{ __('Edit') }}</button>
                <button type="button" class="pg-btn" data-pg-mode="preview" aria-pressed="false">{{ __('Preview') }}</button>
            </div>

            @foreach ($demo['swatches'] as $swatch)
                <label class="pg-swatch">
                    <span class="pg-swatch-label">{{ $swatch['label'] }}</span>
                    <input type="color" value="{{ $swatch['default'] }}" data-pg-swatch="{{ $swatch['key'] }}">
                </label>
            @endforeach

            <div class="pg-bar-group pg-bar-widths" role="group" aria-label="{{ __('Width') }}">
                <button type="button" class="pg-btn is-active" data-pg-width="0" aria-pressed="true">{{ __('Desktop') }}</button>
                <button type="button" class="pg-btn" data-pg-width="820" aria-pressed="false">{{ __('Tablet') }}</button>
                <button type="button" class="pg-btn" data-pg-width="390" aria-pressed="false">{{ __('Phone') }}</button>
            </div>

            <div class="pg-bar-end">
                <button type="button" class="pg-btn" data-pg-undo disabled>{{ __('Undo') }}</button>
                <button type="button" class="pg-btn pg-btn-quiet" data-pg-reset disabled>{{ __('Start over') }}</button>
                <span class="pg-status" data-pg-status aria-live="polite">{{ __('Nothing changed yet') }}</span>
            </div>
        </div>

        {{-- Az utmutato sor egyben az elo visszajelzes helye is: a szkript
             ide irja, mi tortent eppen. --}}
        <p class="pg-hint" data-pg-hint aria-live="polite">{{ __('Tap any text to rewrite it. Tap any photo to swap it.') }}</p>

        <div class="pg-shell" data-pg-shell>
            <span class="tpl-frame pg-frame">
                <span class="tpl-chrome" aria-hidden="true">
                    <span class="tpl-dot"></span>
                    <span class="tpl-dot"></span>
                    <span class="tpl-dot"></span>
                    <span class="tpl-url">{{ $demo['display_url'] }}</span>
                </span>

                <span class="pg-stage">
                    <iframe
                        src="{{ $demo['url'] }}"
                        title="{{ __(':name — editable demo', ['name' => $demo['name']]) }}"
                        data-pg-frame
                    ></iframe>
                </span>
            </span>
        </div>

        {{-- Kep-panel. Rejtve indul: akkor jelenik meg, amikor a
             latogato kivalaszt egy kepet a kereten belul. --}}
        <div class="pg-panel" data-pg-panel hidden>
            <div class="pg-panel-head">
                <span class="t8 ink-faint" data-pg-panel-kind>{{ __('Photo on the page') }}</span>
                <button type="button" class="pg-btn pg-btn-quiet" data-pg-close>{{ __('Close') }}</button>
            </div>

            <div class="pg-panel-body">
                <label class="pg-file">
                    <span class="pg-file-label">{{ __('Choose a photo') }}</span>
                    {{-- capture nelkul: igy a telefon a galeriat es a kamerat
                         is felkinalja, nem eroszakolja ra egyiket sem. --}}
                    <input type="file" accept="image/*" data-pg-file>
                    <span class="t8 ink-faint">{{ __('It is resized in your browser. The file never leaves your device.') }}</span>
                </label>

                <div class="pg-link">
                    <label class="pg-link-label t8 ink-faint" for="pg-link-input">{{ __('Or paste an image link') }}</label>
                    <div class="pg-link-row">
                        <input type="url" id="pg-link-input" placeholder="https://…" data-pg-link>
                        <button type="button" class="pg-btn" data-pg-apply-link>{{ __('Use it') }}</button>
                    </div>
                </div>

                <button type="button" class="pg-btn pg-btn-quiet" data-pg-clear disabled>{{ __('Put the original back') }}</button>
            </div>
        </div>
    </section>

    {{-- ── How ──────────────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="pg-how-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('How it works') }}</span>
                <h2 class="t2 section-head-title" id="pg-how-title">{{ __('Three things you can touch.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Everything you change here is exactly the kind of thing I change for you when you order — the words, the photos, the colour. The layout stays as designed, here and afterwards.') }}</p>
        </header>

        <ol class="pg-steps" data-reveal-group>
            <li>
                <span class="pg-step-index">01</span>
                <h3 class="t4">{{ __('Rewrite the words') }}</h3>
                <p class="t6">{{ __('Tap any headline, paragraph, button or menu item. Type over it. Enter finishes, Esc puts it back.') }}</p>
            </li>
            <li>
                <span class="pg-step-index">02</span>
                <h3 class="t4">{{ __('Swap the photos') }}</h3>
                <p class="t6">{{ __('Tap a photo and pick one from your phone or computer. Big background photos have their own button on top of them.') }}</p>
            </li>
            <li>
                <span class="pg-step-index">03</span>
                <h3 class="t4">{{ __('Set your colour') }}</h3>
                <p class="t6">{{ __('Pick your brand colour and the whole design follows it — every accent, shade and hover state at once.') }}</p>
            </li>
        </ol>

        {{-- Ez a lap egy dolgot igerhet meg, amit egy kepernyokep nem: hogy
             a valtoztatasok megmaradnak. Ezt ki is mondjuk, a hataraval
             egyutt - az "eltunt a munkam" rosszabb, mint az elore
             megmondott korlat. --}}
        <div class="pg-note" data-reveal>
            <h3 class="t4">{{ __('Your changes stay put') }}</h3>
            <p class="t6">{{ __('Everything you do here is kept in this browser, on this device. Close the tab, come back tomorrow, and your version is still here. It is not sent to me and not published anywhere — if you want me to see it, take a screenshot. “Start over” wipes it and brings back the original.') }}</p>
        </div>
    </section>

    {{-- ── Switch ───────────────────────────────────────────────── --}}
    @if ($others)
        <section class="section-tight shell" aria-labelledby="pg-others-title">
            <header class="section-head">
                <div>
                    <span class="t8 ink-faint">{{ __('Try another') }}</span>
                    <h2 class="t2 section-head-title" id="pg-others-title">{{ __('Same idea, different business.') }}</h2>
                </div>
                <p class="t6 section-head-note">{{ __('Each one keeps its own changes, so you can leave one half-finished and come back to it.') }}</p>
            </header>

            <div class="pg-others" data-reveal-group>
                @foreach ($others as $other)
                    <a href="{{ $other['playground'] }}" class="pg-other">
                        <span class="t8 ink-faint">{{ $other['template']['name'] }} &middot; {{ $other['sector'] }}</span>
                        <span class="t3 pg-other-name">{{ $other['name'] }}</span>
                        <span class="t6 pg-other-blurb">{{ $other['blurb'] }}</span>
                        <span class="link-arrow t8">{{ __('Open the playground') }} <span class="arrow" aria-hidden="true">&#8594;</span></span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ── Closing ──────────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="pg-claim-title">
        <div class="contact-grid">
            <div data-reveal>
                <span class="t8 ink-faint">{{ __('If it fits') }}</span>
                <h2 class="t2" id="pg-claim-title" style="margin-top: var(--space-5)">{{ __('Then let me build it properly.') }}</h2>
                <p class="t5 contact-lede">{{ __('What you just made is a sketch in your browser. The real one gets your domain, your photos compressed properly, your forms working, and the whole thing handed over as code you own. :template is the :tier package — :price, and yours outright.', [
                    'template' => $demo['template']['name'],
                    'tier' => mb_strtolower($demo['template']['tier_name']),
                    'price' => $demo['template']['price_label'],
                ]) }}</p>

                <div class="actions actions-spaced">
                    <a href="{{ $demo['template']['url'] }}" class="btn">{{ __('See what :name includes', ['name' => $demo['template']['name']]) }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                    <a href="{{ route('templates.index') }}" class="btn">{{ __('All templates') }}</a>
                </div>
            </div>

            <div class="contact-card" data-reveal style="--reveal-index: 1">
                @include('partials.contact-form')
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ \App\Support\Asset::url('assets/js/playground.js') }}" defer></script>
@endpush

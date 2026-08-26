@extends('layout')

@section('title', $template['name'].' — '.$template['sector'].' '.__('website template').' | blckt.')
@section('meta_description', $template['meta'])
@section('og_type', 'product')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/templates.css') }}">
@endpush

@php
    $cap = \App\Http\Controllers\TemplateController::LICENCE_CAP;
@endphp

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $template['name'].' — '.__('website template'),
        'url' => $template['url'],
        'category' => $template['sector'],
        'description' => $template['summary'],
        'brand' => ['@id' => url('/').'#studio'],
        'offers' => [
            '@type' => 'Offer',
            'price' => (string) $template['price'],
            'priceCurrency' => 'HUF',
            'url' => $template['url'],
            'seller' => ['@id' => url('/').'#studio'],
            'availability' => $template['sold_out']
                ? 'https://schema.org/SoldOut'
                : 'https://schema.org/LimitedAvailability',
            'eligibleQuantity' => [
                '@type' => 'QuantitativeValue',
                'value' => $template['left'],
                'unitText' => 'licence',
            ],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [
            ['label' => __('Templates'), 'url' => route('templates.index')],
            ['label' => $template['name']],
        ]])
    </div>

    {{-- ── Head ─────────────────────────────────────────────────── --}}
    <section class="tpl-head shell" aria-labelledby="template-title">
        <div>
            <span class="t8 ink-faint">{{ $template['tier_name'] }} &middot; {{ $template['sector'] }}</span>

            <h1 class="t1 tpl-head-title optical-left" id="template-title">
                <span class="mask">{{ $template['name'] }}</span>
            </h1>

            <p class="t5 tpl-head-tagline" data-reveal style="--reveal-index: 2">{{ $template['tagline'] }}</p>

            <div class="tpl-head-actions" data-reveal style="--reveal-index: 3">
                @if ($template['sold_out'])
                    <span class="btn" aria-disabled="true">{{ __('All :cap licences sold', ['cap' => $cap]) }}</span>
                    <a href="{{ route('templates.index') }}" class="btn">{{ __('See the other templates') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                @else
                    <a href="/contact" class="btn btn-solid">{{ __('Claim this one') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                    @if ($template['has_demo'])
                        <a href="{{ $template['demos'][0]['url'] }}" class="btn" target="_blank" rel="noopener">{{ __('Try it live') }} <span class="arrow-ne" aria-hidden="true">&#8599;</span></a>
                    @else
                        <a href="{{ route('templates.index') }}" class="btn">{{ __('All templates') }}</a>
                    @endif
                @endif
            </div>
        </div>

        {{-- Ar-blokk. Az ar fix, nem "-tol": a sablonnal pont az a lenyeg,
             hogy nincs mit felmerni rajta. --}}
        <aside class="tpl-price-card" data-reveal style="--reveal-index: 1" aria-label="{{ __('Price and terms') }}">
            <span class="t8 ink-faint">{{ __(':tier package — fixed price', ['tier' => $template['tier_name']]) }}</span>
            <strong class="tpl-price">{{ $template['price_label'] }}</strong>
            <span class="t8 tpl-price-note">{{ __('Excluding VAT. Half up front, half on handover. Nothing monthly.') }}</span>

            <dl class="tpl-price-facts">
                <div>
                    <dt>{{ __('Pages') }}</dt>
                    <dd>{{ $template['pages'] }}</dd>
                </div>
                <div>
                    <dt>{{ __('Live in') }}</dt>
                    <dd>{{ $template['days_label'] }}</dd>
                </div>
                <div>
                    <dt>{{ __('Backend') }}</dt>
                    <dd>{{ $template['backend'] ? __('Included') : __('None — static page') }}</dd>
                </div>
                <div>
                    <dt>{{ __('Licences') }}</dt>
                    <dd>@include('partials.licence', ['template' => $template])</dd>
                </div>
            </dl>

            {{-- A csomag jellemzoi ugyanabbol a tablabol jonnek, mint az
                 arak oldalan - igy nem lehet olyan sablon, ami tobbet iger,
                 mint amennyi a szintjebe belefer. --}}
            <ul class="tpl-includes t8" style="margin-top: var(--space-8)">
                @foreach ($template['tier_features'] as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        </aside>
    </section>

    {{-- ── Preview ──────────────────────────────────────────────── --}}
    <section class="section-tight shell" aria-labelledby="preview-title">
        <h2 class="visually-hidden" id="preview-title">{{ __('Preview') }}</h2>

        @if ($template['has_demo'])
            {{-- Ket kesz demo all mogotte, tehat nem kepet mutatunk, hanem
                 magat az oldalt. Ez az egyetlen hely, ahol a "kicserelem a
                 tartalmat" allitas bizonyithato is. --}}
            <div data-reveal>
                @include('partials.demo-viewer', ['template' => $template])
            </div>
        @else
            <div data-reveal>
                @include('partials.template-preview', [
                    'template' => $template,
                    'eager' => true,
                    'alt' => __(':name — the layout, before your content goes in', ['name' => $template['name']]),
                ])
            </div>

            {{-- Amig nincs elo demo, ezt ki kell mondani. A "hamarosan"
                 allapotot az oldal mashol is igy kezeli: inkabb
                 megmondjuk, mint sugalljuk. --}}
            <p class="demo-pending" style="margin-top: var(--space-5)" data-reveal>{{ __('Live demo being built — this is the layout, with placeholder content') }}</p>
        @endif
    </section>

    {{-- ── What is in it ────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="inside-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('What is in it') }}</span>
                {{-- Az Alap csomagnal ez egyetlen oldal szekcioit sorolja fel,
                     a tobbinel kulon oldalakat - ezert semleges a cim. --}}
                <h2 class="t2 section-head-title" id="inside-title">{{ __('What is on it, in order.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ $template['summary'] }}</p>
        </header>

        <div class="tpl-columns" data-reveal>
            <ol class="tpl-structure">
                @foreach ($template['structure'] as $page)
                    <li>{{ $page }}</li>
                @endforeach
            </ol>

            <div>
                <h3 class="t4">{{ __('Built into this one') }}</h3>
                <ul class="tpl-includes t6" style="margin-top: var(--space-6)">
                    @foreach ($template['includes'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>

                <h3 class="t4" style="margin-top: var(--space-12)">{{ __('Who it is for') }}</h3>
                <p class="t6" style="margin-top: var(--space-5); color: var(--ink-muted)">{{ $template['best_for'] }}</p>
            </div>
        </div>
    </section>

    {{-- ── The trade ────────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="trade-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('The honest part') }}</span>
                <h2 class="t2 section-head-title" id="trade-title">{{ __('What changes. What doesn’t.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('The same trade applies to all six. It is the whole reason this costs what it costs.') }}</p>
        </header>

        <div class="ledger" data-reveal>
            <div class="ledger-col ledger-col-get">
                <div class="ledger-head">
                    <span class="ledger-sign" aria-hidden="true">+</span>
                    <h3 class="t4">{{ __('Becomes yours') }}</h3>
                </div>
                <ul class="ledger-list">
                    @foreach ($tradeOff['swap'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="ledger-col ledger-col-give">
                <div class="ledger-head">
                    <span class="ledger-sign" aria-hidden="true">&minus;</span>
                    <h3 class="t4">{{ __('Stays as designed') }}</h3>
                </div>
                <ul class="ledger-list">
                    @foreach ($tradeOff['fixed'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="actions actions-spaced" data-reveal>
            <a href="{{ route('templates.index') }}#catalogue" class="btn">{{ __('Read the full terms') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            <a href="{{ route('services') }}" class="btn">{{ __('Or price a custom build') }}</a>
        </div>
    </section>

    {{-- ── Next ─────────────────────────────────────────────────── --}}
    <section class="section-tight shell">
        <a href="{{ $nextTemplate['url'] }}" class="next-tpl" data-reveal>
            <span>
                <span class="t8 ink-faint">{{ __('Next template') }}</span>
                <span class="t3 next-tpl-name">{{ $nextTemplate['name'] }}</span>
            </span>
            <span class="next-tpl-arrow" aria-hidden="true">&#8594;</span>
        </a>
    </section>

    {{-- ── Closing ──────────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="claim-title">
        <div class="contact-grid">
            <div data-reveal>
                <span class="t8 ink-faint">{{ __('Claim one') }}</span>
                <h2 class="t2" id="claim-title" style="margin-top: var(--space-5)">{{ __('Want :name?', ['name' => $template['name']]) }}</h2>
                <p class="t5 contact-lede">{{ __('Say the name in the message and roughly when you want to be live. You get the fixed price, the delivery date and the content checklist back — usually the same day.') }}</p>

                <dl class="contact-channels">
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Email') }}</dt>
                        <dd><a href="mailto:hello@blckt.hu" class="contact-channel-value link-underline">hello@blckt.hu</a></dd>
                    </div>
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Response time') }}</dt>
                        <dd class="contact-channel-value">{{ __('Within 24 hours') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="contact-card" data-reveal style="--reveal-index: 1">
                @include('partials.contact-form')
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Csak ott van mit vezerelnie, ahol all elo demo. --}}
    @if ($template['has_demo'])
        <script src="{{ \App\Support\Asset::url('assets/js/template-demo.js') }}" defer></script>
    @endif
@endpush

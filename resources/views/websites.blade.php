@extends('layout')

@section('title', __('Web Projects | blckt. — Custom Websites Built in Budapest'))
@section('meta_description', __('Live client websites built in Budapest — a salon, a tyre service and a type tool — plus the design projects behind them. Real links, real screens.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@push('schema')
    {{-- Csak az elo munkak kerulnek a strukturalt listaba: a tervek nem
         nyilvanosan elerheto oldalak, ezert nem allitjuk oket annak. --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => __('Web projects'),
        'description' => __('Live client websites built by blckt. in Budapest.'),
        'url' => url('/websites'),
        'isPartOf' => ['@id' => url('/').'#website'],
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => count($live),
            'itemListElement' => array_map(fn ($i, $p) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => route('websites.show', $p['slug']),
                'name' => $p['name'],
            ], array_keys($live), $live),
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [['label' => __('Work')]]])
    </div>

    <section class="page-head shell" aria-labelledby="page-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('Web projects') }}</span>

        <h1 class="t1 page-head-title optical-left" id="page-title">
            <span class="mask">{{ __('Built by hand.') }}</span>
            <span class="mask">{{ __('Live on the internet.') }}</span>
        </h1>

        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 3">{{ __('Three client sites you can open right now, and the design projects that came before them. Every screen below was designed in Figma and written by hand — no templates, no page builders.') }}</p>

        <div class="figures" data-reveal-group>
            <div>
                <span class="figure-value">{{ count($live) }}</span>
                <span class="figure-label">{{ __('live client sites') }}</span>
            </div>
            <div>
                <span class="figure-value">{{ count($designs) }}</span>
                <span class="figure-label">{{ __('design projects') }}</span>
            </div>
            <div>
                <span class="figure-value">1</span>
                <span class="figure-label">{{ __('person, start to finish') }}</span>
            </div>
        </div>
    </section>

    {{-- ── Live client work ─────────────────────────────────────── --}}
    <section id="live" class="section-tight shell" aria-labelledby="live-title">
        <header class="section-head">
            <div>
                <span class="status status-live">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ __('Live') }}
                </span>
                <h2 class="t2 section-head-title" id="live-title">{{ __('Shipped, and open to anyone.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Built for real businesses and running in public. Open any card to visit the site itself.') }}</p>
        </header>

        <div class="work-grid">
            @foreach ($live as $index => $project)
                @include('partials.project-card', [
                    'project' => $project,
                    'index' => $index,
                    'wide' => $index === 0,
                ])
            @endforeach
        </div>
    </section>

    {{-- ── Design projects ──────────────────────────────────────── --}}
    <section id="designs" class="section shell" aria-labelledby="designs-title">
        <header class="section-head">
            <div>
                <span class="status status-pending">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ __('Design only') }}
                </span>
                <h2 class="t2 section-head-title" id="designs-title">{{ __('Designed, not deployed.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Full design projects — every screen drawn and built, but never published to a public address. Shown here as work, not as running sites.') }}</p>
        </header>

        <div class="work-grid">
            @foreach ($designs as $index => $project)
                {{-- Ez a szekcio mindig a hajtas alatt van, ezert egyetlen kepe
                     sem kaphat elore-toltest: az +1 eltolas gondoskodik errol. --}}
                @include('partials.project-card', ['project' => $project, 'index' => $index + 1])
            @endforeach
        </div>
    </section>

    {{-- ── Upcoming ─────────────────────────────────────────────── --}}
    <section id="upcoming" class="section shell" aria-labelledby="upcoming-title">
        <header class="section-head">
            <div>
                <span class="status status-pending">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ __('Upcoming releases') }}
                </span>
                <h2 class="t2 section-head-title" id="upcoming-title">{{ __('On the bench right now.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Signed work that has not shipped yet. Names stay off the list until they are live.') }}</p>
        </header>

        <ol class="upcoming" data-reveal-group>
            @foreach ($upcoming as $entry)
                <li class="upcoming-row">
                    <span class="upcoming-code">{{ $entry['code'] }}</span>

                    <div>
                        <div class="upcoming-head">
                            <h3 class="t4">{{ $entry['sector'] }}</h3>
                            <span class="t8 ink-faint">{{ $entry['window'] }}</span>
                        </div>
                        <p class="t6 upcoming-teaser">{{ $entry['teaser'] }}</p>
                    </div>

                    <div class="upcoming-progress">
                        <span class="upcoming-stage">{{ $entry['stage'] }}</span>
                        <span class="upcoming-track" role="img" aria-label="{{ __(':percent% complete', ['percent' => $entry['progress']]) }}">
                            <span class="upcoming-fill" style="--upcoming-progress: {{ $entry['progress'] }}%"></span>
                        </span>
                    </div>
                </li>
            @endforeach
        </ol>

        <p class="t8 ink-faint" style="margin-top: var(--space-10)" data-reveal>{{ __('Two build slots open per quarter. If you want one of them, the earlier you ask the better.') }}</p>
    </section>

    <section class="section shell" aria-labelledby="contact-title">
        <div class="contact-grid">
            <div data-reveal>
                <span class="t8 ink-faint">{{ __('Start a project') }}</span>
                <h2 class="t2" id="contact-title" style="margin-top: var(--space-5)">{{ __('Ready to own something remarkable?') }}</h2>
                <p class="t5 contact-lede">{{ __('Tell me about your industry, budget, and timeline. I’ll match you with the right build — or scope something new from the ground up.') }}</p>

                <dl class="contact-channels">
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Email') }}</dt>
                        <dd><a href="mailto:hello@blckt.hu" class="contact-channel-value link-underline">hello@blckt.hu</a></dd>
                    </div>
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Phone') }}</dt>
                        <dd><a href="tel:+36302552432" class="contact-channel-value link-underline">+36 30 255 2432</a></dd>
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

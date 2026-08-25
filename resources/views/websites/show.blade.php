@extends('layout')

@section('title', $project['name'].' — '.$project['tagline'].' | blckt.')
@section('meta_description', $project['meta'])
@section('meta_image', $project['card'])
@section('og_type', 'article')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/compare.css') }}">
@endpush

@push('schema')
    {{-- A projekt mint munka: a valaszmotorok igy tudjak megnevezni, ki
         keszitette, mikor, mibol es milyen eredmennyel. --}}
    <script type="application/ld+json">
    {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        '@id' => route('websites.show', $project['slug']).'#project',
        'name' => $project['name'],
        'headline' => $project['tagline'],
        'description' => $project['problem'].' '.$project['value'],
        'url' => route('websites.show', $project['slug']),
        'image' => asset($project['card']),
        'dateCreated' => $project['year'],
        'inLanguage' => app()->getLocale(),
        'creator' => ['@id' => url('/').'#studio'],
        'author' => ['@id' => url('/').'#levente'],
        'about' => $project['sector'],
        'genre' => $project['type'],
        'keywords' => implode(', ', array_merge($project['tools'], [$project['sector'], $project['type']])),
    ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    @if ($project['video']['src'])
        {{-- Csak akkor allitunk videot, ha tenyleg van felvetel a lemezen. --}}
        <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => __('Play the :name walkthrough', ['name' => $project['name']]),
            'description' => $project['video']['caption'],
            'thumbnailUrl' => $project['video']['poster'] ? asset($project['video']['poster']) : null,
            'contentUrl' => asset($project['video']['src']),
            'uploadDate' => $project['year'].'-01-01',
        ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endpush

@section('content')
    <article>
        <div class="shell" style="padding-top: calc(72px + var(--space-10))">
            @include('partials.breadcrumbs', ['trail' => [
                ['label' => __('Work'), 'url' => url('/websites')],
                ['label' => $project['name']],
            ]])
        </div>

        {{-- ── Head ─────────────────────────────────────────────── --}}
        <section class="project-head shell">
            <span class="status {{ $project['is_live'] ? 'status-live' : 'status-pending' }}">
                <span class="status-dot" aria-hidden="true"></span>
                {{ $project['is_live'] ? __('Live') : __('Design only') }}
            </span>

            <div class="project-head-grid">
                <div>
                    {{-- Nem minden markanak van kulon logofajlja; ahol nincs,
                         a nev all a helyen. --}}
                    @if (! empty($project['logo']))
                        <img
                            src="{{ asset($project['logo']) }}"
                            alt="{{ $project['name'] }}"
                            class="project-logo {{ !empty($project['logo_invert']) ? 'project-logo-invert' : '' }}"
                            {!! \App\Support\Media::sizeAttrs($project['logo']) !!}
                            decoding="async"
                        >
                    @else
                        <p class="t4" style="margin-bottom: var(--space-8)">{{ $project['name'] }}</p>
                    @endif

                    <h1 class="t2 optical-left">
                        <span class="mask">{{ $project['tagline'] }}</span>
                    </h1>

                    <div class="project-actions" data-reveal style="--reveal-index: 2">
                        @include('partials.visit-link', ['project' => $project])
                        <a href="#start" class="btn">{{ __('Start something like this') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                    </div>
                </div>

                <dl class="facts" data-reveal style="--reveal-index: 1">
                    <div class="fact">
                        <dt>{{ __('Sector') }}</dt>
                        <dd>{{ $project['sector'] }}</dd>
                    </div>
                    <div class="fact">
                        <dt>{{ __('Type') }}</dt>
                        <dd>{{ $project['type'] }}</dd>
                    </div>
                    <div class="fact">
                        <dt>{{ __('Year') }}</dt>
                        <dd>{{ $project['year'] }}</dd>
                    </div>
                    @if (! empty($project['duration']))
                        <div class="fact">
                            <dt>{{ __('Timeline') }}</dt>
                            <dd>{{ $project['duration'] }}</dd>
                        </div>
                    @endif
                    <div class="fact">
                        <dt>{{ __('Built with') }}</dt>
                        <dd>{{ implode(', ', $project['tools']) }}</dd>
                    </div>
                    {{-- Ar csak a sajat munkaknal szerepel: amit egy ugyfel
                         fizetett, az az o uzleti adata. --}}
                    @if (! empty($project['price']))
                        <div class="fact">
                            <dt>{{ __('Investment') }}</dt>
                            <dd>{{ $project['price'] }}</dd>
                        </div>
                    @endif
                    <div class="fact">
                        <dt>{{ __('Address') }}</dt>
                        <dd>
                            @if (! empty($project['url']))
                                <a href="{{ $project['url'] }}" target="_blank" rel="noopener" class="link-underline">{{ preg_replace('#^https?://(www\.)?#', '', rtrim($project['url'], '/')) }}</a>
                            @else
                                {{ __('Not published') }}
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Csak ellenorizheto szamok kerulnek ide - olyan, ami magan az
             elo oldalon is ott all. --}}
        @if ($project['figures'])
            <section class="section-tight shell" aria-label="{{ __('Key figures') }}">
                <dl class="figures" data-reveal-group>
                    @foreach ($project['figures'] as $figure)
                        <div>
                            <dd class="figure-value">{{ $figure['value'] }}</dd>
                            <dt class="figure-label">{{ $figure['label'] }}</dt>
                        </div>
                    @endforeach
                </dl>
            </section>
        @endif

        @unless ($project['is_live'])
            {{-- Egyertelmu jelzes: ez a munka sosem kerult ki nyilvanos cimre. --}}
            <div class="shell">
                <p class="design-notice t6" data-reveal>
                    <strong>{{ __('This one is a design project.') }}</strong>
                    {{ __('Every screen below was designed and built, but the site was never published to a public address — so there is no live link to open.') }}
                </p>
            </div>
        @endunless

        {{-- ── Walkthrough ──────────────────────────────────────── --}}
        <section class="section shell" aria-labelledby="motion-title">
            <header class="section-head">
                <div>
                    <span class="t8 ink-faint">{{ __('In motion') }}</span>
                    <h2 class="t2 section-head-title" id="motion-title">{{ __('See it work.') }}</h2>
                </div>
            </header>

            <div data-reveal>
                @include('partials.project-video', ['video' => $project['video']])
            </div>
        </section>

        {{-- ── Problem / build / value ──────────────────────────── --}}
        <section class="section-tight shell" aria-labelledby="story-title">
            <h2 class="visually-hidden" id="story-title">{{ __('The project in three parts') }}</h2>

            <div class="story" data-reveal-group="loose">
                <div class="story-block">
                    <span class="story-index" aria-hidden="true">01</span>
                    <h3 class="t4 story-title">{{ __('The problem') }}</h3>
                    <p class="t6">{{ $project['problem'] }}</p>
                </div>

                <div class="story-block">
                    <span class="story-index" aria-hidden="true">02</span>
                    <h3 class="t4 story-title">{{ __('What I built') }}</h3>
                    <p class="t6">{{ $project['approach'] }}</p>

                    <ul class="highlights">
                        @foreach ($project['highlights'] as $highlight)
                            <li>{{ $highlight }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="story-block story-block-value">
                    <span class="story-index" aria-hidden="true">03</span>
                    <h3 class="t4 story-title">{{ __('What it was worth') }}</h3>
                    <p class="t6">{{ $project['value'] }}</p>
                </div>
            </div>
        </section>

        {{-- ── Before / after ───────────────────────────────────── --}}
        @if ($project['redesign'])
            <section class="section shell" aria-labelledby="compare-title">
                @include('partials.compare', [
                    'redesign' => $project['redesign'],
                    'eyebrow' => __('Before & after'),
                    'title' => __('The site they had, and the site they have now.'),
                ])
            </section>
        @endif

        {{-- ── Gallery ──────────────────────────────────────────── --}}
        @if ($project['gallery'])
            <section class="section-tight shell" aria-labelledby="gallery-title">
                <header class="section-head">
                    <div>
                        <span class="t8 ink-faint">{{ __('A closer look') }}</span>
                        <h2 class="t2 section-head-title" id="gallery-title">{{ __('Every screen, full length.') }}</h2>
                    </div>
                    <p class="t6 section-head-note">{{ __('Click any screen to open it full size.') }}</p>
                </header>

                <div class="gallery" data-reveal-group="tight">
                    @foreach ($project['gallery'] as $image)
                        <button type="button" class="gallery-item" aria-label="{{ __('Open :alt full size', ['alt' => $image['alt']]) }}">
                            <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}" {!! \App\Support\Media::sizeAttrs($image['src']) !!} loading="lazy" decoding="async">
                            <span class="gallery-expand" aria-hidden="true">&#10530;</span>
                        </button>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── Start ────────────────────────────────────────────── --}}
        <section id="start" class="section shell" aria-labelledby="start-title">
            <div class="contact-grid">
                <div data-reveal>
                    <span class="t8 ink-faint">{{ __('Start a project') }}</span>
                    <h2 class="t2" id="start-title" style="margin-top: var(--space-5)">{{ __('Want the same for your business?') }}</h2>
                    <p class="t5 contact-lede">{{ __('Tell me what your site has to achieve and roughly what you can spend. You get a fixed quote and a delivery date in writing — before anything starts.') }}</p>

                    <dl class="contact-channels">
                        <div class="contact-channel">
                            <dt class="contact-channel-label">{{ __('Email') }}</dt>
                            <dd><a href="mailto:hello@blckt.hu" class="contact-channel-value link-underline">hello@blckt.hu</a></dd>
                        </div>
                        <div class="contact-channel">
                            <dt class="contact-channel-label">{{ __('Phone') }}</dt>
                            <dd><a href="tel:+36302552432" class="contact-channel-value link-underline">+36 30 255 2432</a></dd>
                        </div>
                    </dl>
                </div>

                <div class="contact-card" data-reveal style="--reveal-index: 1">
                    @include('partials.contact-form')
                </div>
            </div>
        </section>

        {{-- ── Next ─────────────────────────────────────────────── --}}
        <nav class="section-tight shell" aria-label="{{ __('Next project') }}">
            <a href="{{ route('websites.show', $nextProject['slug']) }}" class="next-project" data-reveal>
                <span class="t8">{{ __('Next project') }}</span>
                <span class="t3 next-project-name">{{ $nextProject['name'] }}</span>
                <span class="next-project-arrow" aria-hidden="true">&#8594;</span>
            </a>
        </nav>
    </article>
@endsection

@push('scripts')
    <script src="{{ \App\Support\Asset::url('assets/js/compare.js') }}" defer></script>
    <script src="{{ \App\Support\Asset::url('assets/js/walkthrough.js') }}" defer></script>
@endpush

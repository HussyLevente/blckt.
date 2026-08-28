@extends('layout')

@section('title', __('Services & Pricing | blckt. — Web Design in Budapest'))
@section('meta_description', __('Three packages, fixed prices: 80 000 Ft for a landing page, 150 000 Ft for a four-page site, 350 000 Ft with a webshop and admin. Revamps from 70 000 Ft. Ready-made templates from 50 000 Ft.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@php
    use App\Support\Packages;

    $packages = Packages::services();
    $extras = Packages::extras();
    $templateFloorLabel = Packages::money($templateFloor);

    // Egy helyen tarolva, hogy a lathato GYIK es a strukturalt adat
    // biztosan ugyanazt mondja - kulonben a ketto elcsuszna egymastol.
    $faqs = [
        [
            'q' => __('How much does a website cost?'),
            'a' => __('Basic is :basic for a single page, Standard is :standard for up to four pages with forms, and Premium is :premium for up to six pages with a webshop and an admin panel. Revamping an existing site is :revamp. Anything that does not fit those, we scope and I quote. All prices exclude VAT and are fixed in writing before anything starts.', [
                'basic' => $packages[Packages::BASIC]['price_label'],
                'standard' => $packages[Packages::STANDARD]['price_label'],
                'premium' => $packages[Packages::PREMIUM]['price_label'],
                'revamp' => Packages::money(70000),
            ]),
        ],
        [
            'q' => __('How long does it take?'),
            'a' => __('Basic takes one to two days, Standard five days, Premium fourteen. The clock starts when the deposit clears and your content is with me — not when you first email. You get the delivery date in writing with the quote.'),
        ],
        [
            'q' => __('How does payment work?'),
            'a' => __('Half up front, half on handover. I invoice both halves through Számlázz.hu, so you get a proper Hungarian invoice you can put through your books. I start building when the first transfer lands, and you get the code once the second one does.'),
        ],
        [
            'q' => __('How many changes can I ask for?'),
            'a' => __('Three rounds of revisions are included in every package. In practice that is plenty — most of what people want changed is text and photos, and those are quick. If you want something rebuilt from scratch after the third round, that is new work and I will price it as such.'),
        ],
        [
            'q' => __('Do I need to buy anything myself?'),
            'a' => __('A domain, yes — it has to be in your name, not mine, so it stays yours no matter what. If the build needs a server rather than plain hosting, you buy that too. I will tell you exactly what to get and roughly what it costs before you spend anything, and I set both up for you.'),
        ],
        [
            'q' => __('What is the difference between Basic and the rest?'),
            'a' => __('Basic has no backend at all — no forms, no admin panel, no database. It is one page of text and pictures, which is exactly right for a lot of businesses and much faster to load. Standard and Premium both have a backend, which is what makes forms and editable content possible.'),
        ],
        [
            'q' => __('Do I own the site when it is done?'),
            'a' => __('Yes. You get the whole thing as a zip of raw code, and the domain and hosting are in your name. There is no platform you have to keep paying me for, and nothing breaks if we stop working together.'),
        ],
        [
            'q' => __('Can I edit the content myself?'),
            'a' => __('On Standard and Premium, yes — that is what the admin panel is for, and I walk you through it at handover. Basic has no admin by design, so text changes come to me. Structural layout changes are always my job.'),
        ],
        [
            'q' => __('Why not just use a website builder?'),
            'a' => __('Because a builder charges you monthly for something slower than what I hand you outright. If what you actually need is a template, I sell those too — from :price, written by hand. What a custom build adds is a site that looks like nobody else’s and does whatever you need, instead of whatever the plugin allows.', ['price' => $templateFloorLabel]),
        ],
        [
            'q' => __('What is the difference between a template and a custom build?'),
            'a' => __('The template is already designed, so you are paying for my time to fit your business into it — a day or two, and a good bit less money. The custom build is drawn for you from a blank page, takes longer, and nobody else will ever have it. The code is written by hand either way.'),
        ],
        [
            'q' => __('Do you work with clients outside Hungary?'),
            'a' => __('Yes. The work happens over email and video calls either way, and everything I build ships bilingual by default. Invoicing works across the EU without extra paperwork on your side.'),
        ],
    ];
@endphp

@push('schema')
    {{-- GYIK: ez az a blokk, amit a valaszmotorok kozvetlenul idezni tudnak. --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ], $faqs),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'OfferCatalog',
        'name' => __('Web design services'),
        'url' => route('services'),
        'provider' => ['@id' => url('/').'#studio'],
        'itemListElement' => array_values(array_merge(
            array_map(fn ($p) => [
                '@type' => 'Offer',
                'name' => $p['name'],
                'price' => (string) $p['price'],
                'priceCurrency' => 'HUF',
                'description' => $p['summary'],
            ], $packages),
            [[
                '@type' => 'Offer',
                'name' => __('Website template'),
                'price' => (string) $templateFloor,
                'priceCurrency' => 'HUF',
                'url' => route('templates.index'),
                'description' => __('A site that is already designed and built — your content goes in, it goes live in days.'),
            ]],
            array_map(fn ($e) => array_filter([
                '@type' => 'Offer',
                'name' => $e['name'],
                'price' => $e['price'] ? (string) $e['price'] : null,
                'priceCurrency' => $e['price'] ? 'HUF' : null,
                'description' => $e['summary'],
            ]), $extras),
            // A kiegeszitok is sajat ajanlatok: fix aruk van, tehat idezhetok.
            array_map(fn ($a) => [
                '@type' => 'Offer',
                'name' => $a['name'],
                'price' => (string) $a['price'],
                'priceCurrency' => 'HUF',
                'description' => $a['summary'],
            ], Packages::addOns())
        )),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [['label' => __('Services')]]])
    </div>

    <section class="page-head shell" aria-labelledby="services-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('Services & pricing') }}</span>
        <h1 class="t1 page-head-title optical-left" id="services-title">
            <span class="split-line" data-split="words">{{ __('Three packages.') }}</span>
            <span class="split-line" data-split="words">{{ __('Fixed prices.') }}</span>
        </h1>
        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 3">{{ __('No “contact us for pricing” wall and no hourly rate that quietly grows. Pick the package that fits, and that is the number — half before I start, half when you get the code.') }}</p>
    </section>

    {{-- ── The three packages ───────────────────────────────────── --}}
    <section class="section-tight shell" aria-labelledby="tiers-title">
        <h2 class="visually-hidden" id="tiers-title">{{ __('Packages') }}</h2>

        {{-- Harom oszlop, mert harom csomag van. A Standard ki van emelve:
             ez az, ami a legtobb kisvallalkozasnak tenyleg kell. --}}
        <div class="tiers tiers-3" data-reveal-group="loose">
            @foreach ($packages as $package)
                <article class="tier {{ $package['key'] === Packages::STANDARD ? 'tier-featured' : '' }}">
                    <span class="tier-index">{{ $package['index'] }}</span>

                    @if ($package['key'] === Packages::STANDARD)
                        <span class="tier-badge">{{ __('Most requested') }}</span>
                    @endif

                    <h3 class="t3 tier-name">{{ $package['name'] }}</h3>
                    <p class="tier-price">{{ $package['price_label'] }}</p>
                    <p class="t6">{{ $package['summary'] }}</p>

                    <ul class="tier-list">
                        @foreach ($package['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="tier-foot">
                        <span class="tier-timeline">{{ __('Done in :days', ['days' => $package['days_label']]) }}</span>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- ── The two that do not fit a box ────────────────────── --}}
        <div class="tiers" data-reveal-group="loose" style="margin-top: var(--space-8)">
            @foreach ($extras as $extra)
                <article class="tier">
                    <span class="tier-index">{{ $extra['index'] }}</span>
                    <h3 class="t3 tier-name">{{ $extra['name'] }}</h3>
                    <p class="tier-price">{{ $extra['price_label'] }}</p>
                    <p class="t6">{{ $extra['summary'] }}</p>

                    <ul class="tier-list">
                        @foreach ($extra['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="tier-foot">
                        <span class="tier-timeline">{{ $extra['days_label'] }}</span>
                        @if ($extra['key'] === 'revamp')
                            <a href="{{ route('websites.show', 'paradise') }}" class="link-arrow link-underline t8">{{ __('See a rebuild in full') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <p class="t8 ink-faint" style="margin-top: var(--space-10); max-width: 76ch" data-reveal>{{ __('All prices exclude VAT. Every package includes three rounds of revisions, and you get a fixed quote in writing before any work begins — I do not send surprise invoices.') }}</p>

        {{-- Ez a lap legdragabb pillanata: az ember most latta az arakat.
             Ha tul sok, jobb, ha itt megtudja, hogy van masik ut is, mint
             ha becsukja a lapot. --}}
        <aside class="crosssell" data-reveal aria-labelledby="crosssell-title">
            <div>
                <span class="t8 ink-faint">{{ __('The cheaper route') }}</span>
                <h3 class="t3" id="crosssell-title" style="margin-top: var(--space-4)">{{ __('More than you wanted to spend?') }}</h3>
                <p class="t6 crosssell-body">{{ __('Then take a website I have already built. The same three packages, in template form, from :price — you send your text and photos and I put your business inside. The catch is that you are not the only one who can have it.', ['price' => $templateFloorLabel]) }}</p>
            </div>

            <a href="{{ route('templates.index') }}" class="btn btn-solid crosssell-cta">
                {{ __('See the templates') }} <span class="arrow" aria-hidden="true">&#8594;</span>
            </a>
        </aside>
    </section>

    @include('partials.add-ons', ['context' => 'services'])

    <section class="statement">
        <div class="shell">
            <div class="statement-inner">
                <h2 class="t2 optical-left">
                    <span class="split-line" data-split="words">{{ __('No agency layers.') }}</span>
                    <span class="split-line" data-split="words">{{ __('No account manager.') }}</span>
                    <span class="split-line" data-split="words">{{ __('Just me.') }}</span>
                </h2>
                <span class="t8 statement-mark" data-reveal style="--reveal-index: 3">{{ __('That is the whole pitch.') }}</span>
            </div>
        </div>
    </section>

    {{-- ── How it works ─────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="process-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('Process') }}</span>
                <h2 class="t2 section-head-title" id="process-title">{{ __('Six steps, and you always know which one we are on.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Nothing here is a surprise, and nothing happens out of order. If a step is taking longer than it should, it is almost always step two.') }}</p>
        </header>

        @include('partials.process', ['flow' => 'services'])
    </section>

    {{-- ── FAQ ──────────────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="faq-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('Questions') }}</span>
                <h2 class="t2 section-head-title" id="faq-title">{{ __('The things everyone asks first.') }}</h2>
            </div>
        </header>

        <div class="accordion" data-reveal>
            @foreach ($faqs as $faq)
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">
                        {{ $faq['q'] }} <span class="accordion-icon" aria-hidden="true"></span>
                    </button>
                    <div class="accordion-panel"><div>
                        <p>{{ $faq['a'] }}</p>
                    </div></div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section shell" aria-labelledby="quote-title">
        <div class="contact-grid">
            <div data-reveal>
                <span class="t8 ink-faint">{{ __('Get a quote') }}</span>
                <h2 class="t2" id="quote-title" style="margin-top: var(--space-5)">{{ __('Tell me what you need.') }}</h2>
                <p class="t5 contact-lede">{{ __('Name the package you think fits, or just describe the business and let me tell you. Either way you get a fixed price and a delivery date back, usually the same day.') }}</p>

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
    <script src="{{ \App\Support\Asset::url('assets/js/accordion.js') }}" defer></script>
@endpush

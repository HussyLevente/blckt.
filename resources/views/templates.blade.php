@extends('layout')

@section('title', __('Website Templates | blckt. — Ready-Made Sites from 50 000 Ft'))
@section('meta_description', __('Six designs in three packages from 50 000 Ft. Try one with your own text and photos first — free, in your browser. Then I swap your content in and put it live in days, with the same hand-written code as a custom build.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/templates.css') }}">
@endpush

@php
    use App\Support\Packages;

    $cap = \App\Http\Controllers\TemplateController::LICENCE_CAP;
    $floorLabel = Packages::money($floor);

    // Egy helyen tarolva, hogy a lathato GYIK es a strukturalt adat
    // biztosan ugyanazt mondja - kulonben a ketto elcsuszna egymastol.
    $faqs = [
        [
            'q' => __('Is this a WordPress theme or a page builder?'),
            'a' => __('No. I design every one of these in Figma and write them by hand, the same way I write a custom build. Nothing is bought from a marketplace and there is no builder plugin underneath. The only difference from my custom work is that the design already existed before you asked for it.'),
        ],
        [
            'q' => __('Will another business have the same website as me?'),
            'a' => __('Possibly — that is what makes it cheap, so I will not pretend otherwise. I sell each design at most :cap times, and never twice in the same industry in the same town. If being the only one matters to you, buy the custom build instead.', ['cap' => $cap]),
        ],
        [
            'q' => __('What is the difference between the three packages?'),
            'a' => __('Basic is a single page with no backend at all — no forms, no admin, no database. Standard is up to four pages with working forms. Premium is up to six pages with a webshop or booking flow where the design calls for it, plus an admin panel so you can change prices and content yourself.'),
        ],
        [
            'q' => __('What can I actually change?'),
            'a' => __('Every word, every photo, the logo, the colours and the typeface. What stays is the layout — where things sit on the page and how it moves. If you want the layout changed, that is a custom build, and I will quote it as one.'),
        ],
        [
            'q' => __('How fast is it really?'),
            'a' => __('One day for Basic, two for Standard, five for Premium — counted from the moment your content is with me and the deposit has cleared. The clock does not start when you first email. Getting the text and photos together is usually the longest part, and it is entirely on your side.'),
        ],
        [
            'q' => __('How does payment work?'),
            'a' => __('Half up front, half on handover, both invoiced through Számlázz.hu. I start when the first transfer lands, and you get the code once the second one does. Three rounds of revisions are included, same as a custom build.'),
        ],
        [
            'q' => __('What do you need from me?'),
            'a' => __('A domain in your own name, your logo, your photos, and the text for each page. You get a written checklist with the quote so nothing is left to guesswork. If the text is not written yet, say so — I will send a fill-in-the-blanks version to work from.'),
        ],
        [
            'q' => __('Do I own it, or am I renting?'),
            'a' => __('You own it. You get the whole thing as a zip of raw code, and the domain and hosting are in your name, exactly as they are with a custom build. There is no monthly platform fee and nothing switches off if we stop working together.'),
        ],
        [
            'q' => __('Can I see it with my content before it goes live?'),
            'a' => __('Twice over. Before you order anything, the playground lets you put your own text and photos into a live template yourself, free and without signing up. Then once you order, you get a private preview link with everything in place. Nothing is published until you have looked at it and said go.'),
        ],
        [
            'q' => __('Does the playground send you my text and photos?'),
            'a' => __('No. It runs entirely in your browser and never uploads anything. Your photos stay on your device, and I cannot see what you typed — if you want to show me, send a screenshot. The trade-off is that your version only exists in that one browser: it will not follow you to another device.'),
        ],
    ];
@endphp

@push('schema')
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

    {{-- Minden sablon sajat ajanlat, sajat cimmel es fix arral - a
         valaszmotorok igy kozvetlenul tudjak idezni az arat. --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'OfferCatalog',
        'name' => __('Website templates'),
        'url' => route('templates.index'),
        'provider' => ['@id' => url('/').'#studio'],
        'numberOfItems' => count($templates),
        'itemListElement' => array_map(fn ($i, $t) => [
            '@type' => 'Offer',
            'position' => $i + 1,
            'name' => $t['name'].' — '.$t['tier_name'],
            'url' => $t['url'],
            'price' => (string) $t['price'],
            'priceCurrency' => 'HUF',
            'category' => $t['sector'],
            'description' => $t['tagline'],
            'availability' => $t['sold_out']
                ? 'https://schema.org/SoldOut'
                : 'https://schema.org/LimitedAvailability',
            'eligibleQuantity' => [
                '@type' => 'QuantitativeValue',
                'value' => $t['left'],
                'unitText' => 'licence',
            ],
        ], array_keys($templates), $templates),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [['label' => __('Templates')]]])
    </div>

    <section class="page-head shell" aria-labelledby="templates-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('Website templates') }}</span>

        <h1 class="t1 page-head-title optical-left" id="templates-title">
            <span class="mask">{{ __('Already built.') }}</span>
            <span class="mask">{{ __('Yours this week.') }}</span>
        </h1>

        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 3">{{ __('Six designs, in the same three packages as my custom work. :count of them you can open and click through right now; the rest are still being finished. You pick one, send your text and photos, and I put your business inside it — same code as my custom builds, the design just existed before you asked for it.', ['count' => $liveDemos]) }}</p>

        {{-- A szamok a kontrollerbol jonnek, nem kezzel beirva: egy uj sablon
             vagy egy arvaltozas igy nem hagy hazug allitast a lap tetejen. --}}
        <div class="figures" data-reveal-group>
            <div>
                <span class="figure-value">{{ count($templates) }}</span>
                <span class="figure-label">{{ trans_choice('design in the catalogue|designs in the catalogue', count($templates)) }}</span>
            </div>
            <div>
                <span class="figure-value">{{ $floorLabel }}</span>
                <span class="figure-label">{{ __('cheapest, all in') }}</span>
            </div>
            <div>
                <span class="figure-value">{{ $fastest }}</span>
                <span class="figure-label">{{ trans_choice('day at the fastest|days at the fastest', $fastest) }}</span>
            </div>
            {{-- Ez a szam a playground kontrolleretol jon, nem kezzel
                 beirva: amint egy uj demo szerkesztheto lesz, magatol nő -
                 es amig nem, addig nem allitunk tobbet a valosagnal. --}}
            <div>
                <span class="figure-value">{{ $playgrounds }}</span>
                <span class="figure-label">{{ __('you can edit yourself') }}</span>
            </div>
        </div>
    </section>

    {{-- ── Playground ───────────────────────────────────────────── --}}
    <section class="pg-band" aria-labelledby="pg-band-title">
        <div class="shell pg-band-inner">
            <div class="pg-band-copy" data-reveal>
                <span class="t8 pg-band-eyebrow">{{ __('Free, no sign-up') }}</span>

                <h2 class="t2 pg-band-title" id="pg-band-title">{{ __('Try one with your own words and photos.') }}</h2>

                <p class="t5 pg-band-lede">{{ __('Before you spend anything, open a template and rewrite it. Type over the headlines, drop in photos from your phone, set your brand colour, and look at it on a phone-width screen. It runs in your browser — nothing is uploaded, nothing is sent to me, and your version is still there when you come back.') }}</p>

                <div class="actions">
                    <a href="{{ route('playground.index') }}" class="btn btn-solid">{{ __('Open the playground') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                    <span class="t8 pg-band-count">{{ trans_choice(':count demo ready to edit|:count demos ready to edit', $playgrounds) }}</span>
                </div>
            </div>

            <ul class="pg-band-list t6" data-reveal style="--reveal-index: 1">
                <li>{{ __('Rewrite every headline, paragraph and button') }}</li>
                <li>{{ __('Swap any photo for one of your own') }}</li>
                <li>{{ __('Set your brand colour and watch the design follow it') }}</li>
                <li>{{ __('Check it at desktop, tablet and phone width') }}</li>
                <li>{{ __('Come back tomorrow — your changes are still there') }}</li>
            </ul>
        </div>
    </section>

    {{-- ── The three packages ───────────────────────────────────── --}}
    <section class="section-tight shell" aria-labelledby="packages-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('The packages') }}</span>
                <h2 class="t2 section-head-title" id="packages-title">{{ __('Three sizes. Same three as custom.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Every design below falls into one of these, and that is what sets its price. The only hard line is Basic: it has no backend at all.') }}</p>
        </header>

        <div class="tiers tiers-3" data-reveal-group="loose">
            @foreach ($tiers as $tier)
                <article class="tier {{ $tier['key'] === Packages::STANDARD ? 'tier-featured' : '' }}">
                    <span class="tier-index">{{ $tier['index'] }}</span>

                    @if ($tier['key'] === Packages::STANDARD)
                        <span class="tier-badge">{{ __('Most requested') }}</span>
                    @endif

                    <h3 class="t3 tier-name">{{ $tier['name'] }}</h3>
                    <p class="tier-price">{{ $tier['price_label'] }}</p>
                    <p class="t6">{{ $tier['summary'] }}</p>

                    <ul class="tier-list">
                        @foreach ($tier['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="tier-foot">
                        <span class="tier-timeline">{{ __('Live in :days', ['days' => $tier['days_label']]) }}</span>
                        {{-- Az egyedi ar mellette all, mert pont ez a lenyeg:
                             ugyanaz a csomag, kevesebb penzert. --}}
                        <span class="tier-timeline">{{ __('Custom: :price', ['price' => $services[$tier['key']]['price_label']]) }}</span>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ── The catalogue ────────────────────────────────────────── --}}
    <section id="catalogue" class="section shell" aria-labelledby="catalogue-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('The catalogue') }}</span>
                <h2 class="t2 section-head-title" id="catalogue-title">{{ __('Pick the one that already fits.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Each one was designed for a specific kind of business. Where a design says “live demo”, you can open the real thing and click around it — the rest are still being finished.') }}</p>
        </header>

        <div class="tpl-grid">
            @foreach ($templates as $index => $template)
                <article class="tpl-card" data-reveal>
                    {{-- A kep dekorativ: a nev, az agazat es az ar kozvetlenul
                         alatta all szovegkent, ezert a kepernyoolvaso nem
                         hallana tole semmi ujat. --}}
                    <a href="{{ $template['url'] }}" class="tpl-card-media" tabindex="-1" aria-hidden="true">
                        @include('partials.template-preview', [
                            'template' => $template,
                            'eager' => $index < 2,
                        ])
                        <span class="tpl-card-open">
                            {{ __('Look inside') }} <span class="arrow" aria-hidden="true">&#8594;</span>
                        </span>
                    </a>

                    <div class="tpl-card-body">
                        <p class="t8 tpl-card-meta">
                            <span class="tpl-card-tier">{{ $template['tier_name'] }}</span>
                            <span aria-hidden="true">/</span>
                            <span>{{ $template['sector'] }}</span>
                            <span aria-hidden="true">/</span>
                            <span>{{ trans_choice(':count page|:count pages', $template['pages']) }}</span>
                        </p>

                        <div class="tpl-card-head">
                            <h3 class="t3">
                                <a href="{{ $template['url'] }}" class="link-underline">{{ $template['name'] }}</a>
                            </h3>
                            <span class="tpl-card-price">{{ $template['price_label'] }}</span>
                        </div>

                        <p class="t6 tpl-card-tagline">{{ $template['tagline'] }}</p>

                        <div class="tpl-card-foot">
                            @include('partials.licence', ['template' => $template])

                            {{-- Ahol all elo demo, ott a playground a legerosebb
                                 link a kartyan: a tobbi allitast az bizonyitja
                                 be, mert ott a latogato sajat tartalma kerul a
                                 tervbe. --}}
                            @if ($template['has_demo'])
                                <a href="{{ $template['demos'][0]['playground'] }}" class="link-arrow link-underline t8">
                                    {{ __('Try it yourself') }} <span class="arrow" aria-hidden="true">&#8594;</span>
                                </a>
                            @else
                                <a href="{{ $template['url'] }}" class="link-arrow link-underline t8">
                                    {{ __('Look inside') }} <span class="arrow" aria-hidden="true">&#8594;</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <p class="t8 ink-faint" style="margin-top: var(--space-12); max-width: 76ch" data-reveal>{{ __('More are being drawn now. If your industry is not covered yet, tell me which one you were looking for — that is genuinely how I decide what to build next.') }}</p>
    </section>

    {{-- ── The honest part ──────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="ledger-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('The honest part') }}</span>
                <h2 class="t2 section-head-title" id="ledger-title">{{ __('What changes. What doesn’t.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('You should know exactly what you are buying before you pay for it, not after. So here is the whole trade in two columns.') }}</p>
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

            <p class="t6 ledger-note">{{ __('That last line in the right-hand column is the real price of a template, and it is the only one I cannot discount. Everything else on this page is a bargain; that one is a compromise. If it bothers you even slightly, the custom build is one page away, and I would rather sell you that once than sell you this twice.') }}</p>
        </div>
    </section>

    {{-- ── Statement ────────────────────────────────────────────── --}}
    <section class="statement">
        <div class="shell">
            <div class="statement-inner">
                <h2 class="t2 optical-left">
                    <span class="mask">{{ __('Still my design.') }}</span>
                    <span class="mask">{{ __('Still my code.') }}</span>
                    <span class="mask">{{ __('Just not only yours.') }}</span>
                </h2>
                <span class="t8 statement-mark" data-reveal style="--reveal-index: 3">{{ __('That is the entire difference.') }}</span>
            </div>
        </div>
    </section>

    {{-- ── Template or custom ───────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="versus-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('Which one') }}</span>
                <h2 class="t2 section-head-title" id="versus-title">{{ __('Template or custom?') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('No checkmarks — those hide the interesting part. Read the two columns and pick the one you can live with.') }}</p>
        </header>

        <div class="versus-scroll" data-reveal>
            <table class="versus">
                <caption class="visually-hidden">{{ __('A row-by-row comparison of the template service and a custom build.') }}</caption>
                <thead>
                    <tr>
                        <th scope="col"><span class="visually-hidden">{{ __('Aspect') }}</span></th>
                        <th scope="col">{{ __('Template') }}</th>
                        <th scope="col">{{ __('Custom build') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        [__('The design'), __('One of six, already drawn'), __('Drawn for your business and nobody else')],
                        [__('The layout'), __('Fixed — content goes in the slots'), __('Whatever your content actually needs')],
                        [__('Exclusivity'), __('Up to :cap businesses share it', ['cap' => $cap]), __('Yours alone, permanently')],
                        [__('Basic'), $tiers[Packages::BASIC]['price_label'], $services[Packages::BASIC]['price_label']],
                        [__('Standard'), $tiers[Packages::STANDARD]['price_label'], $services[Packages::STANDARD]['price_label']],
                        [__('Premium'), $tiers[Packages::PREMIUM]['price_label'], $services[Packages::PREMIUM]['price_label']],
                        [__('Live in'), __(':from–:to days', ['from' => $fastest, 'to' => 5]), __('2 to 14 days')],
                        [__('The code'), __('Written by hand'), __('Written by hand')],
                        [__('Revisions'), __('Three rounds'), __('Three rounds')],
                        [__('Who owns it'), __('You do — raw code, in a zip'), __('You do — raw code, in a zip')],
                        [__('Best when'), __('You need to be online, and soon'), __('The website is how people judge you')],
                    ] as [$aspect, $tpl, $custom])
                        <tr>
                            <th scope="row">{{ $aspect }}</th>
                            <td>{{ $tpl }}</td>
                            <td class="versus-custom">{{ $custom }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="actions actions-spaced" data-reveal>
            <a href="{{ route('services') }}" class="btn">{{ __('See custom pricing') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
        </div>
    </section>

    {{-- ── How it works ─────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="process-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('How it works') }}</span>
                <h2 class="t2 section-head-title" id="process-title">{{ __('Six steps, and two of them are yours.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Nothing here is a surprise, and nothing happens out of order. If a step is taking longer than it should, it is almost always step four.') }}</p>
        </header>

        @include('partials.process', ['flow' => 'templates'])
    </section>

    {{-- ── Always included ──────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="included-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('In every one') }}</span>
                <h2 class="t2 section-head-title" id="included-title">{{ __('The boring things, done anyway.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Cheap should mean a smaller job, not a worse one. None of this is an upsell.') }}</p>
        </header>

        <div class="tpl-columns" data-reveal>
            <ul class="tpl-includes t6">
                <li>{{ __('Built mobile-first and tested on real phones, not just a resized browser window') }}</li>
                <li>{{ __('Hungarian and English versions if you want both — the switch is already built') }}</li>
                <li>{{ __('Loads fast: compressed images, no builder overhead, no tracker soup') }}</li>
                <li>{{ __('Sitemap, meta tags, structured data and social preview cards') }}</li>
            </ul>
            <ul class="tpl-includes t6">
                <li>{{ __('Imprint, privacy policy and cookie notice that satisfy Hungarian law') }}</li>
                <li>{{ __('Readable at real sizes, keyboard-navigable, works with a screen reader') }}</li>
                <li>{{ __('Domain and hosting set up in your name, with the logins handed to you') }}</li>
                <li>{{ __('Three rounds of revisions, same as a custom build') }}</li>
            </ul>
        </div>
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

    {{-- ── Closing ──────────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="claim-title">
        <div class="contact-grid">
            <div data-reveal>
                <span class="t8 ink-faint">{{ __('Claim one') }}</span>
                <h2 class="t2" id="claim-title" style="margin-top: var(--space-5)">{{ __('Tell me which one.') }}</h2>
                <p class="t5 contact-lede">{{ __('Name the design and roughly when you want to be live. I will send the fixed price, the delivery date and the content checklist back — usually the same day.') }}</p>

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

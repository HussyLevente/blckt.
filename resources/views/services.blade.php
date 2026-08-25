@extends('layout')

@section('title', __('Services & Pricing | blckt. — Web Design in Budapest'))
@section('meta_description', __('Landing pages from 180 000 Ft, multi-page sites from 280 000 Ft, webshops from 450 000 Ft. Real prices, fixed written quotes, no discovery-call gate.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@php
    // Egy helyen tarolva, hogy a lathato GYIK es a strukturalt adat
    // biztosan ugyanazt mondja - kulonben a ketto elcsuszna egymastol.
    $faqs = [
        [
            'q' => __('How much does a website cost?'),
            'a' => __('A landing page starts at 180 000 Ft, a multi-page website at 280 000 Ft, and a webshop at 450 000 Ft. Redesigns are quoted per project. All prices exclude VAT, and you get a fixed written quote before any work begins.'),
        ],
        [
            'q' => __('How long does it take?'),
            'a' => __('A landing page takes one to two weeks, a multi-page site two to four weeks, and a webshop four to six weeks. You get a delivery date in writing with the quote.'),
        ],
        [
            'q' => __('Do I own the site when it is done?'),
            'a' => __('Yes. The domain, the hosting account and the code are all in your name. There is no platform you have to keep paying me for, and nothing breaks if we stop working together.'),
        ],
        [
            'q' => __('Can I edit the content myself?'),
            'a' => __('For anything that changes regularly — prices, products, posts, opening hours — yes, and I walk you through it at handover. For structural layout changes you send me a message and I do it.'),
        ],
        [
            'q' => __('What happens after launch?'),
            'a' => __('Thirty days of bug fixes are included at no extra cost. After that you can either send work over as it comes up, or take a monthly retainer if you would rather have a fixed cost.'),
        ],
        [
            'q' => __('Why not just use a website builder?'),
            'a' => __('If a template genuinely fits your business, use one — I would rather tell you that than take the money. What you get here is a site that looks like nobody else’s, loads faster because there is no builder overhead, and can do whatever you need instead of whatever the plugin allows.'),
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
        'itemListElement' => [
            ['@type' => 'Offer', 'name' => __('Landing page'), 'price' => '180000', 'priceCurrency' => 'HUF', 'description' => __('One page built around a single conversion.')],
            ['@type' => 'Offer', 'name' => __('Multi-page website'), 'price' => '280000', 'priceCurrency' => 'HUF', 'description' => __('Up to eight designed pages, bilingual, animated.')],
            ['@type' => 'Offer', 'name' => __('Webshop'), 'price' => '450000', 'priceCurrency' => 'HUF', 'description' => __('Catalogue, cart, checkout and custom product flows.')],
        ],
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
            <span class="mask">{{ __('What it costs.') }}</span>
            <span class="mask">{{ __('What you get.') }}</span>
        </h1>
        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 3">{{ __('These are real ranges from real projects, not a “contact us for pricing” wall. The final number depends on scope — but you should be able to tell before you email me whether we are in the same ballpark.') }}</p>
    </section>

    <section class="section-tight shell" aria-labelledby="tiers-title">
        <h2 class="visually-hidden" id="tiers-title">{{ __('Packages') }}</h2>

        <div class="tiers" data-reveal-group="loose">
            <article class="tier">
                <span class="tier-index">01</span>
                <h3 class="t3 tier-name">{{ __('Landing page') }}</h3>
                <p class="tier-price">{{ __('from 180 000 Ft') }}</p>
                <p class="t6">{{ __('One page that does one job: get the visitor to act. Custom design, real code, built around whatever your single most important conversion is.') }}</p>
                <ul class="tier-list">
                    <li>{{ __('Custom design in Figma, no template') }}</li>
                    <li>{{ __('Mobile-first, tested on real devices') }}</li>
                    <li>{{ __('Contact or booking form wired up') }}</li>
                    <li>{{ __('Basic SEO and social preview cards') }}</li>
                </ul>
                <div class="tier-foot">
                    <span class="tier-timeline">{{ __('Typically 1–2 weeks') }}</span>
                </div>
            </article>

            <article class="tier tier-featured">
                <span class="tier-index">02</span>
                <span class="tier-badge">{{ __('Most requested') }}</span>
                <h3 class="t3 tier-name">{{ __('Multi-page website') }}</h3>
                <p class="tier-price">{{ __('from 280 000 Ft') }}</p>
                <p class="t6">{{ __('The full site: home, services, about, contact, plus whatever your business actually needs. Everything designed together so it reads as one thing, not five templates stapled together.') }}</p>
                <ul class="tier-list">
                    <li>{{ __('Up to 8 designed pages') }}</li>
                    <li>{{ __('Scroll animations and page transitions') }}</li>
                    <li>{{ __('Multi-language support (EN / HU)') }}</li>
                    <li>{{ __('Sitemap, meta tags, structured markup') }}</li>
                    <li>{{ __('Two rounds of revisions included') }}</li>
                </ul>
                <div class="tier-foot">
                    <span class="tier-timeline">{{ __('Typically 2–4 weeks') }}</span>
                </div>
            </article>

            <article class="tier">
                <span class="tier-index">03</span>
                <h3 class="t3 tier-name">{{ __('Webshop') }}</h3>
                <p class="tier-price">{{ __('from 450 000 Ft') }}</p>
                <p class="t6">{{ __('A storefront built around your product photography instead of a stock grid — product pages, cart, checkout, and any custom flow your catalogue needs.') }}</p>
                <ul class="tier-list">
                    <li>{{ __('Product catalogue and filtering') }}</li>
                    <li>{{ __('Cart and checkout flow') }}</li>
                    <li>{{ __('Custom flows (personalisation, variants)') }}</li>
                    <li>{{ __('Admin handover and a short walkthrough') }}</li>
                </ul>
                <div class="tier-foot">
                    <span class="tier-timeline">{{ __('Typically 4–6 weeks') }}</span>
                </div>
            </article>

            <article class="tier">
                <span class="tier-index">04</span>
                <h3 class="t3 tier-name">{{ __('Redesign') }}</h3>
                <p class="tier-price">{{ __('quoted per project') }}</p>
                <p class="t6">{{ __('You already have a site and it is holding you back. I rebuild it — keeping what works, replacing what does not, and keeping your existing URLs alive so you do not lose your search rankings.') }}</p>
                <ul class="tier-list">
                    <li>{{ __('Audit of the current site first') }}</li>
                    <li>{{ __('Redirects so existing links keep working') }}</li>
                    <li>{{ __('Content migrated, not retyped') }}</li>
                </ul>

                <div class="tier-foot">
                    <a href="{{ route('websites.show', 'paradise') }}" class="link-arrow link-underline t8">{{ __('See a rebuild in full') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                </div>
            </article>
        </div>

        <p class="t8 ink-faint" style="margin-top: var(--space-10); max-width: 76ch" data-reveal>{{ __('All prices are indicative starting points, exclusive of VAT. You get a fixed quote in writing before any work begins — I do not send surprise invoices.') }}</p>
    </section>

    <section class="statement">
        <div class="shell">
            <div class="statement-inner">
                <h2 class="t2 optical-left">
                    <span class="mask">{{ __('No agency layers.') }}</span>
                    <span class="mask">{{ __('No account manager.') }}</span>
                    <span class="mask">{{ __('Just me.') }}</span>
                </h2>
                <span class="t8 statement-mark" data-reveal style="--reveal-index: 3">{{ __('That is the whole pitch.') }}</span>
            </div>
        </div>
    </section>

    <section class="section shell" aria-labelledby="process-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('Process') }}</span>
                <h2 class="t2 section-head-title" id="process-title">{{ __('Five steps, and you always know which one we are on.') }}</h2>
            </div>
        </header>

        <ol class="steps" data-reveal-group>
            @foreach ([
                ['01', __('Call'), __('Twenty minutes. You tell me what the business does and what the site has to achieve. If I am not the right fit, I say so here rather than three weeks in.')],
                ['02', __('Quote'), __('A written scope with a fixed price and a delivery date. Nothing starts until you have said yes to it in writing.')],
                ['03', __('Design'), __('I design the key screens in Figma first. You see the real layout with your real content before a single line of code exists.')],
                ['04', __('Build'), __('Written by hand, not assembled in a page builder. You get a live preview link from day one and can watch it come together.')],
                ['05', __('Launch'), __('Domain, hosting, analytics, and a walkthrough of how to edit what you need to edit. Thirty days of fixes included afterwards.')],
            ] as [$n, $title, $body])
                <li class="step">
                    <span class="step-number">{{ $n }}</span>
                    <h3 class="t4">{{ $title }}</h3>
                    <p class="t6">{{ $body }}</p>
                </li>
            @endforeach
        </ol>
    </section>

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
                <p class="t5 contact-lede">{{ __('Rough idea is fine. Tell me the business, roughly what you can spend, and when you would like it live — I will come back with what is realistic.') }}</p>

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

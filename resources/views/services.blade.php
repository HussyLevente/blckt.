@extends('layout')

@section('title', __('Services & Pricing | blckt. — Web Design in Budapest'))
@section('meta_description', __('Landing pages, multi-page sites, webshops and redesigns — what I build, how the process runs, and what it costs. Indicative pricing up front, no discovery-call gatekeeping.'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/websites.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
@endpush

@section('content')
    <section class="content-section services-hero">
        <span class="section-eyebrow" data-anim="fade">{{ __('Services') }}</span>
        <h1 class="services-hero-title"><span class="anim-mask">{{ __('What it costs.') }}</span><span class="anim-mask">{{ __('What you get.') }}</span></h1>
        <p class="services-hero-text" data-anim="up" data-anim-delay="200">{{ __('Prices on this page are real ranges from real projects, not a “contact us for pricing” wall. The final number depends on scope — but you should be able to tell before you email me whether we are in the same ballpark.') }}</p>
    </section>

    <section class="content-section services-tiers">
        <div class="services-tier-grid" data-anim-stagger="110">
            <article class="services-tier" data-lift>
                <span class="services-tier-index">01</span>
                <h2 class="services-tier-name">{{ __('Landing page') }}</h2>
                <p class="services-tier-price">{{ __('from 180 000 Ft') }}</p>
                <p class="services-tier-text">{{ __('One page that does one job: get the visitor to act. Custom design, real code, built around whatever your single most important conversion is.') }}</p>
                <ul class="services-tier-list">
                    <li>{{ __('Custom design in Figma, no template') }}</li>
                    <li>{{ __('Mobile-first, tested on real devices') }}</li>
                    <li>{{ __('Contact or booking form wired up') }}</li>
                    <li>{{ __('Basic SEO and social preview cards') }}</li>
                </ul>
                <span class="services-tier-timeline">{{ __('Typically 1–2 weeks') }}</span>
            </article>

            <article class="services-tier services-tier-featured" data-lift>
                <span class="services-tier-index">02</span>
                <span class="services-tier-badge">{{ __('Most requested') }}</span>
                <h2 class="services-tier-name">{{ __('Multi-page website') }}</h2>
                <p class="services-tier-price">{{ __('from 280 000 Ft') }}</p>
                <p class="services-tier-text">{{ __('The full site: home, services, about, contact, plus whatever your business actually needs. Everything designed together so it reads as one thing, not five templates stapled together.') }}</p>
                <ul class="services-tier-list">
                    <li>{{ __('Up to 8 designed pages') }}</li>
                    <li>{{ __('Scroll animations and page transitions') }}</li>
                    <li>{{ __('Multi-language support (EN / HU)') }}</li>
                    <li>{{ __('Sitemap, meta tags, structured markup') }}</li>
                    <li>{{ __('Two rounds of revisions included') }}</li>
                </ul>
                <span class="services-tier-timeline">{{ __('Typically 2–4 weeks') }}</span>
            </article>

            <article class="services-tier" data-lift>
                <span class="services-tier-index">03</span>
                <h2 class="services-tier-name">{{ __('Webshop') }}</h2>
                <p class="services-tier-price">{{ __('from 450 000 Ft') }}</p>
                <p class="services-tier-text">{{ __('A storefront built around your product photography instead of a stock grid — product pages, cart, checkout, and any custom flow your catalogue needs.') }}</p>
                <ul class="services-tier-list">
                    <li>{{ __('Product catalogue and filtering') }}</li>
                    <li>{{ __('Cart and checkout flow') }}</li>
                    <li>{{ __('Custom flows (personalisation, variants)') }}</li>
                    <li>{{ __('Admin handover and a short walkthrough') }}</li>
                </ul>
                <span class="services-tier-timeline">{{ __('Typically 4–6 weeks') }}</span>
            </article>

            <article class="services-tier" data-lift>
                <span class="services-tier-index">04</span>
                <h2 class="services-tier-name">{{ __('Redesign') }}</h2>
                <p class="services-tier-price">{{ __('quoted per project') }}</p>
                <p class="services-tier-text">{{ __('You already have a site and it is holding you back. I rebuild it — keeping what works, replacing what does not, and keeping your existing URLs alive so you do not lose your search rankings.') }}</p>
                <ul class="services-tier-list">
                    <li>{{ __('Audit of the current site first') }}</li>
                    <li>{{ __('Redirects so existing links keep working') }}</li>
                    <li>{{ __('Content migrated, not retyped') }}</li>
                    <li>{{ __('Before/after comparison at handover') }}</li>
                </ul>
                <a href="/redesigns" class="services-tier-link anim-underline">{{ __('See before & after') }} <span aria-hidden="true">&#8594;</span></a>
            </article>
        </div>

        <p class="services-disclaimer" data-anim="fade">{{ __('All prices are indicative starting points, exclusive of VAT. You get a fixed quote in writing before any work begins — I do not send surprise invoices.') }}</p>
    </section>

    <div class="manifesto-pin">
        <section class="manifesto-section">
            <div class="manifesto-content">
                <h2 class="manifesto-text">{{ __('No agency layers. No account manager. Just me.') }}</h2>
                <span class="manifesto-brand">{{ __('That is the whole pitch.') }}</span>
            </div>
        </section>
    </div>

    <section class="content-section services-process">
        <span class="section-eyebrow" data-anim="fade">{{ __('Process') }}</span>
        <h2 class="services-section-title" data-anim="up">{{ __('Five steps, and you always know which one we are on.') }}</h2>

        <ol class="services-steps" data-anim-stagger="130">
            <li class="services-step">
                <span class="services-step-number">01</span>
                <h3 class="services-step-title">{{ __('Call') }}</h3>
                <p>{{ __('Twenty minutes. You tell me what the business does and what the site has to achieve. If I am not the right fit, I say so here rather than three weeks in.') }}</p>
            </li>
            <li class="services-step">
                <span class="services-step-number">02</span>
                <h3 class="services-step-title">{{ __('Quote') }}</h3>
                <p>{{ __('A written scope with a fixed price and a delivery date. Nothing starts until you have said yes to it in writing.') }}</p>
            </li>
            <li class="services-step">
                <span class="services-step-number">03</span>
                <h3 class="services-step-title">{{ __('Design') }}</h3>
                <p>{{ __('I design the key screens in Figma first. You see the real layout with your real content before a single line of code exists.') }}</p>
            </li>
            <li class="services-step">
                <span class="services-step-number">04</span>
                <h3 class="services-step-title">{{ __('Build') }}</h3>
                <p>{{ __('Written by hand, not assembled in a page builder. You get a live preview link from day one and can watch it come together.') }}</p>
            </li>
            <li class="services-step">
                <span class="services-step-number">05</span>
                <h3 class="services-step-title">{{ __('Launch') }}</h3>
                <p>{{ __('Domain, hosting, analytics, and a walkthrough of how to edit what you need to edit. Thirty days of fixes included afterwards.') }}</p>
            </li>
        </ol>
    </section>

    <section class="content-section services-faq">
        <span class="section-eyebrow" data-anim="fade">{{ __('Questions') }}</span>
        <div class="values-grid">
            <h2 class="values-title" data-anim="up">{{ __('The things everyone asks first.') }}</h2>
            <div class="values-accordion" data-anim="up" data-anim-delay="120">
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Do I own the site when it is done?') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('Yes. The domain, the hosting account and the code are all in your name. There is no platform you have to keep paying me for, and nothing breaks if we stop working together.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Can I edit the content myself?') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('For anything that changes regularly — prices, products, posts, opening hours — yes, and I walk you through it at handover. For structural layout changes you send me a message and I do it.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('What happens after launch?') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('Thirty days of bug fixes are included at no extra cost. After that you can either send work over as it comes up, or take a monthly retainer if you would rather have a fixed cost.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Why not just use a website builder?') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('If a template genuinely fits your business, use one — I would rather tell you that than take the money. What you get here is a site that looks like nobody else’s, loads faster because there is no builder overhead, and can do whatever you need it to instead of whatever the plugin allows.') }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">{{ __('Do you work with clients outside Hungary?') }} <span class="accordion-icon">+</span></button>
                    <div class="accordion-panel">
                        <p>{{ __('Yes. The work happens over email and video calls either way, and everything I build ships bilingual by default. Invoicing works across the EU without any extra paperwork on your side.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section contact-cta-section reveal">
        <div class="contact-grid">
            <div class="contact-left">
                <h2 class="contact-title">{{ __('Tell me what you need.') }}</h2>
                <p class="contact-text">{{ __('Rough idea is fine. Tell me the business, roughly what you can spend, and when you would like it live — I will come back with what is realistic.') }}</p>
                <a href="mailto:hello@blckt.hu" class="contact-email">{{ __('Email: hello@blckt.hu') }}</a>
                <a href="https://wa.me/36302552432" target="_blank" rel="noopener" class="contact-email contact-whatsapp">{{ __('Message on WhatsApp') }}</a>
                <p class="contact-response">{{ __('Response time: 24 Hours') }}</p>
            </div>
            <div class="contact-right">
                @include('partials.contact-form')
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/scroll-story.js') }}"></script>
    <script src="{{ asset('assets/js/about.js') }}"></script>
@endpush

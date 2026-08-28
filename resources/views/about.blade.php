@extends('layout')

@section('title', __('About | blckt. — Levente Hussy, Budapest'))
@section('meta_description', __('blckt. is Levente Hussy, a solo designer and developer in Budapest. Custom websites and premium streetwear, designed and built by one person — no agency, no templates.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'url' => url('/about'),
        'isPartOf' => ['@id' => url('/').'#website'],
        'mainEntity' => ['@id' => url('/').'#levente'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [['label' => __('About')]]])
    </div>

    <section class="page-head shell" aria-labelledby="about-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('About') }}</span>
        <h1 class="t1 page-head-title optical-left" id="about-title">
            <span class="split-line" data-split="words">{{ __('I am blckt.') }}</span>
        </h1>
        <p class="t5 page-head-lede" data-reveal style="--reveal-index: 2">{{ __('A solo studio from Hungary building clothes and websites that don’t apologize for existing.') }}</p>

        <div class="figures" data-reveal-group>
            <div>
                <span class="figure-value stat-number" data-target="2026" data-suffix="">0</span>
                <span class="figure-label">{{ __('founded') }}</span>
            </div>
            <div>
                <span class="figure-value stat-number" data-target="12" data-suffix="+">0</span>
                <span class="figure-label">{{ __('websites designed') }}</span>
            </div>
            <div>
                <span class="figure-value stat-number" data-target="8" data-suffix="">0</span>
                <span class="figure-label">{{ __('clothing designs') }}</span>
            </div>
        </div>
    </section>

    <section class="section shell" aria-labelledby="origin-title">
        <div class="split">
            <h2 class="t3" id="origin-title" data-reveal>{{ __('Started in a bedroom. Still feels that way.') }}</h2>
            <div class="split-body" data-reveal style="--reveal-index: 1">
                <p class="t6">{{ __('blckt. launched in 2026 as an experiment — what happens if you apply the same obsessive attention to detail to a t-shirt that you’d apply to a software product?') }}</p>
                <p class="t6">{{ __('The answer turned out to be: something people actually want. So I kept going. The clothing led to the websites. The websites led back to better clothing. Now it’s both, permanently, by design.') }}</p>
                <p class="t6">{{ __('The name blckt. is intentionally stripped. No vowels, no fuss. A mark, not a word.') }}</p>
            </div>
        </div>
    </section>

    <section class="section-tight shell" aria-labelledby="what-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('What I do') }}</span>
                <h2 class="t2 section-head-title" id="what-title">{{ __('Two halves of one studio.') }}</h2>
            </div>
        </header>

        <div class="about-cards" data-reveal-group="loose">
            <a href="/websites" class="about-card">
                <span class="frame frame-zoom" data-unveil>
                    <img src="{{ asset('assets/imgs/websites/juiced/juiced_after.jpg') }}" alt="" {!! \App\Support\Media::sizeAttrs('assets/imgs/websites/juiced/juiced_after.jpg') !!} loading="lazy" decoding="async">
                </span>
                <div class="about-card-body">
                    <span class="t8 ink-faint">01 — {{ __('Websites') }}</span>
                    <h3 class="t4" style="margin-top: var(--space-4)">{{ __('Websites people actually want to visit.') }}</h3>
                    {{-- A "nincs sablon" allitas a sablon-szolgaltatas ota
                         felreertheto lenne; ez a kartya a kifejezetten egyedi
                         munkarol szol, ezert a mondat is arra szukul. --}}
                    <p class="t6">{{ __('Custom sites for brands that care about the details. Nothing off the shelf, no bloat. Designed in Figma, written by hand, handed over as something you are proud to share.') }}</p>
                    <span class="link-arrow link-underline t8">{{ __('See the work') }} <span class="arrow" aria-hidden="true">&#8594;</span></span>
                </div>
            </a>

            <a href="/clothing" class="about-card">
                <span class="frame frame-zoom" data-unveil>
                    <img src="{{ asset('assets/imgs/brand/blckt_coll_main.webp') }}" alt="" {!! \App\Support\Media::sizeAttrs('assets/imgs/brand/blckt_coll_main.webp') !!} loading="lazy" decoding="async">
                </span>
                <div class="about-card-body">
                    <span class="t8 ink-faint">02 — {{ __('Clothing') }}</span>
                    <h3 class="t4" style="margin-top: var(--space-4)">{{ __('Apparel that actually says something.') }}</h3>
                    <p class="t6">{{ __('A micro-collection of premium oversized tees. Graphic-led, culture-literate, Hungarian-made. Every piece designed in-house — no outsourced taste.') }}</p>
                    <span class="link-arrow link-underline t8">{{ __('See the collection') }} <span class="arrow" aria-hidden="true">&#8594;</span></span>
                </div>
            </a>
        </div>
    </section>

    <section class="section shell" aria-labelledby="values-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('Values') }}</span>
                <h2 class="t2 section-head-title" id="values-title">{{ __('Two things I won’t compromise on.') }}</h2>
            </div>
        </header>

        <div class="accordion" data-reveal>
            @foreach ([
                [__('No filler.'), __('If it doesn’t earn its place on the page or on the shirt, it doesn’t ship. No stock photography, no lorem ipsum, no decoration for decoration’s sake.')],
                [__('Built to last.'), __('Heavyweight cotton. Real code, not page-builder duct tape. I design for the fifth wash and the fifth deploy, not just the first screenshot.')],
            ] as [$title, $body])
                <div class="accordion-item">
                    <button type="button" class="accordion-trigger">
                        {{ $title }} <span class="accordion-icon" aria-hidden="true"></span>
                    </button>
                    <div class="accordion-panel"><div>
                        <p>{{ $body }}</p>
                    </div></div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section shell" aria-labelledby="founder-title">
        <div class="founder">
            {{-- A portre a sotet oldalon is megall: fekete hatteru felvetel,
                 ezert nem kap kulon keretet, csak a racs tartja meg. --}}
            <figure class="founder-portrait" data-reveal>
                <img
                    src="{{ asset('assets/imgs/portrait.webp') }}"
                    alt="{{ __('Levente Hussy, the founder of blckt.') }}"
                    {!! \App\Support\Media::sizeAttrs('assets/imgs/portrait.webp') !!}
                    loading="lazy"
                    decoding="async"
                >
            </figure>

            <div class="founder-body" data-reveal style="--reveal-index: 1">
                <span class="t8 ink-faint">{{ __('The founder') }}</span>
                <h2 class="t2 founder-name" id="founder-title">Hussy Levente</h2>

                <p class="t5 founder-lede">{{ __('21 years old, studying Economic Informatics at Budapest Business School (BGE). Design, code, clothing, and websites — all one person, on purpose.') }}</p>
                <p class="t6">{{ __('I work in Figma and a code editor, and nothing in between. That means the person who designs your site is the person who builds it, so nothing gets lost in a handover that never happens.') }}</p>

                <dl class="founder-facts">
                    <div>
                        <dt>{{ __('Based in') }}</dt>
                        <dd>Solymár</dd>
                    </div>
                    <div class="founder-fact-wide">
                        <dt>{{ __('Works in') }}</dt>
                        <dd>Laravel, Figma, PHP, CSS, HTML5, JavaScript, C#</dd>
                    </div>
                    <div>
                        <dt>{{ __('Answers in') }}</dt>
                        <dd>{{ __('Hungarian & English') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section class="closer" style="border-top: 1px solid var(--line)">
        <div class="shell">
            <h2 class="t2 closer-title">
                <span class="split-line" data-split="words">{{ __('Let’s make') }}</span>
                <span class="split-line" data-split="words">{{ __('something remarkable.') }}</span>
            </h2>
            <div class="closer-actions" data-reveal style="--reveal-index: 2">
                <a href="/contact" class="btn btn-solid" data-magnetic="0.25">{{ __('Start a project') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                <a href="/websites" class="btn">{{ __('See the work') }}</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ \App\Support\Asset::url('assets/js/accordion.js') }}" defer></script>
@endpush

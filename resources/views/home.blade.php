@extends('layout')

@section('title', __('blckt. — Custom Websites & Templates, Budapest'))
@section('meta_description', __('blckt. is a one-person studio in Budapest. Custom websites written by hand and ready-made templates from 50 000 Ft. See the work and what it costs.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/home.css') }}">
    {{-- A sablon-sav a katalogussal azonos kartyat hasznal, ezert ugyanabbol
         a fajlbol olvas - ket masolat elobb-utobb elcsuszna egymastol. --}}
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/templates.css') }}">
@endpush

@section('content')

    {{-- ── Hero ─────────────────────────────────────────────────── --}}
    <section class="hero shell" aria-labelledby="hero-title">
        <p class="t8 hero-eyebrow">{{ __('Design studio — Budapest, Hungary') }}</p>

        {{-- A cimsor szavankent lep be, nem soronkent. Ez a lap legelso
             mozdulata, es ez az egyetlen hely, ahol a lassabb, reszletesebb
             belepes indokolt - lejjebb mar a soronkenti eleg.
             A ket sor kozotti lepcsot a JS szamolja, hogy forditaskor is
             helyes maradjon. --}}
        <h1 class="t1 hero-title optical-left" id="hero-title">
            <span class="split-line" data-split="words">{{ __('I build your future') }}</span>
            <span class="split-line" data-split="words">{{ __('so you don’t have to.') }}</span>
        </h1>

        <p class="t5 hero-lede" data-reveal style="--reveal-index: 3">{{ __('Custom websites and ready-made templates, designed and built by one person from the first call to launch.') }}</p>

        {{-- A hero-ban all a legtobb hely a gomb korul, ezert itt erosebb a
             magneses huzas, mint a fejlecben. --}}
        <div class="hero-actions" data-reveal style="--reveal-index: 4">
            <a href="/websites" class="btn btn-solid" data-magnetic="0.25" data-magnetic="0.3">{{ __('See the work') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            <a href="/contact" class="btn" data-magnetic="0.3">{{ __('Start a project') }}</a>
        </div>

        {{-- Csak ellenorizheto allitas: a szam a tenylegesen lathato elo
             munkakbol jon, nem kezzel beirt ertek - igy nem csuszhat el, ha
             egy munkat elrejtunk vagy visszahozunk. --}}
        <p class="t8 hero-facts" data-reveal style="--reveal-index: 5">
            <span>{{ trans_choice(':count live client site|:count live client sites', $liveCount) }}</span>
            <span aria-hidden="true">—</span>
            <span>{{ __('100% written from scratch') }}</span>
            <span aria-hidden="true">—</span>
            <span>{{ __('Replies within 24 hours') }}</span>
        </p>

        <span class="hero-scroll" aria-hidden="true"></span>
    </section>

    {{-- ── Selected work ────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="work-title">
        <header class="section-head">
            <div>
                <span class="status status-live">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ __('Live projects') }}
                </span>
                <h2 class="t2 section-head-title" id="work-title">{{ __('Built, shipped, in use.') }}</h2>
            </div>
            <p class="t6 section-head-note">{{ __('Every project started with something broken. Open one for the full story, the before-and-after, and a walkthrough of the site in motion.') }}</p>
        </header>

        <div class="work-grid" data-skew="2">
            @foreach ($featured as $index => $project)
                {{-- Egyetlen kiemelt munka teljes szelessegben all: felezve
                     furcsa, felig ures sort hagyna maga mellett. --}}
                @include('partials.project-card', [
                    'project' => $project,
                    'index' => $index,
                    'wide' => count($featured) === 1,
                ])
            @endforeach
        </div>

        <div class="actions actions-spaced" data-reveal>
            <a href="/websites" class="btn">{{ __('All web projects') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
        </div>
    </section>

    {{-- ── Statement ────────────────────────────────────────────── --}}
    {{-- Itt korabban a "Nincsenek sablonok." sor allt. A sablon-szolgaltatas
         mellett az mar nem lenne igaz, es egy hazug allitas tobbet ront,
         mint amennyit egy jol hangzo sor hasznal. Ami maradt, az mindket
         szolgaltatasra igaz: a sablonokat is ugyanaz az egy ember rajzolta. --}}
    <section class="statement">
        <div class="shell">
            <div class="statement-inner">
                {{-- Harom rovid, kemeny mondat - szavankent erkeznek, mert
                     igy mindegyik kap egy sajat utemet. Egyben belepve ez a
                     harom sor egyetlen blokknak latszana. --}}
                <h2 class="t2 optical-left">
                    <span class="split-line" data-split="words">{{ __('No page builders.') }}</span>
                    <span class="split-line" data-split="words">{{ __('No agency layers.') }}</span>
                    <span class="split-line" data-split="words">{{ __('No outsourced taste.') }}</span>
                </h2>
                <span class="t8 statement-mark" data-reveal data-drift style="--reveal-index: 3">blckt.&trade;</span>
            </div>
        </div>
    </section>

    {{-- ── Ribbon ───────────────────────────────────────────────── --}}
    {{-- Az egyetlen elem az oldalon, ami akkor is mozog, ha a latogato nem
         csinal semmit. A sebessegebe belejatszik a gorgetes, ezert nem
         fuggetlenul jaro disz, hanem a lap sajat lendulete - lefele haladva
         gyorsul, felfele megfordul.

         aria-hidden, mert a tartalma szo szerint ugyanaz, mint a lentebbi
         "Amit csinalok" szekcio: a kepernyoolvasonak ez csak ismetles lenne.
         Ez egyben azt is megoldja, hogy a vegtelen sodrashoz szukseges
         masodik peldany ne hangozzon el ketszer. --}}
    <section class="ribbon" aria-hidden="true">
        <div class="marquee" data-marquee="34">
            <div class="marquee-track">
                @foreach ([__('Custom websites'), __('Website templates'), __('Redesigns'), __('Budapest')] as $word)
                    <span class="ribbon-word">{{ $word }}</span>
                    <span class="ribbon-dot">&bull;</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Templates ────────────────────────────────────────────── --}}
    {{-- A cimlap egyetlen hirdetese. A tobbi szekcio bemutat, ez elad -
         ezert fordul meg a paletta, es ezert all itt, kozvetlenul az
         allitas utan: az elozo mondat a minoseget vedi, ez pedig kinyitja
         az ajtot annak is, akinek az egyedi epites tul draga. --}}
    <section class="tpl-band" aria-labelledby="templates-band-title">
        <div class="shell">
            <header class="tpl-band-head">
                <div>
                    <span class="t8 ink-faint">{{ __('New — website templates') }}</span>
                    <h2 class="t2 tpl-band-title" id="templates-band-title">{{ __('Or take one I already built.') }}</h2>
                </div>
                <p class="t6 tpl-band-note">{{ __('Websites that are finished before you ask for them. You send your text and photos, I put your business inside, and it goes live in days — from :price, in the same three packages as my custom work.', ['price' => \App\Support\Packages::money($templateFloor)]) }}</p>
            </header>

            <div class="tpl-band-strip" data-reveal-group="tight">
                @foreach ($templates as $template)
                    <a href="{{ $template['url'] }}" class="tpl-band-item">
                        @include('partials.template-preview', ['template' => $template])
                        <span class="tpl-band-meta">
                            <span class="t7 tpl-band-name">{{ $template['name'] }}</span>
                            <span class="tpl-band-price">{{ $template['price_label'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="tpl-band-foot">
                <a href="{{ route('templates.index') }}" class="btn btn-solid" data-magnetic="0.25">
                    {{ trans_choice('See the :count template|See all :count templates', $templateCount) }}
                    <span class="arrow" aria-hidden="true">&#8594;</span>
                </a>

                <p class="t8 tpl-band-terms">
                    <span>{{ trans_choice('Live in :count day|Live in :count days', $templateDays) }}</span>
                    <span aria-hidden="true">—</span>
                    <span>{{ __('Fixed price, nothing monthly') }}</span>
                    <span aria-hidden="true">—</span>
                    <span>{{ __('Not unique, and priced like it') }}</span>
                </p>
            </div>
        </div>
    </section>

    {{-- ── What I do ────────────────────────────────────────────── --}}
    <section class="section shell" aria-labelledby="capabilities-title">
        <header class="section-head">
            <div>
                <span class="t8 ink-faint">{{ __('What I do') }}</span>
                <h2 class="t2 section-head-title" id="capabilities-title">{{ __('Three things, done properly.') }}</h2>
            </div>
        </header>

        <div class="capabilities" data-reveal-group>
            <article class="capability">
                <span class="t8 capability-index">01 — {{ __('Websites') }}</span>
                <h3 class="t4">{{ __('Sites that earn their keep.') }}</h3>
                <p class="t6">{{ __('Landing pages, multi-page sites and webshops. Designed in Figma, written by hand, and built around whatever your business actually needs to happen.') }}</p>
                <a href="/websites" class="capability-link link-underline">{{ __('See the work') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            </article>

            <article class="capability">
                <span class="t8 capability-index">02 — {{ __('Templates') }}</span>
                <h3 class="t4">{{ __('Finished before you asked.') }}</h3>
                <p class="t6">{{ __('Websites I already designed and wrote, in the same three packages. You get the same code for a good bit less, and you are live in days — as long as you can live with someone else having the same layout.') }}</p>
                <a href="{{ route('templates.index') }}" class="capability-link link-underline">{{ __('Browse the templates') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            </article>

            <article class="capability">
                <span class="t8 capability-index">03 — {{ __('Redesigns') }}</span>
                <h3 class="t4">{{ __('Rebuilds that keep your rankings.') }}</h3>
                <p class="t6">{{ __('You already have a site and it is holding you back. I rebuild it, keep the URLs alive, and hand you the numbers showing what changed.') }}</p>
                <a href="/services" class="capability-link link-underline">{{ __('What it costs') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
            </article>
        </div>
    </section>

    {{-- ── Closing CTA ──────────────────────────────────────────── --}}
    <section class="closer">
        <div class="shell">
            <h2 class="t2 closer-title">
                <span class="split-line" data-split="words">{{ __('Two build slots') }}</span>
                <span class="split-line" data-split="words">{{ __('open per quarter.') }}</span>
            </h2>

            <div class="closer-actions" data-reveal style="--reveal-index: 2">
                <a href="/contact" class="btn btn-solid" data-magnetic="0.25">{{ __('Start a project') }} <span class="arrow" aria-hidden="true">&#8594;</span></a>
                <a href="mailto:hello@blckt.hu" class="btn">hello@blckt.hu</a>
            </div>

            <p class="t8 closer-note" data-reveal style="--reveal-index: 3">{{ __('Tell me the business, the budget and the deadline. You get a fixed quote in writing.') }}</p>
        </div>
    </section>
@endsection

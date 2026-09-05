<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- A tema meg az elso festes elott beall, kulonben sotet modban
         felvillanna a vilagos hatter. --}}
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('blckt-theme');
                var theme = (stored === 'dark' || stored === 'light')
                    ? stored
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>

    @include('partials.seo-head')

    <link rel="icon" type="image/png" href="{{ asset('assets/imgs/brand/blckt_logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/imgs/brand/blckt_logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&family=Fragment+Mono&display=swap">

    @stack('preload')

    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/system.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/motion.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/chrome.css') }}">
    @stack('styles')
</head>
<body>

    <a href="#main" class="skip-link">{{ __('Skip to content') }}</a>

    @include('partials.icon-sprite')

    {{-- Betoltokep szandekosan nincs: legalabb 450 ms-ot varakoztatott
         mindenkit egy olyan oldalon, ami ennel gyorsabban megjelenik. --}}
    <div class="scroll-progress" aria-hidden="true"><span></span></div>

    <header id="site-header" class="site-header">
        <div class="shell header-inner">
            <a href="/" class="wordmark" aria-label="blckt. — {{ __('Home') }}">blckt.</a>

            <div class="header-right" id="header-right">
                <nav class="site-nav" aria-label="{{ __('Main navigation') }}">
                    @php
                        // A weboldalak allnak elol - ez a fo munka, a tobbi utana jon.
                        // A sablonok kozvetlenul utana: ugyanaz a vasarloi szandek,
                        // csak a masik veget celozza.
                        $navItems = [
                            '/websites' => __('Work'),
                            '/templates' => __('Templates'),
                            '/services' => __('Services'),
                            '/about' => __('About'),
                            '/contact' => __('Contact'),
                        ];
                    @endphp
                    @foreach ($navItems as $href => $label)
                        @php $active = request()->is(ltrim($href, '/').'*'); @endphp
                        <a href="{{ $href }}" class="{{ $active ? 'is-active' : '' }}" @if ($active) aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </nav>

                <div class="header-tools">
                    <a href="{{ route('saved') }}" class="saved-link" data-saved-link aria-label="{{ __('Saved') }}" @if (request()->is('saved')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 3h12a1 1 0 0 1 1 1v16l-7-4-7 4V4a1 1 0 0 1 1-1z"></path>
                        </svg>
                        <span class="saved-count" data-saved-count hidden></span>
                    </a>

                    <div class="lang-switch">
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'is-active' : '' }}" hreflang="en">EN</a>
                        <span aria-hidden="true">/</span>
                        <a href="{{ route('lang.switch', 'hu') }}" class="{{ app()->getLocale() === 'hu' ? 'is-active' : '' }}" hreflang="hu">HU</a>
                    </div>

                    <button type="button" id="theme-toggle" class="theme-toggle" aria-label="{{ __('Toggle dark mode') }}" aria-pressed="false">
                        <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4.5"></circle>
                            <path d="M12 2v2M12 20v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2 12h2M20 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"></path>
                        </svg>
                        <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>
                </div>

                {{-- Allando, egyertelmu utvonal a kapcsolat oldalra. Ez az egyetlen
                     tomor gomb a fejlecben, hogy ne versenyezzen mas akcioval. --}}
                {{-- A magneses huzas szandekosan gyenge (0.18): a fejlecben a
                     gomb kozel all a navigaciohoz, egy nagyobb kiterjedes mar
                     eltalalhatatlanna tenne. --}}
                <a href="/contact" class="btn btn-solid header-cta" data-magnetic="0.18" @if (request()->is('contact')) aria-current="page" @endif>
                    {{ __('Start a project') }}
                    <span class="arrow" aria-hidden="true">&#8594;</span>
                </a>
            </div>

            <button type="button" id="nav-toggle" class="nav-toggle" aria-label="{{ __('Menu') }}" aria-expanded="false" aria-controls="header-right">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <div class="nav-backdrop" id="nav-backdrop"></div>

    <main id="main">
        @yield('content')
    </main>

    <button type="button" id="scroll-top" class="scroll-top" aria-label="{{ __('Back to top') }}">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 19V5M5 12l7-7 7 7"></path>
        </svg>
    </button>

    <footer class="site-footer">
        <div class="shell">
            <div class="footer-top">
                <div class="footer-pitch">
                    <a href="/" class="footer-wordmark">blckt.</a>
                    <p class="t6 ink-muted">{{ __('A one-person studio in Budapest. Custom websites and ready-made templates — designed, built and shipped by the same pair of hands.') }}</p>
                </div>

                <div class="footer-cols">
                    @inject('websiteProjects', 'App\Http\Controllers\WebsiteProjectController')
                    <div class="footer-col">
                        <h2>{{ __('Work') }}</h2>
                        <a href="/websites">{{ __('All projects') }}</a>
                        {{-- A lista a lathato munkakbol epul (elo eloszor), nem kezzel
                             felsorolva - igy egy elrejtett munka nem hagy 404-es linket. --}}
                        @foreach ($websiteProjects->navLinks(4) as $link)
                            <a href="{{ route('websites.show', $link['slug']) }}">{{ $link['name'] }}</a>
                        @endforeach
                    </div>
                    @inject('websiteTemplates', 'App\Http\Controllers\TemplateController')
                    <div class="footer-col">
                        <h2>{{ __('Templates') }}</h2>
                        <a href="{{ route('templates.index') }}">{{ __('All templates') }}</a>
                        <a href="{{ route('playground.index') }}">{{ __('Playground') }}</a>
                        @foreach ($websiteTemplates->navLinks(4) as $link)
                            <a href="{{ route('templates.show', $link['slug']) }}">{{ $link['name'] }}</a>
                        @endforeach
                    </div>
                    <div class="footer-col">
                        <h2>{{ __('Studio') }}</h2>
                        <a href="/about">{{ __('About') }}</a>
                        <a href="/services">{{ __('Services & pricing') }}</a>
                        <a href="/contact">{{ __('Contact') }}</a>
                    </div>
                    <div class="footer-col">
                        <h2>{{ __('Reach me') }}</h2>
                        <a href="mailto:hello@blckt.hu">hello@blckt.hu</a>
                        <a href="tel:+36302552432">+36 30 255 2432</a>
                        @include('partials.social-links', ['variant' => 'row'])
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} blckt. — Budapest</span>
                <span class="footer-legal">
                    <a href="{{ route('legal.impresszum') }}" class="link-underline">{{ __('Imprint') }}</a>
                    <a href="{{ route('legal.adatvedelem') }}" class="link-underline">{{ __('Privacy') }}</a>
                    <a href="{{ route('legal.aszf') }}" class="link-underline">{{ __('Terms') }}</a>
                    <a href="#" data-cookie-preferences class="link-underline">{{ __('Cookies') }}</a>
                </span>
            </div>
        </div>
    </footer>

    <div class="cookie-bar" id="cookie-bar" role="region" aria-label="{{ __('Cookies') }}">
        <h2>{{ __('Cookies') }}</h2>
        <p>{{ __('This site only uses cookies that are strictly necessary for it to work — session security, and remembering your language and theme choice. No advertising or tracking cookies.') }}</p>
        <div class="cookie-actions">
            <button type="button" class="btn" id="cookie-accept">{{ __('Got it') }}</button>
            <a href="{{ route('legal.adatvedelem') }}#sutik" class="t8 ink-muted link-underline">{{ __('Learn more') }}</a>
        </div>
    </div>

    <div class="lightbox" id="site-lightbox" role="dialog" aria-modal="true" aria-label="{{ __('Image viewer') }}">
        <div class="lightbox-toolbar">
            <button type="button" class="lightbox-btn lightbox-zoom-out" aria-label="{{ __('Zoom out') }}">&minus;</button>
            <button type="button" class="lightbox-btn lightbox-zoom-in" aria-label="{{ __('Zoom in') }}">&plus;</button>
            <button type="button" class="lightbox-btn lightbox-close" aria-label="{{ __('Close') }}">&times;</button>
        </div>
        <div class="lightbox-stage">
            <img src="" alt="" class="lightbox-image">
        </div>
    </div>

    {{-- A mozgas-mag ELSOKENT fut: o nyitja az oldal egyetlen
         requestAnimationFrame ciklusat, es a tobbi szkript abba kot be
         (window.blcktMotion), ahelyett hogy sajat gorgetes-figyelot nyitna.
         Mindketto "defer", tehat a sorrend garantalt. --}}
    <script src="{{ \App\Support\Asset::url('assets/js/motion.js') }}" defer></script>
    <script src="{{ \App\Support\Asset::url('assets/js/site.js') }}" defer></script>
    <script src="{{ \App\Support\Asset::url('assets/js/lightbox.js') }}" defer></script>
    <script src="{{ \App\Support\Asset::url('assets/js/saved.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

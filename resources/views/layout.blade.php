<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('preload')
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('blckt-theme');
                var theme = (stored === 'dark' || stored === 'light') ? stored : 'light';
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>
    @php
        // @section('x', $value) inline form already HTML-escapes $value via e(),
        // so the yielded content below must be echoed raw (not re-escaped) to
        // avoid double-encoding entities like "&" -> "&amp;amp;".
        $metaTitle = trim($__env->yieldContent('title')) ?: e(__('blckt. | Custom Websites & Clothing Design, Budapest'));
        $metaDescription = trim($__env->yieldContent('meta_description')) ?: e(__('I design and build custom websites and premium streetwear from Budapest. No templates, real code, one person start to finish.'));
        $metaImagePath = trim($__env->yieldContent('meta_image')) ?: 'assets/imgs/brand/blckt_mainpage_hero_image.webp';
        $ogLocale = app()->getLocale() === 'hu' ? 'hu_HU' : 'en_US';
        $ogLocaleAlt = app()->getLocale() === 'hu' ? 'en_US' : 'hu_HU';
    @endphp

    <title>{!! $metaTitle !!}</title>
    <meta name="description" content="{!! $metaDescription !!}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="blckt.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{!! $metaTitle !!}">
    <meta property="og:description" content="{!! $metaDescription !!}">
    <meta property="og:image" content="{{ asset($metaImagePath) }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:locale:alternate" content="{{ $ogLocaleAlt }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! $metaTitle !!}">
    <meta name="twitter:description" content="{!! $metaDescription !!}">
    <meta name="twitter:image" content="{{ asset($metaImagePath) }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/imgs/brand/blckt_logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/imgs/brand/blckt_logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/imgs/brand/blckt_logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cursor.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/spotlight.css') }}">
    @stack('styles')
</head>
<body class="is-loading{{ request()->is('websites') ? ' page-websites' : '' }}">

    <div class="dot-spotlight" id="dot-spotlight"></div>

    <div id="page-loader" class="page-loader">
        <div class="page-loader-inner">
            <span class="page-loader-text">blckt.</span>
            <div class="page-loader-bar"><span></span></div>
        </div>
    </div>

    <header id="blckt-navbar" class="navbar">
        <div class="nav-container">
            <div class="logo">
                <a href="/">blckt.</a>
            </div>
            <div class="nav-right" id="nav-right">
                <nav class="nav-links">
                    <a href="/clothing" class="active">{{ __('clothing') }}</a>
                    <a href="/websites">{{ __('websites') }}</a>
                    <a href="/contact">{{ __('contact') }}</a>
                    <a href="/about">{{ __('about') }}</a>
                </nav>
                <div class="lang-switcher">
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-option {{ app()->getLocale() === 'en' ? 'is-active' : '' }}">EN</a>
                    <span class="lang-divider">/</span>
                    <a href="{{ route('lang.switch', 'hu') }}" class="lang-option {{ app()->getLocale() === 'hu' ? 'is-active' : '' }}">HU</a>
                </div>
                <button type="button" id="theme-toggle" class="theme-toggle" aria-label="{{ __('Toggle dark mode') }}" aria-pressed="false">
                    <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"></circle>
                        <line x1="12" y1="2" x2="12" y2="4.5"></line>
                        <line x1="12" y1="19.5" x2="12" y2="22"></line>
                        <line x1="4.22" y1="4.22" x2="5.94" y2="5.94"></line>
                        <line x1="18.06" y1="18.06" x2="19.78" y2="19.78"></line>
                        <line x1="2" y1="12" x2="4.5" y2="12"></line>
                        <line x1="19.5" y1="12" x2="22" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.94" y2="18.06"></line>
                        <line x1="18.06" y1="5.94" x2="19.78" y2="4.22"></line>
                    </svg>
                    <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>
            </div>
            <button type="button" id="nav-toggle" class="nav-toggle" aria-label="{{ __('Menu') }}" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <div class="nav-backdrop" id="nav-backdrop"></div>

    <main>
        @yield('content')
    </main>

    <a href="/contact" class="get-in-touch">{{ __('Get in touch') }} <span class="action-icon" aria-hidden="true">&#8594;</span></a>

    <button type="button" id="scroll-top-btn" class="scroll-top-btn" aria-label="{{ __('Back to top') }}">
        <img loading="lazy" decoding="async" src="{{ asset('assets/imgs/brand/slider_arrow_blckt.png') }}" alt="">
    </button>

    <footer class="footer">
        <div class="footer-container">
            <a href="/" class="footer-logo">
                <h2>blckt.</h2>
            </a>
            <div class="footer-columns">
                <div class="col">
                    <h3>{{ __('clothing') }}</h3>
                    <a href="{{ route('clothing.collection') }}">{{ __('blckt. collection') }}</a>
                    <a href="{{ route('clothing.show', 'ratio') }}">Ratio</a>
                    <a href="{{ route('clothing.show', 'hollyweed') }}">Hollyweed</a>
                    <a href="{{ route('clothing.show', 'agapiti') }}">Agapití Skópelos</a>
                    <a href="{{ route('clothing.show', 'prodigy') }}">Prodigy.</a>
                    <a href="{{ route('clothing.show', 'miamivice') }}">Miami Vice</a>
                </div>
                <div class="col">
                    <h3>{{ __('websites') }}</h3>
                    <a href="/websites">{{ __('all websites') }}</a>
                    <a href="{{ route('websites.show', 'paradise') }}">Paradise</a>
                    <a href="{{ route('websites.show', 'palesso') }}">Palesso</a>
                    <a href="{{ route('websites.show', 'kepszakadas') }}">Képszakadás</a>
                    <a href="{{ route('websites.show', 'juiced') }}">Juiced</a>
                </div>
                <div class="col">
                    <h3>{{ __('contact') }}</h3>
                    <a href="/contact">{{ __('Get in touch') }}</a>
                    <a href="mailto:hello@blckt.hu">hello@blckt.hu</a>
                    <a href="tel:+36302552432">+36 30 255 2432</a>
                    <a href="https://wa.me/36302552432" target="_blank" rel="noopener">{{ __('WhatsApp') }}</a>
                </div>
                <div class="col">
                    <h3>{{ __('about') }}</h3>
                    <a href="/">{{ __('Home') }}</a>
                    <a href="/about">{{ __('about') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="lightbox" id="site-lightbox">
        <div class="lightbox-toolbar">
            <button type="button" class="lightbox-btn lightbox-zoom-out" aria-label="{{ __('Zoom out') }}">&minus;</button>
            <button type="button" class="lightbox-btn lightbox-zoom-in" aria-label="{{ __('Zoom in') }}">&plus;</button>
            <button type="button" class="lightbox-btn lightbox-close" aria-label="{{ __('Close') }}">&times;</button>
        </div>
        <div class="lightbox-stage">
            <img src="" alt="" class="lightbox-image">
        </div>
    </div>

    <script>
        (function () {
            var MIN_DISPLAY_MS = 500;
            var start = Date.now();
            var loader = document.getElementById('page-loader');

            function hideLoader() {
                var remaining = Math.max(0, MIN_DISPLAY_MS - (Date.now() - start));
                setTimeout(function () {
                    loader.classList.add('is-hidden');
                    document.body.classList.remove('is-loading');
                    loader.addEventListener('transitionend', function () {
                        loader.remove();
                    }, { once: true });
                }, remaining);
            }

            if (document.readyState === 'complete') {
                hideLoader();
            } else {
                window.addEventListener('load', hideLoader);
            }
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lastScrollY = window.scrollY;
            const navbar = document.getElementById('blckt-navbar');

            window.addEventListener('scroll', () => {
                if (window.scrollY > lastScrollY && window.scrollY > 80) {
                    // Lefele görgetés - eltűnik
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    // Felfele görgetés - megjelenik
                    navbar.style.transform = 'translateY(0)';
                }
                lastScrollY = window.scrollY;
            });
        });
    </script>

    <script>
        (function () {
            var btn = document.getElementById('scroll-top-btn');
            var SHOW_AFTER = 500;

            function toggleVisibility() {
                btn.classList.toggle('is-visible', window.scrollY > SHOW_AFTER);
            }

            window.addEventListener('scroll', toggleVisibility, { passive: true });
            toggleVisibility();

            btn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();
    </script>

    <script>
        (function () {
            var toggle = document.getElementById('nav-toggle');
            var navRight = document.getElementById('nav-right');
            var backdrop = document.getElementById('nav-backdrop');
            if (!toggle || !navRight || !backdrop) return;

            function closeMenu() {
                toggle.classList.remove('is-active');
                navRight.classList.remove('is-open');
                backdrop.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('no-scroll');
            }

            function openMenu() {
                toggle.classList.add('is-active');
                navRight.classList.add('is-open');
                backdrop.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.classList.add('no-scroll');
            }

            toggle.addEventListener('click', function () {
                if (navRight.classList.contains('is-open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            backdrop.addEventListener('click', closeMenu);

            navRight.querySelectorAll('a').forEach(function (a) {
                a.addEventListener('click', closeMenu);
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 768) closeMenu();
            });
        })();
    </script>

    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/cursor.js') }}"></script>
    <script src="{{ asset('assets/js/lightbox.js') }}"></script>

    @stack('scripts')
</body>
</html>

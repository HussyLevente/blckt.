<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('blckt. | Professional Websites') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cursor.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">
    @stack('styles')
</head>
<body class="is-loading">

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
            <div class="nav-right">
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
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <a href="/contact" class="get-in-touch">{{ __('Get in touch') }} <span class="info-icon">&#9432;</span></a>

    <button type="button" id="scroll-top-btn" class="scroll-top-btn" aria-label="{{ __('Back to top') }}">
        <img src="{{ asset('assets/imgs/slider_arrow_blckt.png') }}" alt="">
    </button>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h2>blckt.</h2>
            </div>
            <div class="footer-columns">
                <div class="col">
                    <h3>{{ __('clothing') }}</h3>
                    <a href="#">{{ __('blckt. collection') }}</a>
                </div>
                <div class="col">
                    <h3>{{ __('websites') }}</h3>
                    <a href="#">{{ __('all websites') }}</a>
                </div>
                <div class="col">
                    <h3>{{ __('contact') }}</h3>
                    <a href="#">blckt.websites@gmail.com</a>
                    <a href="#">+36 30 255 2432</a>
                </div>
                <div class="col">
                    <h3>{{ __('about') }}</h3>
                    <a href="#">{{ __('about') }}</a>
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

    <script src="{{ asset('assets/js/cursor.js') }}"></script>
    <script src="{{ asset('assets/js/lightbox.js') }}"></script>

    @stack('scripts')
</body>
</html>

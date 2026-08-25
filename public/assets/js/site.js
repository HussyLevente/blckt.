/**
 * Oldalkeret-viselkedes: fejlec, mobilmenu, tema, gorgeto gomb,
 * sutisav. Mind apro, egymastol fuggetlen darab, ezert egy fajlban ulnek -
 * igy egyetlen keres hozza az egesz keretet.
 */
(function () {
    'use strict';

    var THEME_KEY = 'blckt-theme';
    var COOKIE_KEY = 'blckt-cookie-consent';

    document.addEventListener('DOMContentLoaded', function () {
        initHeader();
        initMobileNav();
        initTheme();
        initScrollTop();
        initCookieBar();
    });

    /* ---------------------------------------------------------------
       Fejlec: uveg-hatter gorgetesre, elrejtes lefele haladva
       --------------------------------------------------------------- */
    function initHeader() {
        var header = document.getElementById('site-header');
        if (!header) return;

        var lastY = window.scrollY;
        var ticking = false;

        function render() {
            ticking = false;
            var y = window.scrollY;

            header.classList.toggle('is-scrolled', y > 24);

            // Nyitott mobilmenu mellett a fejlec marad, kulonben a bezaro
            // gomb is elcsuszna a kepernyorol.
            var menuOpen = document.body.classList.contains('no-scroll');
            header.classList.toggle('is-hidden', !menuOpen && y > lastY && y > 140);

            lastY = y;
        }

        window.addEventListener('scroll', function () {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(render);
        }, { passive: true });

        render();
    }

    /* ---------------------------------------------------------------
       Mobilmenu
       --------------------------------------------------------------- */
    function initMobileNav() {
        var toggle = document.getElementById('nav-toggle');
        var panel = document.getElementById('header-right');
        var backdrop = document.getElementById('nav-backdrop');
        if (!toggle || !panel || !backdrop) return;

        function close() {
            toggle.classList.remove('is-open');
            panel.classList.remove('is-open');
            backdrop.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('no-scroll');
        }

        function open() {
            toggle.classList.add('is-open');
            panel.classList.add('is-open');
            backdrop.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.classList.add('no-scroll');
        }

        toggle.addEventListener('click', function () {
            panel.classList.contains('is-open') ? close() : open();
        });

        backdrop.addEventListener('click', close);
        panel.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', close);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') close();
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 900) close();
        });
    }

    /* ---------------------------------------------------------------
       Tema - az elso festes elotti beallitast a layout inline scriptje
       vegzi, itt mar csak a kapcsolo el.
       --------------------------------------------------------------- */
    function initTheme() {
        var toggle = document.getElementById('theme-toggle');
        if (!toggle) return;

        function current() {
            return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        }

        function apply(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            toggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        }

        apply(current());

        toggle.addEventListener('click', function () {
            var next = current() === 'dark' ? 'light' : 'dark';
            apply(next);
            try { localStorage.setItem(THEME_KEY, next); } catch (e) {}
        });
    }

    /* ---------------------------------------------------------------
       Vissza a tetejere
       --------------------------------------------------------------- */
    function initScrollTop() {
        var btn = document.getElementById('scroll-top');
        if (!btn) return;

        var ticking = false;

        window.addEventListener('scroll', function () {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () {
                ticking = false;
                btn.classList.toggle('is-visible', window.scrollY > 600);
            });
        }, { passive: true });

        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ---------------------------------------------------------------
       Sutisav - nem modalis, csak egy sarokban meghuzodo sav, mert
       kizarolag mukodeshez szukseges sutik vannak.
       --------------------------------------------------------------- */
    function initCookieBar() {
        var bar = document.getElementById('cookie-bar');
        if (!bar) return;

        var accept = document.getElementById('cookie-accept');

        function consented() {
            try { return localStorage.getItem(COOKIE_KEY) === 'accepted'; }
            catch (e) { return false; }
        }

        if (accept) {
            accept.addEventListener('click', function () {
                try { localStorage.setItem(COOKIE_KEY, 'accepted'); } catch (e) {}
                bar.classList.remove('is-visible');
            });
        }

        document.querySelectorAll('[data-cookie-preferences]').forEach(function (el) {
            el.addEventListener('click', function (event) {
                event.preventDefault();
                bar.classList.add('is-visible');
            });
        });

        if (!consented()) {
            setTimeout(function () { bar.classList.add('is-visible'); }, 1200);
        }
    }
})();

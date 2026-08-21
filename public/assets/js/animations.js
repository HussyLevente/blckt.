/**
 * Globalis mozgas-vezerles.
 *
 * Minden gorgeteshez kotott szamitas egyetlen rAF ciklusban fut, hogy ne legyen
 * tobb egymast taposo scroll listener az oldalon.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.addEventListener('DOMContentLoaded', function () {
        initScrollProgress();
        initEnterAnimations();
        initMaskedHeadings();
        initParallax();
        initFloatingCta();
        initPageTransition();
    });

    /* ---------------------------------------------------------------
       Gorgetesi folyamatjelzo
       --------------------------------------------------------------- */
    function initScrollProgress() {
        var bar = document.querySelector('.scroll-progress span');
        if (!bar) return;

        var ticking = false;

        function render() {
            ticking = false;
            var max = document.documentElement.scrollHeight - window.innerHeight;
            var ratio = max > 0 ? window.scrollY / max : 0;
            bar.style.transform = 'scaleX(' + Math.min(1, Math.max(0, ratio)).toFixed(4) + ')';
        }

        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(render);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
        render();
    }

    /* ---------------------------------------------------------------
       Belepo animaciok - egyszer futnak le, utana levalunk az elemrol
       --------------------------------------------------------------- */
    function initEnterAnimations() {
        var targets = document.querySelectorAll('[data-anim], [data-anim-stagger]');
        if (targets.length === 0) return;

        if (reduceMotion || !('IntersectionObserver' in window)) {
            targets.forEach(function (el) { el.classList.add('is-inview', 'is-settled'); });
            return;
        }

        targets.forEach(applyStagger);

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                entry.target.classList.add('is-inview');
                observer.unobserve(entry.target);

                entry.target.addEventListener('transitionend', function onEnd() {
                    entry.target.classList.add('is-settled');
                    entry.target.removeEventListener('transitionend', onEnd);
                });
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

        targets.forEach(function (el) { observer.observe(el); });
    }

    function applyStagger(el) {
        var delay = parseInt(el.dataset.animDelay || '0', 10);
        if (delay) el.style.setProperty('--anim-delay', delay + 'ms');

        if (!el.hasAttribute('data-anim-stagger')) return;

        var step = parseInt(el.dataset.animStagger, 10) || 90;
        Array.prototype.forEach.call(el.children, function (child, index) {
            child.style.transitionDelay = (delay + index * step) + 'ms';
        });
    }

    /* ---------------------------------------------------------------
       Cimsor-maszk: a .anim-mask tartalmat egy belso sav-elembe csomagoljuk,
       hogy alulrol be tudjon csuszni a levagott keretbol.
       --------------------------------------------------------------- */
    function initMaskedHeadings() {
        var masks = document.querySelectorAll('.anim-mask');
        if (masks.length === 0) return;

        masks.forEach(function (mask) {
            if (mask.querySelector('.anim-mask-line')) return;

            var line = document.createElement('span');
            line.className = 'anim-mask-line';
            while (mask.firstChild) line.appendChild(mask.firstChild);
            mask.appendChild(line);
        });

        if (reduceMotion || !('IntersectionObserver' in window)) {
            masks.forEach(function (mask) { mask.classList.add('is-inview'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-inview');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.4 });

        masks.forEach(function (mask) { observer.observe(mask); });
    }

    /* ---------------------------------------------------------------
       Parallax - csak akkor szamolunk, ha az elem a nezetben van
       --------------------------------------------------------------- */
    function initParallax() {
        var items = Array.prototype.slice.call(document.querySelectorAll('[data-parallax]'));
        if (items.length === 0 || reduceMotion) return;

        // Erintokepernyon a parallax tobbet art, mint hasznal (jitter, akkumulator).
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

        var ticking = false;

        function render() {
            ticking = false;
            var viewportH = window.innerHeight;

            items.forEach(function (el) {
                var rect = el.getBoundingClientRect();
                if (rect.bottom < 0 || rect.top > viewportH) return;

                var speed = parseFloat(el.dataset.parallax) || 0.15;
                // -1 .. 1 a nezet kozepehez kepest
                var progress = ((rect.top + rect.height / 2) - viewportH / 2) / viewportH;
                el.style.setProperty('--parallax-y', (progress * speed * -100).toFixed(2) + 'px');
            });
        }

        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(render);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
        render();
    }

    /* ---------------------------------------------------------------
       Lebego "Get in touch" link: elrejtjuk, amikor a kapcsolati szekcio
       vagy a lablec latszik - kulonben rarakodik a tartalomra, es ugyanazt
       a hivast ismetli, ami amugy is a kepernyon van.
       --------------------------------------------------------------- */
    function initFloatingCta() {
        var cta = document.querySelector('.get-in-touch');
        if (!cta) return;

        var zones = document.querySelectorAll('.contact-cta-section, .project-contact-section, .about-contact-section, .footer');
        if (zones.length === 0 || !('IntersectionObserver' in window)) return;

        var visibleZones = 0;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                visibleZones += entry.isIntersecting ? 1 : -1;
            });

            visibleZones = Math.max(0, visibleZones);
            cta.classList.toggle('is-tucked', visibleZones > 0);
        }, { threshold: 0.05 });

        zones.forEach(function (zone) { observer.observe(zone); });
    }

    /* ---------------------------------------------------------------
       Oldalvaltas: belso linkre kattintva kihalvanyitjuk a tartalmat,
       igy a kovetkezo oldal betolto-kepernyoje nem vagja el a mozgast.
       --------------------------------------------------------------- */
    function initPageTransition() {
        if (reduceMotion) return;

        document.addEventListener('click', function (event) {
            if (event.defaultPrevented || event.button !== 0) return;
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

            var link = event.target.closest ? event.target.closest('a[href]') : null;
            if (!link || link.target === '_blank' || link.hasAttribute('download')) return;

            var href = link.getAttribute('href');
            if (!href || href.charAt(0) === '#') return;
            if (link.origin !== window.location.origin) return;
            if (link.pathname === window.location.pathname && link.search === window.location.search) return;

            var protocol = link.protocol;
            if (protocol !== 'http:' && protocol !== 'https:') return;

            event.preventDefault();
            document.body.classList.add('is-leaving');

            // A navigacio akkor is elindul, ha a transitionend valamiert nem jon meg.
            setTimeout(function () { window.location.href = link.href; }, 300);
        });

        // Vissza-gomb utan a bfcache visszaadhatja a kihalvanyitott allapotot.
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) document.body.classList.remove('is-leaving');
        });
    }
})();

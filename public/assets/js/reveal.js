/**
 * Mozgas-vezerles.
 *
 * Minden gorgeteshez kotott szamitas egyetlen requestAnimationFrame ciklusban
 * fut, hogy ne legyen tobb egymast taposo scroll listener az oldalon. A JS
 * kizarolag osztalyt es CSS-valtozot ir - az idozitest es a gorbeket a
 * motion.css hatarozza meg.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.addEventListener('DOMContentLoaded', function () {
        initMasks();
        initReveal();
        initScrollProgress();
        initParallax();
        initPageTransition();
    });

    /* ---------------------------------------------------------------
       Maszkolt cimsorok
       A tartalmat egy belso savba csomagoljuk, hogy alulrol be tudjon
       csuszni a levagott keretbol. Meg a reveal elott fut, kulonben az
       observer olyan elemet figyelne, aminek meg nincs .mask-line-ja.
       --------------------------------------------------------------- */
    function initMasks() {
        document.querySelectorAll('.mask').forEach(function (mask, index) {
            if (mask.querySelector('.mask-line')) return;

            var line = document.createElement('span');
            line.className = 'mask-line';
            while (mask.firstChild) line.appendChild(mask.firstChild);
            mask.appendChild(line);

            // Egy cimsoron beluli sorok egymas utan lepnek be
            if (!mask.style.getPropertyValue('--reveal-index')) {
                var siblings = mask.parentElement
                    ? Array.prototype.filter.call(mask.parentElement.children, function (el) {
                          return el.classList.contains('mask');
                      })
                    : [mask];
                mask.style.setProperty('--reveal-index', siblings.indexOf(mask));
            }
        });
    }

    /* ---------------------------------------------------------------
       Belepo animaciok - egyszer futnak le, utana levalunk az elemrol
       --------------------------------------------------------------- */
    function initReveal() {
        var targets = document.querySelectorAll('[data-reveal], [data-reveal-group], .mask');
        if (targets.length === 0) return;

        if (reduceMotion || !('IntersectionObserver' in window)) {
            targets.forEach(function (el) { el.classList.add('is-in', 'is-settled'); });
            return;
        }

        // A csoportok gyerekei kapjak meg a sorszamot, amibol a lepcso szamolodik
        document.querySelectorAll('[data-reveal-group]').forEach(function (group) {
            Array.prototype.forEach.call(group.children, function (child, index) {
                child.style.setProperty('--reveal-index', Math.min(index, 6));
            });
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);

                entry.target.addEventListener('transitionend', function onEnd() {
                    entry.target.classList.add('is-settled');
                    entry.target.removeEventListener('transitionend', onEnd);
                }, { once: true });
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -80px 0px' });

        targets.forEach(function (el) { observer.observe(el); });
    }

    /* ---------------------------------------------------------------
       Gorgetesi folyamatjelzo
       --------------------------------------------------------------- */
    function initScrollProgress() {
        var bar = document.querySelector('.scroll-progress span');
        if (!bar) return;

        onScrollFrame(function () {
            var max = document.documentElement.scrollHeight - window.innerHeight;
            var ratio = max > 0 ? window.scrollY / max : 0;
            bar.style.transform = 'scaleX(' + Math.min(1, Math.max(0, ratio)).toFixed(4) + ')';
        });
    }

    /* ---------------------------------------------------------------
       Parallax - csak akkor szamolunk, ha az elem a nezetben van
       --------------------------------------------------------------- */
    function initParallax() {
        var items = Array.prototype.slice.call(document.querySelectorAll('[data-parallax]'));
        if (items.length === 0 || reduceMotion) return;

        // Erintokepernyon a parallax tobbet art, mint hasznal (jitter)
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

        onScrollFrame(function () {
            var viewportH = window.innerHeight;

            items.forEach(function (el) {
                var rect = el.getBoundingClientRect();
                if (rect.bottom < 0 || rect.top > viewportH) return;

                var speed = parseFloat(el.dataset.parallax) || 0.15;
                var progress = ((rect.top + rect.height / 2) - viewportH / 2) / viewportH;
                el.style.setProperty('--parallax-y', (progress * speed * -100).toFixed(2) + 'px');
            });
        });
    }

    /* ---------------------------------------------------------------
       Oldalvaltas: belso linkre kattintva kihalvanyitjuk a tartalmat,
       igy a kovetkezo oldal betoltese nem vagja el a mozgast.
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

            // Ez a keses minden belso kattintashoz hozzaadodik, ezert a lehető
            // legrovidebb: eppen csak elfedi a festes valtast, de meg nem
            // erzodik varakozasnak. A navigacio akkor is elindul, ha a
            // transitionend nem jonne meg.
            setTimeout(function () { window.location.href = link.href; }, 160);
        });

        // Vissza-gomb utan a bfcache visszaadhatja a kihalvanyitott allapotot
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) document.body.classList.remove('is-leaving');
        });
    }

    /* ---------------------------------------------------------------
       Kozos gorgetes-hurok: minden hivo ugyanabban a frame-ben fut le
       --------------------------------------------------------------- */
    function onScrollFrame(render) {
        var ticking = false;

        function schedule() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () {
                ticking = false;
                render();
            });
        }

        window.addEventListener('scroll', schedule, { passive: true });
        window.addEventListener('resize', schedule);
        render();
    }
})();

/**
 * Kollekcio-vago.
 *
 * A gorgetest vegig a bongeszo vegzi (scroll-snap + scrollLeft), a szkript
 * csak rateszi a vezerlest. Ket oka van:
 *   - JS nelkul is teljesen mukodik: ujjal, gorgovel es billentyuzettel
 *     gorgetheto marad, csak a gombok es a jelzo hianyoznak
 *   - a mozgast a kompozitor viszi, nem a fo szal, igy nem akad
 *
 * A gombok mindig egy lathato "lapnyit" leptetnek, nem egy elemet - szeles
 * kepernyon igy nem kell otot kattintani ahhoz, hogy tortenjen valami.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-slider]').forEach(setup);
    });

    function setup(root) {
        var track = root.querySelector('[data-slider-track]');
        if (!track) return;

        var prev = document.querySelector('[data-slider-prev]');
        var next = document.querySelector('[data-slider-next]');
        var progress = root.querySelector('[data-slider-progress] span');
        var head = document.querySelector('.slider-head');

        // Csak akkor mutatjuk a vezerlest, ha van egyaltalan mit gorgetni.
        var scrollable = track.scrollWidth - track.clientWidth > 4;
        if (scrollable) {
            root.classList.add('is-ready');
            if (head) head.classList.add('is-ready');
        }

        /* --- Leptetes ------------------------------------------------ */

        function page() {
            var slide = track.querySelector('.slide');
            if (!slide) return track.clientWidth;

            var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
            var step = slide.getBoundingClientRect().width + gap;

            // Egesz szamu elemet leptetunk, hogy a snap-pontokra erkezzunk.
            var perView = Math.max(1, Math.floor(track.clientWidth / step));
            return step * perView;
        }

        function go(dir) {
            track.scrollBy({
                left: dir * page(),
                behavior: prefersReducedMotion() ? 'auto' : 'smooth'
            });
        }

        if (prev) prev.addEventListener('click', function () { go(-1); });
        if (next) next.addEventListener('click', function () { go(1); });

        /* --- Allapot ------------------------------------------------- */

        function render() {
            var max = track.scrollWidth - track.clientWidth;
            var x = track.scrollLeft;

            // A vegeken kikapcsoljuk a gombot - 2px tures a tort pixelek miatt.
            if (prev) prev.disabled = x <= 2;
            if (next) next.disabled = x >= max - 2;

            if (progress) {
                // A sav hossza a lathato hanyadot mutatja, a helye pedig azt,
                // hol tartunk - mint egy gorgetosav hovelye.
                //
                // A translateX szazalek a sav SAJAT (skalazas elotti) szelessegehez
                // mert, ezert nem kell a scaleX-szel korrigalni: a bal el pontosan
                // a megtett hanyadra kerul.
                var visible = Math.min(1, track.clientWidth / track.scrollWidth);
                var travelled = max > 0 ? (x / max) * (1 - visible) : 0;
                progress.style.transform =
                    'translateX(' + (travelled * 100) + '%) scaleX(' + visible + ')';
            }
        }

        var ticking = false;
        track.addEventListener('scroll', function () {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () { ticking = false; render(); });
        }, { passive: true });

        /* --- Billentyuzet -------------------------------------------- */

        track.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') { e.preventDefault(); go(1); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); go(-1); }
        });

        /* --- Huzas egerrel ------------------------------------------- */

        var down = false, startX = 0, startScroll = 0, moved = 0;

        track.addEventListener('pointerdown', function (e) {
            // Erintesnel a natív gorgetes jobb, mint barmi, amit utanoznank.
            if (e.pointerType === 'touch') return;
            down = true;
            moved = 0;
            startX = e.clientX;
            startScroll = track.scrollLeft;
            root.classList.add('is-dragging');
        });

        track.addEventListener('pointermove', function (e) {
            if (!down) return;
            var dx = e.clientX - startX;
            moved = Math.abs(dx);
            track.scrollLeft = startScroll - dx;
        });

        function endDrag() {
            if (!down) return;
            down = false;
            root.classList.remove('is-dragging');
        }

        track.addEventListener('pointerup', endDrag);
        track.addEventListener('pointercancel', endDrag);
        track.addEventListener('pointerleave', endDrag);

        // Huzas utani kattintas ne nyissa meg a terméket.
        track.addEventListener('click', function (e) {
            if (moved > 8) { e.preventDefault(); e.stopPropagation(); }
            moved = 0;
        }, true);

        /* --- Ujramerés ----------------------------------------------- */

        if ('ResizeObserver' in window) {
            var ro = new ResizeObserver(function () {
                var can = track.scrollWidth - track.clientWidth > 4;
                root.classList.toggle('is-ready', can);
                if (head) head.classList.toggle('is-ready', can);
                render();
            });
            ro.observe(track);
        }

        render();
    }

    function prefersReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }
})();

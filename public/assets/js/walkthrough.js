/**
 * Vegigvezeto videok lusta betoltese.
 *
 * A <video> elem szandekosan nem szerepel a kiszolgalt HTML-ben: amig senki nem
 * kattint a lejatszasra, a bongeszo csak a poszterkepet tolti le. Kattintasra
 * epitjuk fel az elemet, es kifele gorgetve automatikusan megallitjuk, hogy egy
 * hosszu oldalon ne fusson a hattereben egy nem latszo video.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-walkthrough]').forEach(initWalkthrough);
    });

    function initWalkthrough(root) {
        var stage = root.querySelector('[data-walkthrough-stage]');
        var button = root.querySelector('[data-walkthrough-play]');
        if (!stage || !button) return;

        var video = null;

        button.addEventListener('click', function () {
            if (video) {
                video.play();
                return;
            }

            video = buildVideo(button, root);
            stage.appendChild(video);
            root.classList.add('is-playing');

            // Egy frame-et varunk, hogy a beuszas atmenete tenyleg lefusson.
            requestAnimationFrame(function () {
                video.classList.add('is-ready');
                var started = video.play();

                // Ha az autoplay-szabalyok elutasitjak, a natív vezerlok
                // ott maradnak - a latogato kezzel el tudja inditani.
                if (started && typeof started.catch === 'function') {
                    started.catch(function () {});
                }
            });

            watchVisibility(video);
        });
    }

    function buildVideo(button, root) {
        var video = document.createElement('video');
        var poster = root.querySelector('.walkthrough-poster');

        video.className = 'walkthrough-video';
        video.controls = true;
        video.playsInline = true;
        video.preload = 'auto';
        video.setAttribute('playsinline', '');

        if (poster) video.poster = poster.getAttribute('src');

        var source = document.createElement('source');
        source.src = button.dataset.src;
        source.type = button.dataset.mime || 'video/mp4';
        video.appendChild(source);

        return video;
    }

    /**
     * A nezetbol kigorduló video megall. Ha a latogato visszagorget, nem
     * inditjuk ujra magatol - a lejatszas mindig szandekos marad.
     */
    function watchVisibility(video) {
        if (!('IntersectionObserver' in window)) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting && !video.paused) video.pause();
            });
        }, { threshold: 0.25 });

        observer.observe(video);
    }
})();

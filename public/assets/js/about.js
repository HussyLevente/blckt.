document.addEventListener('DOMContentLoaded', function () {
    initCounters();
    initAccordions();
});

/* Szamlalo animacio a statisztikaknal */
function initCounters() {
    var counters = document.querySelectorAll('.stat-number');
    if (counters.length === 0) return;

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function animateCounter(el) {
        var target = parseFloat(el.dataset.target);
        var suffix = el.dataset.suffix || '';

        if (reduceMotion) {
            el.textContent = target + suffix;
            return;
        }

        var duration = 1200;
        var start = null;

        function step(timestamp) {
            if (!start) start = timestamp;
            var progress = Math.min((timestamp - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased) + suffix;
            if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
    }

    if (!('IntersectionObserver' in window)) {
        counters.forEach(animateCounter);
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            animateCounter(entry.target);
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.4 });

    counters.forEach(function (el) { observer.observe(el); });
}

/**
 * Osszecsukhato panelek.
 *
 * Kulon fut a szamlaloktol: korabban ugyanabban a fuggvenyben ult, egy
 * `counters.length === 0` korai return mogott, igy olyan oldalon, ahol nincs
 * statisztika (pl. GYIK), az akkordeon egyaltalan nem valaszolt kattintasra.
 */
function initAccordions() {
    var triggers = document.querySelectorAll('.accordion-trigger');
    if (triggers.length === 0) return;

    triggers.forEach(function (trigger) {
        var item = trigger.closest('.accordion-item');
        if (!item) return;

        var panel = item.querySelector('.accordion-panel');
        if (!panel) return;

        trigger.setAttribute('aria-expanded', 'false');

        trigger.addEventListener('click', function () {
            var isOpen = item.classList.contains('is-open');

            if (isOpen) {
                panel.style.maxHeight = '0px';
                item.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            } else {
                item.classList.add('is-open');
                panel.style.maxHeight = panel.scrollHeight + 'px';
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // Atmeretezeskor a nyitott panelek fix maxHeight-je elavul, es levagja a szoveget.
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            document.querySelectorAll('.accordion-item.is-open .accordion-panel').forEach(function (panel) {
                panel.style.maxHeight = panel.scrollHeight + 'px';
            });
        }, 150);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    var counters = document.querySelectorAll('.stat-number');
    if (counters.length === 0) return;

    function animateCounter(el) {
        var target = parseFloat(el.dataset.target);
        var suffix = el.dataset.suffix || '';
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
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    counters.forEach(function (el) { observer.observe(el); });

    var accordionTriggers = document.querySelectorAll('.accordion-trigger');
    accordionTriggers.forEach(function (trigger) {
        var item = trigger.closest('.accordion-item');
        var panel = item.querySelector('.accordion-panel');

        trigger.addEventListener('click', function () {
            var isOpen = item.classList.contains('is-open');
            if (isOpen) {
                panel.style.maxHeight = '0px';
                item.classList.remove('is-open');
            } else {
                item.classList.add('is-open');
                panel.style.maxHeight = panel.scrollHeight + 'px';
            }
        });
    });
});

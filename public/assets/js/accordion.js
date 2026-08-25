/**
 * Osszecsukhato panelek.
 *
 * A magassagot a CSS animalja (grid-template-rows: 0fr -> 1fr), ezert itt
 * nincs meresre szukseg: a JS csak az allapotot es az ARIA-t kezeli. Igy az
 * atmeretezes sem tudja elrontani a nyitott panelek magassagat.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.accordion-trigger').forEach(function (trigger) {
            var item = trigger.closest('.accordion-item');
            if (!item) return;

            var panel = item.querySelector('.accordion-panel');
            if (!panel) return;

            if (!panel.id) {
                panel.id = 'panel-' + Math.random().toString(36).slice(2, 9);
            }

            var open = item.classList.contains('is-open');
            trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
            trigger.setAttribute('aria-controls', panel.id);

            trigger.addEventListener('click', function () {
                var next = !item.classList.contains('is-open');
                item.classList.toggle('is-open', next);
                trigger.setAttribute('aria-expanded', next ? 'true' : 'false');
            });
        });
    });
})();

/* ============================================================================
   Sablon-demo nezegeto.

   A jelolesben minden mukodik szkript nelkul is: a fulek valodi linkek, a
   keretben pedig mar ott all az elso demo. Ez a fajl csak annyit tesz, hogy
   a valtas a lapon belul tortenjen, es hogy a szelesseg-valtok megjelenjenek
   - azoknak szkript nelkul nem lenne ertelmuk, ezert alapbol rejtve allnak.
   ============================================================================ */
(function () {
    'use strict';

    var viewers = document.querySelectorAll('[data-demo]');
    if (!viewers.length) return;

    Array.prototype.forEach.call(viewers, function (root) {
        var frame = root.querySelector('[data-demo-frame]');
        var shell = root.querySelector('[data-demo-shell]');
        var address = root.querySelector('[data-demo-address]');
        var open = root.querySelector('[data-demo-open]');
        var tabs = root.querySelectorAll('[data-demo-tab]');
        var widths = root.querySelector('[data-demo-widths]');

        if (!frame || !shell) return;

        /* ── Vallalkozas-valtas ─────────────────────────────── */
        Array.prototype.forEach.call(tabs, function (tab) {
            tab.addEventListener('click', function (e) {
                // Uj lapon nyitas (kozepso gomb, Ctrl/Cmd) marad, ami volt.
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
                e.preventDefault();

                var url = tab.getAttribute('data-demo-url');
                var name = tab.getAttribute('data-demo-name');

                frame.setAttribute('src', url);
                if (address) address.textContent = url;
                if (open) open.setAttribute('href', url);

                Array.prototype.forEach.call(tabs, function (other) {
                    var on = other === tab;
                    other.classList.toggle('is-active', on);
                    other.setAttribute('aria-selected', on ? 'true' : 'false');
                });

                // A cim a keretben valtozik, ezert a kepernyoolvasonak is
                // el kell mondani, mi latszik most.
                var title = frame.getAttribute('title');
                if (title && name) {
                    frame.setAttribute('title', title.replace(/^[^—]+/, name + ' '));
                }
            });
        });

        /* ── Szelesseg-valtas ───────────────────────────────── */
        if (!widths) return;
        widths.hidden = false;

        var buttons = widths.querySelectorAll('[data-demo-width]');
        Array.prototype.forEach.call(buttons, function (btn) {
            btn.addEventListener('click', function () {
                var w = parseInt(btn.getAttribute('data-demo-width'), 10) || 0;

                // 0 = teljes szelesseg. Barmi mas rogzitett pixelertek, hogy
                // a demo tenyleg abban a nezetben rendelodjon, ne csak
                // kicsinyitve latszodjon.
                shell.style.maxWidth = w ? w + 'px' : '';
                shell.classList.toggle('is-narrow', w > 0);

                Array.prototype.forEach.call(buttons, function (other) {
                    var on = other === btn;
                    other.classList.toggle('is-active', on);
                    other.setAttribute('aria-pressed', on ? 'true' : 'false');
                });
            });
        });
    });
})();

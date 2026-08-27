/* ============================================================================
   Mentett elemek.
   ----------------------------------------------------------------------------
   Nincs fiok az oldalon, ezert a mentes a latogato bongeszojeben el. Ez nem
   ideiglenes megoldas: a jatszoter is igy mukodik, es ugyanaz az alku -
   semmi nem megy el sehova, cserebe a lista nem koveti at masik gepre.

   A tarolt alak SZANDEKOSAN tipusos:

       { v: 1, items: [ { type: 'template', id: 'signal', at: 1724... } ] }

   Igy egy kesobbi menthető dolog (munka, ruha) csak egy uj 'type' - a
   taroloval, a fejlec-szamlaloval es a mentett lappal nem kell mit kezdeni.
   ============================================================================ */
(function () {
    'use strict';

    var KEY = 'blckt-saved';
    var VERSION = 1;
    var listeners = [];

    /* --- Tarolo ---------------------------------------------------------- */

    function read() {
        try {
            var raw = window.localStorage.getItem(KEY);
            if (!raw) return [];
            var data = JSON.parse(raw);
            if (!data || data.v !== VERSION || !Array.isArray(data.items)) return [];

            return data.items.filter(function (item) {
                return item && typeof item.type === 'string' && typeof item.id === 'string';
            });
        } catch (e) {
            // Privat ablak, tiltott sutik, tele tarolo: a lap mukodjon tovabb.
            return [];
        }
    }

    function write(items) {
        try {
            window.localStorage.setItem(KEY, JSON.stringify({ v: VERSION, items: items }));
        } catch (e) {}
        emit();
    }

    function indexOf(items, type, id) {
        for (var i = 0; i < items.length; i++) {
            if (items[i].type === type && items[i].id === id) return i;
        }
        return -1;
    }

    /* --- Nyilvanos felulet ----------------------------------------------- */

    var store = {
        all: function (type) {
            var items = read();
            return type ? items.filter(function (i) { return i.type === type; }) : items;
        },
        has: function (type, id) {
            return indexOf(read(), type, id) !== -1;
        },
        count: function (type) {
            return store.all(type).length;
        },
        toggle: function (type, id) {
            var items = read();
            var at = indexOf(items, type, id);

            if (at === -1) {
                // A legutobb mentett kerul elore: a mentett lap igy a
                // legfrissebb valasztassal indul.
                items.unshift({ type: type, id: id, at: Date.now() });
            } else {
                items.splice(at, 1);
            }

            write(items);
            return at === -1;
        },
        remove: function (type, id) {
            var items = read();
            var at = indexOf(items, type, id);
            if (at === -1) return false;
            items.splice(at, 1);
            write(items);
            return true;
        },
        clear: function () {
            write([]);
        },
        onChange: function (fn) {
            listeners.push(fn);
            return fn;
        }
    };

    function emit() {
        listeners.forEach(function (fn) {
            try { fn(store); } catch (e) {}
        });
    }

    window.blcktSaved = store;

    /* --- Gombok ----------------------------------------------------------- */

    function paint(button) {
        var type = button.dataset.saveType;
        var id = button.dataset.saveId;
        var saved = store.has(type, id);
        var label = button.querySelector('.save-btn-label');

        button.classList.toggle('is-saved', saved);
        button.setAttribute('aria-pressed', saved ? 'true' : 'false');

        if (label) {
            label.textContent = saved ? button.dataset.labelSaved : button.dataset.labelSave;
        }
    }

    function paintAll() {
        document.querySelectorAll('[data-save]').forEach(paint);
    }

    /* --- Fejlec-szamlalo --------------------------------------------------- */

    function paintCount() {
        var total = store.count();

        document.querySelectorAll('[data-saved-count]').forEach(function (el) {
            el.textContent = total ? String(total) : '';
            el.hidden = total === 0;
        });

        document.querySelectorAll('[data-saved-link]').forEach(function (el) {
            el.classList.toggle('has-items', total > 0);
        });
    }

    /* --- Mentett lap ------------------------------------------------------- */

    function paintList() {
        var list = document.querySelector('[data-saved-list]');
        if (!list) return;

        var empty = document.querySelector('[data-saved-empty]');
        var counter = document.querySelector('[data-saved-total]');
        var items = store.all();
        var shown = 0;

        // Elobb mindent elrejtunk, aztan a mentes sorrendjeben tesszuk
        // vissza oket - igy a lista sorrendje a valasztasokat koveti, nem
        // a szerver altal kiirt sorrendet.
        var nodes = list.querySelectorAll('[data-saved-item]');
        nodes.forEach(function (node) { node.hidden = true; });

        items.forEach(function (item) {
            var node = list.querySelector('[data-saved-item][data-type="' + item.type + '"][data-id="' + item.id + '"]');
            if (!node) return;
            node.hidden = false;
            list.appendChild(node);
            shown++;
        });

        list.hidden = shown === 0;
        if (empty) empty.hidden = shown > 0;
        if (counter) counter.textContent = String(shown);
    }

    /* --- Indulas ---------------------------------------------------------- */

    document.addEventListener('click', function (event) {
        var button = event.target.closest('[data-save]');
        if (!button) return;

        event.preventDefault();
        store.toggle(button.dataset.saveType, button.dataset.saveId);
    });

    // Masik fulon mentett elem is latszodjon, ne csak ujratoltes utan.
    window.addEventListener('storage', function (event) {
        if (event.key === KEY) emit();
    });

    store.onChange(function () {
        paintAll();
        paintCount();
        paintList();
    });

    document.addEventListener('DOMContentLoaded', function () {
        paintAll();
        paintCount();
        paintList();
    });
})();

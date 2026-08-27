/* ============================================================================
   Sablon-playground.

   A demo egy iframe-ben fut, ugyanarrol az originrol, ezert a szuloablak
   belelat a dokumentumaba. Ez a fajl abbol csinal szerkesztot: a szoveges
   blokkok helyben irhatova valnak, a kepek lecserelhetok, a markaszin
   allithato - a valtoztatasok pedig megmaradnak a kovetkezo latogatasig.

   Amit SZANDEKOSAN nem tud:
     - nem kuld semmit szerverre (a fotok a gepen maradnak),
     - nem ad letoltheto vagy megoszthato valtozatot,
     - nem ir a demo forrasfajljaiba.

   A tarolas IndexedDB, nem localStorage. Egy telefonnal keszult foto
   atmeretezve is szazezres nagysagrendu; base64-kent a localStorage 5 MB-os
   kerete par kep utan betelne. Az IndexedDB a Blobot nyersen tarolja, es
   sokkal tobbet enged.
   ============================================================================ */
(function () {
    'use strict';

    var root = document.querySelector('[data-pg]');
    if (!root) return;

    var config;
    try {
        config = JSON.parse(root.getAttribute('data-pg-config'));
    } catch (e) {
        return;
    }

    /* ── Mit tekintunk szerkesztheto egysegnek ───────────────────────────
       TEXT: amin egyaltalan szoba johet a szerkesztes.
       BREAK: ami egy blokkot ketteoszt. Ha egy jelolt ilyet tartalmaz,
       akkor nem o az egyseg, hanem a benne levok. Igy egy kepet es harom
       bekezdest osszefogo kartya-link nem valik egyetlen szerkesztheto
       masszava, egy <h1>Nagy<br><em>betu</em></h1> viszont egyben marad -
       ahogy egy ember is egyetlen cimsornak latja.                        */
    var TEXT = 'h1,h2,h3,h4,h5,h6,p,li,blockquote,figcaption,dt,dd,td,th,label,a,button,span,strong,em,small,sup,time,address,summary,div';
    var BREAK = 'h1,h2,h3,h4,h5,h6,p,li,blockquote,figcaption,dt,dd,td,th,ul,ol,section,article,header,footer,nav,form,figure,table,div';
    var MEDIA = 'img,picture,video,iframe,svg,canvas';

    /* Nagyobb elt ennel nem tartunk meg. Egy 4000 px-es telefonfoto a
       keretben ugyanugy nez ki, mint 1600-on, csak sokkal tobb helyet visz. */
    var MAX_EDGE = 1600;
    var MAX_UPLOAD = 25 * 1024 * 1024;
    var UNDO_DEPTH = 40;

    var L = config.labels || {};

    /* ── A lap elemei ───────────────────────────────────────────────── */
    var frame = root.querySelector('[data-pg-frame]');
    var shell = root.querySelector('[data-pg-shell]');
    var status = root.querySelector('[data-pg-status]');
    var hint = root.querySelector('[data-pg-hint]');
    var undoButton = root.querySelector('[data-pg-undo]');
    var resetButton = root.querySelector('[data-pg-reset]');
    var panel = root.querySelector('[data-pg-panel]');

    if (!frame || !shell) return;

    /* ── Allapot ────────────────────────────────────────────────────── */
    var state = blank();
    var units = { text: {}, image: {} };
    var originals = { text: {}, image: {} };
    var undo = [];
    var objectUrls = [];
    var markers = [];
    var mode = 'edit';
    var persistent = true;
    var booted = false;
    var doc = null;
    var frameWin = null;
    var editingFrom = null;
    var selected = null;
    var saveTimer = null;

    function blank() {
        return { v: 1, text: {}, images: {}, swatches: {} };
    }

    /* ════════════════════════════════════════════════════════════════
       Tarolas
       ════════════════════════════════════════════════════════════════ */
    var DB_NAME = 'blckt-playground';
    var STORE = 'demos';
    var memory = {};
    var dbPromise = null;

    function db() {
        if (dbPromise) return dbPromise;

        dbPromise = new Promise(function (resolve, reject) {
            if (!window.indexedDB) {
                reject(new Error('unsupported'));
                return;
            }

            var request;
            try {
                request = window.indexedDB.open(DB_NAME, 1);
            } catch (e) {
                reject(e);
                return;
            }

            request.onupgradeneeded = function () {
                if (!request.result.objectStoreNames.contains(STORE)) {
                    request.result.createObjectStore(STORE);
                }
            };
            request.onsuccess = function () { resolve(request.result); };
            request.onerror = function () { reject(request.error || new Error('open')); };
            request.onblocked = function () { reject(new Error('blocked')); };
        });

        return dbPromise;
    }

    function transact(mode, run) {
        return db().then(function (handle) {
            return new Promise(function (resolve, reject) {
                var tx = handle.transaction(STORE, mode);
                var request = run(tx.objectStore(STORE));
                request.onsuccess = function () { resolve(request.result); };
                request.onerror = function () { reject(request.error); };
            });
        });
    }

    /* Ha az IndexedDB nem all rendelkezesre (privat ablak, letiltott
       sutik), a szerkeszto nem all le: memoriaban dolgozik tovabb, es a
       lap kiirja, hogy a valtozasok csak a lap bezarasaig elnek. Ez
       jobb, mint egy hasznalhatatlan gomb. */
    function loadState() {
        return transact('readonly', function (store) { return store.get(config.demo); })
            .catch(function () { persistent = false; return memory[config.demo] || null; });
    }

    function writeState(value) {
        memory[config.demo] = value;

        return transact('readwrite', function (store) { return store.put(value, config.demo); })
            .catch(function (error) {
                persistent = false;
                if (error && error.name === 'QuotaExceededError') say(L.quota, true);
                return null;
            });
    }

    function dropState() {
        delete memory[config.demo];

        return transact('readwrite', function (store) { return store['delete'](config.demo); })
            .catch(function () { return null; });
    }

    function persist() {
        if (saveTimer) window.clearTimeout(saveTimer);
        setStatus(L.saving);

        saveTimer = window.setTimeout(function () {
            saveTimer = null;
            writeState(state).then(function () { setStatus(null); });
        }, 400);
    }

    /* ════════════════════════════════════════════════════════════════
       Az iframe dokumentumanak feldolgozasa
       ════════════════════════════════════════════════════════════════ */
    function boot() {
        try {
            doc = frame.contentDocument;
            frameWin = frame.contentWindow;
        } catch (e) {
            return;
        }
        if (!doc || !doc.body) return;

        teardown();
        booted = true;

        injectStyle();
        index();

        loadState().then(function (saved) {
            state = merge(saved);
            applyAll();
            setMode(mode);
            reportRestored(saved);
            refreshUndo();
            setStatus(null);
        });

        bind();
    }

    function teardown() {
        objectUrls.forEach(function (url) { URL.revokeObjectURL(url); });
        objectUrls = [];
        markers = [];
        units = { text: {}, image: {} };
        originals = { text: {}, image: {} };
        selected = null;
        closePanel();
    }

    /* A mentett allapot regebbi lehet, mint a demo jelenlegi jelolese. Ami
       nem talal utat a lapon, azt csendben eldobjuk - jobb, mint egy
       kifagyott szerkeszto. */
    function merge(saved) {
        var fresh = blank();
        if (!saved) return fresh;

        fresh.swatches = saved.swatches || {};

        Object.keys(saved.text || {}).forEach(function (path) {
            if (units.text[path]) fresh.text[path] = saved.text[path];
        });
        Object.keys(saved.images || {}).forEach(function (path) {
            if (units.image[path]) fresh.images[path] = saved.images[path];
        });

        return fresh;
    }

    /**
     * Egy elem cime a dokumentumfaban.
     *
     * Nem az id-re es nem az osztalyra epul, mert azok ismetlodnek. A
     * "hanyadik ilyen nevu testver" lanc viszont egyertelmu, es olvashato
     * marad, ha valaki belenez a tarolt adatba.
     */
    function pathOf(el) {
        var parts = [];

        while (el && el.nodeType === 1 && el.nodeName !== 'HTML') {
            var parent = el.parentNode;
            if (!parent || parent.nodeType !== 1) break;

            var n = 0;
            for (var sibling = parent.firstElementChild; sibling && sibling !== el; sibling = sibling.nextElementSibling) {
                if (sibling.nodeName === el.nodeName) n++;
            }

            parts.unshift(el.nodeName.toLowerCase() + (n ? '[' + n + ']' : ''));
            el = parent;
        }

        return parts.join('/');
    }

    function skipped(el) {
        for (var i = 0; i < config.skip.length; i++) {
            if (el.closest(config.skip[i])) return true;
        }

        return false;
    }

    function isTextUnit(el) {
        if (!el.matches(TEXT)) return false;
        if (!el.textContent.trim()) return false;
        if (el.querySelector(MEDIA)) return false;

        var breakers = el.querySelectorAll(BREAK);
        for (var i = 0; i < breakers.length; i++) {
            if (breakers[i].textContent.trim()) return false;
        }

        return true;
    }

    function backgroundOf(el) {
        var value = frameWin.getComputedStyle(el).backgroundImage;

        if (!value || value.indexOf('url(') === -1) return null;
        // A data: URI-k dekoraciok (zajtextura, minta), nem tartalom.
        if (value.indexOf('url("data:') !== -1 || value.indexOf('url(data:') !== -1) return null;

        return value;
    }

    function index() {
        var all = doc.body.querySelectorAll('*');

        for (var i = 0; i < all.length; i++) {
            var el = all[i];
            var tag = el.nodeName.toLowerCase();

            if (tag === 'script' || tag === 'style' || tag === 'noscript' || tag === 'link') continue;
            if (skipped(el)) continue;

            if (tag === 'img') {
                register('image', el, {
                    kind: 'img',
                    src: el.getAttribute('src'),
                    srcset: el.getAttribute('srcset'),
                    width: el.getAttribute('width'),
                    height: el.getAttribute('height')
                });
                continue;
            }

            /* Egy elem vagy szoveg, vagy hatterkep - nem mindketto. A
               szoveg az erosebb: azt latja es arra kattint az ember. */
            if (isTextUnit(el)) {
                // Csak a legkulso egyseg szamit. Egy <em> a cimsoron belul
                // maga is jelolt lenne, de a cimsorral egyutt szerkesztjuk.
                if (!el.parentNode.closest('[data-pg-text]')) {
                    register('text', el, el.innerHTML);
                }
                continue;
            }

            if (tag === 'body' || tag === 'html') continue;

            var background = backgroundOf(el);
            if (background) {
                register('image', el, { kind: 'bg', style: el.style.backgroundImage });
            }
        }
    }

    function register(type, el, original) {
        var path = pathOf(el);
        if (!path || units[type][path]) return;

        units[type][path] = type === 'image' ? { el: el, kind: original.kind } : { el: el };
        originals[type][path] = original;
        el.setAttribute('data-pg-' + (type === 'image' ? 'img' : 'text'), path);
    }

    /* ════════════════════════════════════════════════════════════════
       Megjelenites
       ════════════════════════════════════════════════════════════════ */
    function injectStyle() {
        if (doc.getElementById('pg-style')) return;

        var style = doc.createElement('style');
        style.id = 'pg-style';
        style.textContent = [
            '.pg-edit [data-pg-text]{cursor:text}',
            '.pg-edit [data-pg-text]:hover{outline:2px dashed rgba(43,110,246,.7);outline-offset:3px}',
            '.pg-edit [data-pg-text]:empty::after{content:"\\00b7\\00b7\\00b7";opacity:.45}',
            '.pg-edit [data-pg-img]{cursor:pointer}',
            '.pg-edit [data-pg-img]:hover{outline:2px solid rgba(43,110,246,.85);outline-offset:3px}',
            '[data-pg-editing]{outline:2px solid #2b6ef6!important;outline-offset:3px;border-radius:2px}',
            '[data-pg-selected]{outline:3px solid #2b6ef6!important;outline-offset:3px}',
            '.pg-chip{position:fixed;z-index:2147483000;transform:translate(-50%,-50%);',
            'padding:9px 14px;border:0;border-radius:999px;background:#2b6ef6;color:#fff;',
            'font:600 13px/1 system-ui,sans-serif;letter-spacing:.01em;cursor:pointer;',
            'box-shadow:0 6px 22px rgba(0,0,0,.35);white-space:nowrap}',
            '.pg-chip:hover{background:#1a56d0}',
            'html:not(.pg-edit) .pg-chip{display:none!important}'
        ].join('');

        doc.head.appendChild(style);
    }

    /* A hatterkepek gyakran minden mas MOGOTT allnak (a signal-burger
       nyitokepe z-index:-2), ezert rajuk kattintani nem lehet: a kattintas
       a folotte levo szovegen all meg. Ezert kap mindegyik egy sajat,
       lebego gombot. A gomb fixen pozicionalt, igy a helyet a keret
       nezetehez kepest kell szamolni - gorgetesnel ujra. */
    function buildMarkers() {
        Object.keys(units.image).forEach(function (path) {
            var unit = units.image[path];
            if (unit.kind !== 'bg') return;

            var chip = doc.createElement('button');
            chip.type = 'button';
            chip.className = 'pg-chip';
            chip.textContent = L.background || 'Background photo';
            chip.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selectImage(unit.el);
            });

            doc.body.appendChild(chip);
            markers.push({ chip: chip, el: unit.el });
        });

        if (!markers.length) return;

        placeMarkers();
        frameWin.addEventListener('scroll', queuePlace, true);
        frameWin.addEventListener('resize', queuePlace);
    }

    var placing = false;
    function queuePlace() {
        if (placing) return;
        placing = true;
        frameWin.requestAnimationFrame(function () {
            placing = false;
            placeMarkers();
        });
    }

    function placeMarkers() {
        var vh = frameWin.innerHeight;
        var vw = frameWin.innerWidth;

        markers.forEach(function (marker) {
            var rect = marker.el.getBoundingClientRect();
            var visible = mode === 'edit'
                && rect.bottom > 60 && rect.top < vh - 20
                && rect.width > 60 && rect.height > 60;

            marker.chip.style.display = visible ? 'block' : 'none';
            if (!visible) return;

            marker.chip.style.top = Math.round(clamp(rect.top + rect.height / 2, 56, vh - 56)) + 'px';
            marker.chip.style.left = Math.round(clamp(rect.left + rect.width / 2, 100, vw - 100)) + 'px';
        });
    }

    /* ════════════════════════════════════════════════════════════════
       Allapot alkalmazasa
       ════════════════════════════════════════════════════════════════ */
    function applyAll() {
        Object.keys(state.text).forEach(function (path) {
            paintText(path, state.text[path]);
        });
        Object.keys(state.images).forEach(function (path) {
            paintImage(path, state.images[path]);
        });
        config.swatches.forEach(function (swatch) {
            paintSwatch(swatch, state.swatches[swatch.key] || swatch['default']);
        });

        buildMarkers();
    }

    function paintText(path, html) {
        var unit = units.text[path];
        if (unit && typeof html === 'string') unit.el.innerHTML = sanitize(html);
    }

    function paintImage(path, record) {
        var unit = units.image[path];
        if (!unit) return;

        var url = record.url || objectUrl(record.blob);
        if (!url) return;

        if (unit.kind === 'img') {
            // A srcset erosebb a src-nel: ha bennmaradna, a bongeszo tovabbra
            // is az eredeti kepet toltene. A meret-attributumokat pedig a
            // beteendo kep valodi aranyara allitjuk at, kulonben egy allo
            // fotot egy fekvo helyere teve az oldal megnyulna.
            unit.el.removeAttribute('srcset');
            unit.el.removeAttribute('sizes');
            unit.el.src = url;

            if (record.w && record.h) {
                unit.el.setAttribute('width', record.w);
                unit.el.setAttribute('height', record.h);
            }
        } else {
            unit.el.style.backgroundImage = 'url("' + url + '")';
        }
    }

    function restoreImage(path) {
        var unit = units.image[path];
        var original = originals.image[path];
        if (!unit || !original) return;

        if (unit.kind === 'img') {
            unit.el.src = original.src || '';
            setAttr(unit.el, 'srcset', original.srcset);
            setAttr(unit.el, 'width', original.width);
            setAttr(unit.el, 'height', original.height);
        } else {
            unit.el.style.backgroundImage = original.style || '';
        }
    }

    function paintSwatch(swatch, colour) {
        Object.keys(swatch.vars).forEach(function (name) {
            doc.documentElement.style.setProperty(name, shiftLightness(colour, swatch.vars[name]));
        });
    }

    function objectUrl(blob) {
        if (!blob) return null;

        var url = URL.createObjectURL(blob);
        objectUrls.push(url);

        return url;
    }

    function setAttr(el, name, value) {
        if (value === null || value === undefined) el.removeAttribute(name);
        else el.setAttribute(name, value);
    }

    /* ════════════════════════════════════════════════════════════════
       Szerkesztes
       ════════════════════════════════════════════════════════════════ */
    function bind() {
        doc.addEventListener('click', onClick, true);
        doc.addEventListener('keydown', onKey, true);
    }

    function onClick(e) {
        var target = e.target;
        if (!target || !target.closest) return;

        /* A demokban vannak kifele mutato linkek. Ha egy ilyen a kereten
           belul nyilna meg, a latogato eltevedne, es a munkaja is eltunne a
           szeme elol - ezert uj lapra tereljuk, mindket modban. */
        var link = target.closest('a[href]');
        if (link && external(link.getAttribute('href')) && link.target !== '_blank') {
            e.preventDefault();
            window.open(link.href, '_blank', 'noopener');

            return;
        }

        if (mode !== 'edit') return;

        var active = doc.querySelector('[data-pg-editing]');
        if (active && active.contains(target)) {
            // A kurzor elhelyezesehez at kell engedni a kattintast; csak a
            // linkek kovetese all meg.
            if (link) e.preventDefault();

            return;
        }

        e.preventDefault();
        e.stopPropagation();

        if (active) commitText(active);

        var image = target.closest('[data-pg-img]');
        if (image) {
            selectImage(image);

            return;
        }

        var text = target.closest('[data-pg-text]');
        if (text) {
            startText(text);

            return;
        }

        deselect();
    }

    function onKey(e) {
        var active = doc.querySelector('[data-pg-editing]');
        if (!active) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            active.innerHTML = editingFrom;
            commitText(active);

            return;
        }

        // Enter = kesz. Sortoreshez Shift+Enter - ugyanaz a szokas, mint
        // barmelyik uzenetkuldoben.
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            commitText(active);
        }
    }

    function startText(el) {
        deselect();

        editingFrom = el.innerHTML;
        el.setAttribute('contenteditable', 'true');
        el.setAttribute('data-pg-editing', '');
        el.setAttribute('spellcheck', 'false');
        el.focus();

        selectAll(el);
        el.addEventListener('paste', onPaste);
        el.addEventListener('blur', onBlur);
        say(L.editingText);
    }

    function onPaste(e) {
        // Vagolapbol erkezo jeloles nelkul: a demo sajat stilusai maradnak.
        e.preventDefault();
        var text = (e.clipboardData || frameWin.clipboardData).getData('text/plain');
        doc.execCommand('insertText', false, text);
    }

    function onBlur(e) {
        var el = e.currentTarget;
        if (el.hasAttribute('data-pg-editing')) commitText(el);
    }

    function commitText(el) {
        /* Ujrabelepes elleni zar.
           A contenteditable levetele egy fokuszban levo elemrol AZONNAL
           blur esemenyt valt ki, az pedig visszavezet ide - meg mielott ez
           a hivas vegzett volna. A masodik hivas elhasznalna az
           editingFrom-ot, es az elso mar csak null-t talalna: a
           visszavonas ettol uritette ki a blokkot ahelyett, hogy
           visszaallitotta volna.
           Ezert a jelolo megy le elsokent, es a mentendo ertek is azonnal
           kikerul a kozos valtozobol. */
        if (!el.hasAttribute('data-pg-editing')) return;
        el.removeAttribute('data-pg-editing');

        var before = editingFrom;
        editingFrom = null;

        el.removeAttribute('contenteditable');
        el.removeAttribute('spellcheck');
        el.removeEventListener('paste', onPaste);
        el.removeEventListener('blur', onBlur);

        var path = el.getAttribute('data-pg-text');
        var after = sanitize(el.innerHTML);

        if (after === before) {
            el.innerHTML = before;

            return;
        }

        el.innerHTML = after;
        recordText(path, after);
        pushUndo({ kind: 'text', path: path, prev: before });
        say(L.textSaved);
    }

    function recordText(path, html) {
        if (html === originals.text[path]) delete state.text[path];
        else state.text[path] = html;

        persist();
    }

    function selectAll(el) {
        var range = doc.createRange();
        range.selectNodeContents(el);

        var selection = frameWin.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }

    /* Csak annyit engedunk at, ami a demo sajat jelolesebol szarmazhat. A
       tartalom sose hagyja el ezt a gepet, tehat masnak nem tud artani -
       de a sajat lapjat se tudja szetlonni valaki egy beillesztessel. */
    function sanitize(html) {
        var box = doc.createElement('div');
        box.innerHTML = html;

        var all = box.querySelectorAll('*');
        for (var i = all.length - 1; i >= 0; i--) {
            var el = all[i];
            var tag = el.nodeName.toLowerCase();

            if (tag === 'script' || tag === 'style' || tag === 'iframe' || tag === 'object' || tag === 'embed' || tag === 'link') {
                el.parentNode.removeChild(el);
                continue;
            }

            for (var j = el.attributes.length - 1; j >= 0; j--) {
                var name = el.attributes[j].name;
                var value = el.attributes[j].value;

                if (name.slice(0, 2).toLowerCase() === 'on') el.removeAttribute(name);
                else if ((name === 'href' || name === 'src') && /^\s*javascript:/i.test(value)) el.removeAttribute(name);
            }
        }

        return box.innerHTML;
    }

    /* ════════════════════════════════════════════════════════════════
       Kepek
       ════════════════════════════════════════════════════════════════ */
    function selectImage(el) {
        deselect();

        selected = el;
        el.setAttribute('data-pg-selected', '');

        var rect = el.getBoundingClientRect();
        if (rect.top < 0 || rect.bottom > frameWin.innerHeight) {
            el.scrollIntoView({ block: 'center', behavior: 'smooth' });
        }

        openPanel(el);
    }

    function deselect() {
        if (selected) selected.removeAttribute('data-pg-selected');
        selected = null;
        closePanel();
    }

    function openPanel(el) {
        if (!panel) return;

        var path = el.getAttribute('data-pg-img');
        var unit = units.image[path];

        panel.hidden = false;
        panel.setAttribute('data-pg-path', path);

        var kind = panel.querySelector('[data-pg-panel-kind]');
        if (kind) kind.textContent = unit.kind === 'bg' ? (L.background || '') : (L.imageOnPage || '');

        var clear = panel.querySelector('[data-pg-clear]');
        if (clear) clear.disabled = !state.images[path];

        var file = panel.querySelector('[data-pg-file]');
        if (file) file.value = '';

        var link = panel.querySelector('[data-pg-link]');
        if (link) link.value = '';
    }

    function closePanel() {
        if (!panel) return;
        panel.hidden = true;
        panel.removeAttribute('data-pg-path');
    }

    var webpOk = null;
    function canEncodeWebp() {
        if (webpOk === null) {
            var probe = document.createElement('canvas');
            probe.width = 1;
            probe.height = 1;
            webpOk = probe.toDataURL('image/webp').indexOf('data:image/webp') === 0;
        }

        return webpOk;
    }

    /**
     * Feltoltott fajlbol beteheto kep.
     *
     * A telefonok fotoi tul nagyok ahhoz, hogy nyersen taroljuk oket, ezert
     * atmeretezzuk. A forgatast nem kell kezzel intezni: a bongeszok az
     * <img>-re maguktol alkalmazzak az EXIF orientaciot, es a vaszon is a
     * mar elforgatott kepet kapja - ellentetben a nyers fajl olvasasaval.
     */
    function processFile(file) {
        return new Promise(function (resolve, reject) {
            if (!file || file.type.indexOf('image/') !== 0) {
                reject(new Error('type'));

                return;
            }
            if (file.size > MAX_UPLOAD) {
                reject(new Error('size'));

                return;
            }
            // Az SVG vektoros: a vaszonra rajzolas pont azt venne el belole,
            // amiert valasztottak. Valtozatlanul megy tovabb.
            if (file.type === 'image/svg+xml') {
                resolve({ blob: file, w: 0, h: 0 });

                return;
            }

            var url = URL.createObjectURL(file);
            var image = new Image();

            image.onload = function () {
                var scale = Math.min(1, MAX_EDGE / Math.max(image.naturalWidth, image.naturalHeight));
                var w = Math.max(1, Math.round(image.naturalWidth * scale));
                var h = Math.max(1, Math.round(image.naturalHeight * scale));

                var canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                canvas.getContext('2d').drawImage(image, 0, 0, w, h);
                URL.revokeObjectURL(url);

                // Atlatszosag: a JPEG nem tud rola, ezert logo eseten
                // feketere vagy feherre valtana a hatter. Ahol nincs WebP,
                // ott inkabb PNG, meg ha nagyobb is.
                var alpha = file.type === 'image/png' || file.type === 'image/gif' || file.type === 'image/webp';
                var type = canEncodeWebp() ? 'image/webp' : (alpha ? 'image/png' : 'image/jpeg');

                canvas.toBlob(function (blob) {
                    if (!blob) {
                        reject(new Error('encode'));

                        return;
                    }

                    // Egy mar tomoritett kis kepet az ujrakodolas nagyobbra
                    // is hizlalhat. Olyankor az eredeti a jobb.
                    if (scale === 1 && blob.size >= file.size) {
                        resolve({ blob: file, w: image.naturalWidth, h: image.naturalHeight });

                        return;
                    }

                    resolve({ blob: blob, w: w, h: h });
                }, type, type === 'image/webp' ? 0.82 : 0.85);
            };

            image.onerror = function () {
                URL.revokeObjectURL(url);
                reject(new Error('decode'));
            };

            image.src = url;
        });
    }

    function replaceImage(path, record) {
        var previous = state.images[path] || null;

        state.images[path] = record;
        paintImage(path, record);
        pushUndo({ kind: 'image', path: path, prev: previous });
        persist();

        var clear = panel && panel.querySelector('[data-pg-clear]');
        if (clear) clear.disabled = false;

        say(L.imageSaved);
    }

    function resetImage(path) {
        var previous = state.images[path] || null;
        if (!previous) return;

        delete state.images[path];
        restoreImage(path);
        pushUndo({ kind: 'image', path: path, prev: previous });
        persist();

        var clear = panel && panel.querySelector('[data-pg-clear]');
        if (clear) clear.disabled = true;
    }

    /* ════════════════════════════════════════════════════════════════
       Visszavonas
       ════════════════════════════════════════════════════════════════ */
    function pushUndo(entry) {
        undo.push(entry);
        if (undo.length > UNDO_DEPTH) undo.shift();
        refreshUndo();
    }

    function stepBack() {
        var entry = undo.pop();
        if (!entry) return;

        if (entry.kind === 'text') {
            paintText(entry.path, entry.prev);
            recordText(entry.path, entry.prev);
        } else if (entry.kind === 'image') {
            if (entry.prev) {
                state.images[entry.path] = entry.prev;
                paintImage(entry.path, entry.prev);
            } else {
                delete state.images[entry.path];
                restoreImage(entry.path);
            }
            persist();
        } else if (entry.kind === 'swatch') {
            applySwatch(entry.key, entry.prev, false);
        }

        refreshUndo();
        say(L.undone);
    }

    function refreshUndo() {
        if (undoButton) undoButton.disabled = undo.length === 0;
        if (resetButton) resetButton.disabled = !touched();
    }

    function touched() {
        return Object.keys(state.text).length > 0
            || Object.keys(state.images).length > 0
            || Object.keys(state.swatches).length > 0;
    }

    /* ════════════════════════════════════════════════════════════════
       Szinek
       ════════════════════════════════════════════════════════════════ */
    function applySwatch(key, colour, record) {
        var swatch = null;
        config.swatches.forEach(function (s) { if (s.key === key) swatch = s; });
        if (!swatch) return;

        var previous = state.swatches[key] || swatch['default'];

        if (colour === swatch['default']) delete state.swatches[key];
        else state.swatches[key] = colour;

        paintSwatch(swatch, colour);

        var input = root.querySelector('[data-pg-swatch="' + key + '"]');
        if (input && input.value !== colour) input.value = colour;

        if (record !== false) pushUndo({ kind: 'swatch', key: key, prev: previous });
        persist();
    }

    function shiftLightness(hex, delta) {
        if (!delta) return hex;

        var hsl = toHsl(hex);
        if (!hsl) return hex;

        return toHex(hsl.h, hsl.s, clamp(hsl.l + delta, 0, 100));
    }

    function toHsl(hex) {
        var m = /^#?([0-9a-f]{6})$/i.exec(String(hex).trim());
        if (!m) return null;

        var n = parseInt(m[1], 16);
        var r = ((n >> 16) & 255) / 255;
        var g = ((n >> 8) & 255) / 255;
        var b = (n & 255) / 255;

        var max = Math.max(r, g, b);
        var min = Math.min(r, g, b);
        var l = (max + min) / 2;
        var h = 0;
        var s = 0;

        if (max !== min) {
            var d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);

            if (max === r) h = (g - b) / d + (g < b ? 6 : 0);
            else if (max === g) h = (b - r) / d + 2;
            else h = (r - g) / d + 4;

            h *= 60;
        }

        return { h: h, s: s * 100, l: l * 100 };
    }

    function toHex(h, s, l) {
        s /= 100;
        l /= 100;

        var c = (1 - Math.abs(2 * l - 1)) * s;
        var x = c * (1 - Math.abs(((h / 60) % 2) - 1));
        var m = l - c / 2;
        var rgb = [0, 0, 0];

        if (h < 60) rgb = [c, x, 0];
        else if (h < 120) rgb = [x, c, 0];
        else if (h < 180) rgb = [0, c, x];
        else if (h < 240) rgb = [0, x, c];
        else if (h < 300) rgb = [x, 0, c];
        else rgb = [c, 0, x];

        return '#' + rgb.map(function (v) {
            var hex = Math.round((v + m) * 255).toString(16);

            return hex.length === 1 ? '0' + hex : hex;
        }).join('');
    }

    /* ════════════════════════════════════════════════════════════════
       Mod, szelesseg, allapotjelzes
       ════════════════════════════════════════════════════════════════ */
    function setMode(next) {
        mode = next;

        if (doc) {
            var active = doc.querySelector('[data-pg-editing]');
            if (active) commitText(active);

            doc.documentElement.classList.toggle('pg-edit', mode === 'edit');
            placeMarkers();
        }

        if (mode !== 'edit') deselect();

        root.querySelectorAll('[data-pg-mode]').forEach(function (button) {
            var on = button.getAttribute('data-pg-mode') === mode;
            button.classList.toggle('is-active', on);
            button.setAttribute('aria-pressed', on ? 'true' : 'false');
        });

        say(mode === 'edit' ? L.hintEdit : L.hintPreview);
    }

    function setStatus(message) {
        if (!status) return;

        if (message) {
            status.textContent = message;

            return;
        }

        status.textContent = touched()
            ? (persistent ? L.saved : L.sessionOnly)
            : (persistent ? L.ready : L.sessionOnly);
    }

    function say(message, sticky) {
        if (!hint || !message) return;
        hint.textContent = message;

        if (sticky) return;

        window.clearTimeout(say.timer);
        say.timer = window.setTimeout(function () {
            hint.textContent = mode === 'edit' ? L.hintEdit : L.hintPreview;
        }, 4000);
    }

    function reportRestored(saved) {
        if (!saved) return;

        var count = Object.keys(state.text).length + Object.keys(state.images).length + Object.keys(state.swatches).length;
        if (count) say(L.restored, true);
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function external(href) {
        if (!href) return false;
        if (/^(#|mailto:|tel:|javascript:)/i.test(href)) return false;

        return /^https?:\/\//i.test(href) && href.indexOf(window.location.origin) !== 0;
    }

    /* ════════════════════════════════════════════════════════════════
       A lap vezerloi
       ════════════════════════════════════════════════════════════════ */
    root.querySelectorAll('[data-pg-mode]').forEach(function (button) {
        button.addEventListener('click', function () { setMode(button.getAttribute('data-pg-mode')); });
    });

    root.querySelectorAll('[data-pg-width]').forEach(function (button) {
        button.addEventListener('click', function () {
            var width = parseInt(button.getAttribute('data-pg-width'), 10) || 0;

            shell.style.maxWidth = width ? width + 'px' : '';
            shell.classList.toggle('is-narrow', width > 0);

            root.querySelectorAll('[data-pg-width]').forEach(function (other) {
                var on = other === button;
                other.classList.toggle('is-active', on);
                other.setAttribute('aria-pressed', on ? 'true' : 'false');
            });

            // A keret merete valtozott, a lebego gombok helye is.
            if (frameWin) window.setTimeout(placeMarkers, 220);
        });
    });

    root.querySelectorAll('[data-pg-swatch]').forEach(function (input) {
        input.addEventListener('input', function () {
            applySwatch(input.getAttribute('data-pg-swatch'), input.value, false);
        });
        input.addEventListener('change', function () {
            applySwatch(input.getAttribute('data-pg-swatch'), input.value, true);
        });
    });

    if (undoButton) undoButton.addEventListener('click', stepBack);

    if (resetButton) {
        resetButton.addEventListener('click', function () {
            if (!touched()) return;
            if (!window.confirm(L.confirmReset)) return;

            dropState().then(function () {
                state = blank();
                undo = [];
                // Ujratoltes: igy biztosan az eredeti lap all vissza,
                // minden foltozas nelkul.
                frame.setAttribute('src', config.src);
            });
        });
    }

    if (panel) {
        var fileInput = panel.querySelector('[data-pg-file]');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                var path = panel.getAttribute('data-pg-path');
                var file = fileInput.files && fileInput.files[0];
                if (!path || !file) return;

                say(L.working, true);

                processFile(file).then(function (record) {
                    replaceImage(path, record);
                }, function (error) {
                    var reason = error && error.message === 'size' ? L.tooBig
                        : error && error.message === 'type' ? L.notImage
                            : L.imageFailed;
                    say(reason, true);
                });
            });
        }

        var linkInput = panel.querySelector('[data-pg-link]');
        var linkButton = panel.querySelector('[data-pg-apply-link]');
        if (linkButton && linkInput) {
            linkButton.addEventListener('click', function () {
                var path = panel.getAttribute('data-pg-path');
                var value = linkInput.value.trim();

                if (!path || !value) return;
                if (!/^https?:\/\//i.test(value)) {
                    say(L.badLink, true);

                    return;
                }

                replaceImage(path, { url: value });
            });
        }

        var clearButton = panel.querySelector('[data-pg-clear]');
        if (clearButton) {
            clearButton.addEventListener('click', function () {
                var path = panel.getAttribute('data-pg-path');
                if (path) resetImage(path);
            });
        }

        var closeButton = panel.querySelector('[data-pg-close]');
        if (closeButton) closeButton.addEventListener('click', deselect);
    }

    /* ── Indulas ────────────────────────────────────────────────────
       A keret betoltese elobb is befejezodhetett, mint hogy ez a fajl
       lefusson (gyorsitotarbol jovo demonal ez a szokasos), ezert a
       load esemeny mellett a kesz allapotot is meg kell nezni. */
    frame.addEventListener('load', function () {
        booted = false;
        boot();
    });

    try {
        if (!booted && frame.contentDocument && frame.contentDocument.readyState === 'complete') boot();
    } catch (e) {
        // Masik originrol jovo keret - itt nem fordulhat elo, de a
        // hozzaferes-hiba nem viheti el az egesz szkriptet.
    }

    window.addEventListener('beforeunload', function () {
        objectUrls.forEach(function (url) { URL.revokeObjectURL(url); });
    });
})();

/**
 * Mozgas-mag.
 * ----------------------------------------------------------------------------
 * Az egesz oldalon EGYETLEN requestAnimationFrame ciklus fut, es azon belul is
 * ket elkulonitett fazis: eloszor MINDEN fogyaszto mer (olvas a DOM-bol), utana
 * MINDEN fogyaszto ir. Igy egy kepkockan belul soha nincs olvasas-iras-olvasas
 * sorrend, vagyis nincs kenyszeritett ujraszamolas (layout thrashing).
 *
 * A korabbi felallasban ez csak a kommentben volt igaz: az onScrollFrame minden
 * hivasnal sajat gorgetes-figyelot es sajat rAF-lancot nyitott, a parallax pedig
 * elemenkent valtogatta az olvasast es az irast. Negy parhuzamos hurok futott,
 * es minden parallax-elem kikenyszeritett egy elrendezes-szamolast.
 *
 * A ciklus alapbol ALL. Csak akkor indul, ha tortent valami (gorgetes,
 * atmeretezes), vagy ha egy folyamatos fogyaszto tartja (mozgo magneses gomb).
 * Ha nincs dolga, nem eszik akkumulatort.
 *
 * A JS tovabbra is kizarolag osztalyt es CSS-valtozot ir - az idozitest es a
 * gorbeket a motion.css hatarozza meg.
 */
(function () {
    'use strict';

    var reduceQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    var pointerQuery = window.matchMedia('(hover: hover) and (pointer: fine)');

    /* ---------------------------------------------------------------
       1. Kozos allapot
       Amit tobb fogyaszto is hasznalna, azt egyszer merjuk meg, es innen
       olvassak. A scrollHeight kulonosen draga - minden lekerdezese
       ujraszamoltatja az elrendezest -, ezert csak atmeretezeskor frissul,
       nem kepkockankent.
       --------------------------------------------------------------- */
    var view = {
        scrollY: window.pageYOffset,
        lastY: window.pageYOffset,
        viewportH: window.innerHeight,
        viewportW: window.innerWidth,
        maxScroll: 0,
        reduced: reduceQuery.matches,
        fine: pointerQuery.matches
    };

    var readers = [];
    var writers = [];
    var reduceHandlers = [];
    var handle = 0;
    var holds = 0;

    /* ---------------------------------------------------------------
       2. A hurok
       --------------------------------------------------------------- */
    function request() {
        if (handle) return;
        handle = window.requestAnimationFrame(tick);
    }

    function tick() {
        handle = 0;

        view.lastY = view.scrollY;
        view.scrollY = window.pageYOffset;

        var i;

        // Merofazis: ide KIZAROLAG DOM-olvasas kerulhet.
        for (i = 0; i < readers.length; i++) readers[i](view);

        // Irofazis: ide KIZAROLAG DOM-iras kerulhet.
        for (i = 0; i < writers.length; i++) writers[i](view);

        // Folyamatos fogyaszto (pl. epp mozgo magneses gomb) tovabb hajtja.
        if (holds > 0) request();
    }

    /**
     * Fogyaszto felvetele. A ket fuggveny szandekosan kulon all: ami mer, az
     * nem irhat, ami ir, az nem merhet. Ez a szabaly tartja a kepkockat
     * egyetlen elrendezes-szamolason. Barmelyik elhagyhato.
     */
    function subscribe(reader, writer) {
        if (reader) readers.push(reader);
        if (writer) writers.push(writer);
        request();

        return function () {
            var r = reader ? readers.indexOf(reader) : -1;
            var w = writer ? writers.indexOf(writer) : -1;
            if (r > -1) readers.splice(r, 1);
            if (w > -1) writers.splice(w, 1);
        };
    }

    // Folyamatos futas kerese es elengedese (lerp-elo animaciokhoz).
    function hold() {
        holds++;
        request();
    }

    function release() {
        if (holds > 0) holds--;
    }

    /* ---------------------------------------------------------------
       3. Nezet-meres
       Esemenyido alatt fut, nem kepkockan belul - itt szabad olvasni.
       --------------------------------------------------------------- */
    function measure() {
        view.scrollY = window.pageYOffset;
        view.viewportH = window.innerHeight;
        view.viewportW = window.innerWidth;
        view.maxScroll = Math.max(0, document.documentElement.scrollHeight - view.viewportH);
    }

    /* ---------------------------------------------------------------
       4. Media-lekerdezesek elo kovetese
       A csokkentett mozgas korabban csak betolteskor olvasodott ki, igy a
       rendszerbeallitas atkapcsolasa ujratoltesig nem latszott.
       --------------------------------------------------------------- */
    function watchQuery(query, onChange) {
        if (query.addEventListener) query.addEventListener('change', onChange);
        else if (query.addListener) query.addListener(onChange);
    }

    watchQuery(reduceQuery, function (event) {
        view.reduced = event.matches;
        for (var i = 0; i < reduceHandlers.length; i++) reduceHandlers[i](view.reduced);
        request();
    });

    watchQuery(pointerQuery, function (event) {
        view.fine = event.matches;
    });

    function onReduceChange(fn) {
        reduceHandlers.push(fn);
    }

    /* ---------------------------------------------------------------
       5. Segedek
       --------------------------------------------------------------- */
    function lerp(from, to, amount) {
        return from + (to - from) * amount;
    }

    function clamp(value, min, max) {
        return value < min ? min : (value > max ? max : value);
    }

    /* ---------------------------------------------------------------
       6. Kulso vezerles: gorgetes es atmeretezes
       A gorgetes-figyelo passziv, es egyetlen darab van belole az egesz
       oldalon - csak annyit tesz, hogy kepkockat ker.
       --------------------------------------------------------------- */
    window.addEventListener('scroll', request, { passive: true });

    window.addEventListener('resize', function () {
        measure();
        request();
    }, { passive: true });

    /* A dokumentum magassaga keptol, betutipustol, nyilo harmonikatol is
       valtozik - ilyenkor a gorgetesi maximum es a parallax alappontok
       elavulnak. A ResizeObserver ezt elkapja, a resize esemeny nem. */
    if ('ResizeObserver' in window) {
        new ResizeObserver(function () {
            measure();
            request();
        }).observe(document.documentElement);
    }

    measure();

    /* ---------------------------------------------------------------
       7. Nyilvanos felulet
       Innen hasznalja a site.js es barmely oldal-specifikus szkript, hogy
       ne nyisson sajat gorgetes-figyelot.
       --------------------------------------------------------------- */
    var motion = {
        view: view,
        subscribe: subscribe,
        request: request,
        measure: measure,
        hold: hold,
        release: release,
        onReduceChange: onReduceChange,
        lerp: lerp,
        clamp: clamp
    };

    window.blcktMotion = motion;

    /* ============================================================================
       MODULOK
       ============================================================================ */

    document.addEventListener('DOMContentLoaded', function () {
        initSmoothScroll();

        // A darabolas a reveal ELOTT fut: az observer mar a kesz
        // darabokat kapja, kulonben olyan elemet figyelne, aminek meg
        // nincsenek darabjai.
        initSplit();
        initReveal();

        initScrollProgress();
        initParallax();
        initVelocity();
        initMarquee();
        initCounters();
        initMagnetic();
        initCursor();
        initPageTransition();
        measure();
    });

    /* ---------------------------------------------------------------
       8. Lagy gorgetes (Lenis) - opcionalis
       Csak akkor lep be, ha a konyvtar be van toltve. Fontos, hogy a Lenis
       sajat rAF-hurkat NE inditsuk el: a mi egyetlen ciklusunk hajtja,
       kulonben megint ket hurok versenyezne egymassal.
       --------------------------------------------------------------- */
    function initSmoothScroll() {
        if (!window.Lenis || view.reduced) return;

        // Erintokepernyon a natv gorgetes jobb, mint barmilyen emulacio.
        if (!view.fine) return;

        var lenis = new window.Lenis({
            duration: 1.05,
            easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); },
            smoothWheel: true,
            syncTouch: false
        });

        lenis.on('scroll', request);

        /* A ciklus innentol folyamatosan fut, mert a Lenis-nek minden
           kepkockan lepnie kell. Ez az egyetlen allando fogyaszto. */
        hold();
        subscribe(null, function () {
            lenis.raf(window.performance.now());
        });

        motion.lenis = lenis;

        onReduceChange(function (reduced) {
            if (!reduced || !motion.lenis) return;
            motion.lenis.destroy();
            motion.lenis = null;
            release();
        });
    }

    /* ---------------------------------------------------------------
       9. Belepo animaciok
       Egyszer futnak le, utana levalunk az elemrol. A tranzicio vegen az
       .is-settled elengedi a kompozit reteget - egy hosszu oldalon
       kulonben szazaval maradnanak nyitva a GPU-retegek.
       --------------------------------------------------------------- */
    function initReveal() {
        var targets = document.querySelectorAll(
            '[data-reveal], [data-reveal-group], [data-split], [data-unveil]'
        );
        if (targets.length === 0) return;

        function settleAll() {
            targets.forEach(function (el) { el.classList.add('is-in', 'is-settled'); });
        }

        if (view.reduced || !('IntersectionObserver' in window)) {
            settleAll();
            return;
        }

        // A csoportok gyerekei kapjak a sorszamot, amibol a lepcso szamolodik.
        document.querySelectorAll('[data-reveal-group]').forEach(function (group) {
            Array.prototype.forEach.call(group.children, function (child, index) {
                child.style.setProperty('--reveal-index', Math.min(index, 6));
            });
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                var el = entry.target;
                el.classList.add('is-in');
                observer.unobserve(el);

                el.addEventListener('transitionend', function onEnd() {
                    el.classList.add('is-settled');
                    el.removeEventListener('transitionend', onEnd);
                }, { once: true });
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -80px 0px' });

        targets.forEach(function (el) { observer.observe(el); });

        // Ha kozben bekapcsoljak a csokkentett mozgast, minden alljon a helyere.
        onReduceChange(function (reduced) {
            if (!reduced) return;
            observer.disconnect();
            settleAll();
        });
    }

    /* ---------------------------------------------------------------
       10. Gorgetesi folyamatjelzo
       Csak ir. A maxScroll a kozos allapotbol jon, igy a korabbi
       kepkockankenti scrollHeight-olvasas (= kenyszeritett elrendezes-
       szamolas minden egyes kepkockan) teljesen eltunt.
       --------------------------------------------------------------- */
    function initScrollProgress() {
        var bar = document.querySelector('.scroll-progress span');
        if (!bar) return;

        var applied = -1;

        subscribe(null, function (v) {
            var ratio = v.maxScroll > 0 ? clamp(v.scrollY / v.maxScroll, 0, 1) : 0;
            var next = Math.round(ratio * 1000) / 1000;

            // Azonos ertek ujrairasa felesleges stilus-ervenyesitest hozna.
            if (next === applied) return;
            applied = next;
            bar.style.transform = 'scaleX(' + next + ')';
        });
    }

    /* ---------------------------------------------------------------
       11. Parallax
       ----------------------------------------------------------------
       Kepkockankent NULLA DOM-olvasas. Az elem dokumentumhoz kepesti
       pozicioja csak atrendezeskor valtozik, ezert egyszer merunk, es utana
       mar csak a gorgetes-ertekbol szamolunk.
       A will-change is csak addig all fenn, amig az elem a nezet kozeleben
       van - az allandoan nyitott kompozit reteg GPU-memoriaba kerul.
       --------------------------------------------------------------- */
    function initParallax() {
        var nodes = document.querySelectorAll('[data-parallax]');
        if (nodes.length === 0) return;

        // Erintokepernyon a parallax tobbet art, mint hasznal (jitter).
        if (!view.fine) return;

        var items = [];

        nodes.forEach(function (el) {
            items.push({
                el: el,
                speed: parseFloat(el.dataset.parallax) || 0.15,
                top: 0,       // dokumentumhoz kepesti pozicio
                height: 0,
                applied: 0,   // amit legutobb kiirtunk
                live: false
            });
        });

        /* A rect a MAR kiirt eltolast is tartalmazza, ezert azt le kell vonni -
           kulonben minden ujrameres tovabb csusztatna az elemet. */
        function remeasure() {
            var y = window.pageYOffset;
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                var rect = item.el.getBoundingClientRect();
                item.top = rect.top + y - item.applied;
                item.height = rect.height;
            }
        }

        // Csak a nezet koruli elemek szamolnak es tartanak kompozit reteget.
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                for (var i = 0; i < items.length; i++) {
                    if (items[i].el !== entry.target) continue;
                    items[i].live = entry.isIntersecting;
                    entry.target.classList.toggle('is-live', entry.isIntersecting);
                    break;
                }
            });
            request();
        }, { rootMargin: '20% 0px 20% 0px' });

        items.forEach(function (item) { observer.observe(item.el); });

        subscribe(null, function (v) {
            if (v.reduced) return;

            var half = v.viewportH / 2;

            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (!item.live) continue;

                var top = item.top - v.scrollY;             // = rect.top, szamolva
                var progress = (top + item.height / 2 - half) / v.viewportH;
                var y = progress * item.speed * -100;

                // A szemmel nem lathato kulonbseget nem irjuk ki.
                if (Math.abs(y - item.applied) < 0.05) continue;

                item.applied = y;
                item.el.style.setProperty('--parallax-y', y.toFixed(2) + 'px');
            }
        });

        window.addEventListener('resize', remeasure, { passive: true });
        window.addEventListener('load', remeasure);

        if ('ResizeObserver' in window) {
            new ResizeObserver(remeasure).observe(document.documentElement);
        }

        remeasure();

        onReduceChange(function (reduced) {
            if (!reduced) return;
            observer.disconnect();
            items.forEach(function (item) {
                item.live = false;
                item.applied = 0;
                item.el.classList.remove('is-live');
                item.el.style.removeProperty('--parallax-y');
            });
        });
    }

    /* ---------------------------------------------------------------
       12. Magneses elemek
       ----------------------------------------------------------------
       A mutato fele huzodo gomb. A kulcs az, hogy a pointermove NEM ir es
       NEM mer: csak celkoordinatat allit. A doboz meretet belepeskor
       olvassuk ki egyszer, a tenyleges mozgast pedig a kozos ciklus
       simitja - igy a mozdulat sose eroltet ki elrendezest, es a
       tehetetlenseg adja a finom erzetet, nem a nagy elmozdulas.
       --------------------------------------------------------------- */
    function initMagnetic() {
        var nodes = document.querySelectorAll('[data-magnetic]');
        if (nodes.length === 0 || !view.fine || view.reduced) return;

        var items = [];

        function start(item) {
            if (item.running) return;
            item.running = true;
            hold();
        }

        nodes.forEach(function (el) {
            var item = {
                el: el,
                strength: parseFloat(el.dataset.magnetic) || 0.28,
                rect: null,
                hover: false,
                running: false,
                x: 0, y: 0,    // aktualis
                tx: 0, ty: 0   // cel
            };

            function leave() {
                item.hover = false;
                item.tx = 0;
                item.ty = 0;
                start(item);
            }

            el.addEventListener('pointerenter', function (event) {
                if (event.pointerType !== 'mouse') return;
                item.rect = el.getBoundingClientRect();   // esemenyido, nem kepkocka
                item.hover = true;
                start(item);
            });

            el.addEventListener('pointermove', function (event) {
                if (!item.hover || !item.rect) return;
                var r = item.rect;
                item.tx = (event.clientX - (r.left + r.width / 2)) * item.strength;
                item.ty = (event.clientY - (r.top + r.height / 2)) * item.strength;
            });

            el.addEventListener('pointerleave', leave);
            el.addEventListener('blur', leave);

            items.push(item);
        });

        subscribe(null, function () {
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (!item.running) continue;

                item.x = lerp(item.x, item.tx, 0.16);
                item.y = lerp(item.y, item.ty, 0.16);

                // Visszatert nyugalomba: elengedjuk a ciklust es a reteget.
                if (!item.hover && Math.abs(item.x) < 0.08 && Math.abs(item.y) < 0.08) {
                    item.x = 0;
                    item.y = 0;
                    item.running = false;
                    item.el.classList.remove('is-magnetic');
                    release();
                } else {
                    item.el.classList.add('is-magnetic');
                }

                item.el.style.setProperty('--mag-x', item.x.toFixed(2) + 'px');
                item.el.style.setProperty('--mag-y', item.y.toFixed(2) + 'px');
            }
        });
    }

    /* ---------------------------------------------------------------
       13. Kinetikus tipografia
       ----------------------------------------------------------------
       A cimsort szavakra (vagy betukre) bontjuk, es a darabok egymas utan
       usznak fel a sajat levagott savukbol. Egy tipografia-vezerelt oldalon
       ez a legerosebb belepo, mert magat a tartalmat mozgatja, nem egy
       dobozt korulotte.

       KEPERNYOOLVASO: a darabolt szoveg egyben marad. A darabok
       aria-hidden-ek, a teljes mondat pedig az aria-label-bol jon -
       kulonben a felolvaso szavankent vagy betunkent daralna fel.
       --------------------------------------------------------------- */
    function initSplit() {
        /* Tobbsoros cimsornal minden sor kulon elem, es a sorszamozas
           soronkent ujraindulna - a masodik sor egyszerre lepne be az
           elsovel. Ezert a testverek darabszamat gorgetjuk tovabb.

           Ezt SZANDEKOSAN nem a jelolesbe irt fix szam adja: a szavak
           szama forditasonkent mas (a magyar sor rendszerint rovidebb),
           igy egy bedrotozott eltolas az egyik nyelven mindig hibas lenne. */
        var lastParent = null;
        var running = 0;

        document.querySelectorAll('[data-split]').forEach(function (el) {
            if (el.querySelector('.split-part')) return;

            /* Csak sima szoveget darabolunk. Ha van benne elem - link,
               kiemeles -, a darabolas szetszedne a jelolest, es eppen azt a
               linket tennenk tonkre, amiert a sor ott van. */
            if (el.children.length > 0) return;

            var text = el.textContent.replace(/\s+/g, ' ').trim();
            if (!text) return;

            var parent = el.parentElement;
            if (parent !== lastParent) {
                lastParent = parent;
                running = 0;
            }

            var chars = el.dataset.split === 'chars';
            var units = chars ? text.split('') : text.split(' ');
            var frag = document.createDocumentFragment();
            var index = 0;

            el.setAttribute('aria-label', text);

            // A jelolesben megadott ertek elsobbseget elvez.
            if (!el.style.getPropertyValue('--split-offset')) {
                el.style.setProperty('--split-offset', running);
            }

            units.forEach(function (unit, i) {
                if (unit === ' ') {
                    frag.appendChild(document.createTextNode(' '));
                    return;
                }

                var part = document.createElement('span');
                part.className = 'split-part';
                part.setAttribute('aria-hidden', 'true');
                part.style.setProperty('--split-index', index++);

                var inner = document.createElement('span');
                inner.className = 'split-inner';
                inner.textContent = unit;

                part.appendChild(inner);
                frag.appendChild(part);

                /* Szavak kozott VALODI szokoz all, nem margo: igy a sortores
                   termeszetes marad. A hosszu magyar osszetett szavaknal ez
                   szamit - egy nem-toro megoldas kiszoritana a sort. */
                if (!chars && i < units.length - 1) {
                    frag.appendChild(document.createTextNode(' '));
                }
            });

            el.textContent = '';
            el.appendChild(frag);

            running += index;
        });
    }

    /* ---------------------------------------------------------------
       14. Gorgetesi sebesseg
       ----------------------------------------------------------------
       A gyors gorgetes alatt a kepek egy hajszalnyit megdolnek, majd
       megallaskor visszasimulnak. Ez adja azt az erzetet, hogy az oldalnak
       van tomege - ettol tunik a mozgas anyagszerunek, nem kapcsolgatottnak.

       A dolest SZANDEKOSAN nem a :root-ra irjuk. Egy custom property a
       gyokeren az EGESZ fanak ervenytelenitene a stilusat minden
       kepkockan; igy viszont csak az az egy-ket elem szamol ujra, amelyik
       tenylegesen hasznalja.
       --------------------------------------------------------------- */
    function initVelocity() {
        var nodes = document.querySelectorAll('[data-skew]');
        if (nodes.length === 0 || view.reduced) return;

        var items = [];

        nodes.forEach(function (el) {
            items.push({
                el: el,
                max: parseFloat(el.dataset.skew) || 3,
                applied: 0
            });
        });

        var smoothed = 0;
        var holding = false;

        subscribe(null, function (v) {
            // A nyers kepkockankenti elmozdulas ugral, ezert simitjuk.
            var raw = clamp((v.scrollY - v.lastY) / 45, -1, 1);
            smoothed = lerp(smoothed, raw, 0.12);

            if (Math.abs(smoothed) < 0.002) smoothed = 0;

            /* Amig van maradek doles, a hurkot ebren kell tartani. Enelkul a
               gorgetes vegen megallna a ciklus, es az utolso ferde allapot
               beleragadna a kepbe. */
            if (smoothed !== 0 && !holding) {
                holding = true;
                hold();
            } else if (smoothed === 0 && holding) {
                holding = false;
                release();
            }

            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                var value = smoothed * item.max;

                if (Math.abs(value - item.applied) < 0.01) continue;

                item.applied = value;
                item.el.style.setProperty('--skew', value.toFixed(3) + 'deg');
            }
        });
    }

    /* ---------------------------------------------------------------
       15. Vegtelen szalag
       ----------------------------------------------------------------
       Folyamatosan sodrodo szoveg, aminek a sebessegebe BELEJATSZIK a
       gorgetes: lefele haladva gyorsul, felfele lassul, sot megfordul.
       Ettol lesz a szalag az oldal resze, nem pedig egy fuggetlenul jaro
       disz - es ez a kulonbseg az, amit az ember eszrevesz, meg ha nem is
       tudja megnevezni.
       --------------------------------------------------------------- */
    function initMarquee() {
        document.querySelectorAll('[data-marquee]').forEach(function (el) {
            var track = el.querySelector('.marquee-track');
            if (!track || el.querySelector('.marquee-row')) return;

            /* A vegtelen szalaghoz a tartalom KETSZER kell: mire az elso
               peldany kifutott, a masodik mar pontosan a helyen all, igy a
               visszaugras lathatatlan. A masolat aria-hidden, kulonben a
               felolvaso mindent ketszer mondana. */
            var row = document.createElement('div');
            row.className = 'marquee-row';
            el.insertBefore(row, track);
            row.appendChild(track);

            var clone = track.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            row.appendChild(clone);

            if (view.reduced) return;

            var speed = parseFloat(el.dataset.marquee) || 40;   // keppont / masodperc
            var x = 0;
            var width = 0;
            var last = 0;
            var live = false;
            var holding = false;

            function remeasure() {
                width = track.offsetWidth;
            }

            subscribe(null, function (v) {
                if (!live || width <= 0) return;

                var now = window.performance.now();
                // Az elso kepkockan es lapful-valtas utan a delta hatalmas
                // lenne; a felso hatar megakadalyozza, hogy a szalag ugorjon.
                var delta = last ? Math.min((now - last) / 1000, 0.05) : 0;
                last = now;

                var boost = clamp((v.scrollY - v.lastY) * 0.6, -70, 70);
                x -= (speed + boost) * delta;

                // Korbeforgatas egy peldany szelessegenel.
                if (x <= -width) x += width;
                if (x > 0) x -= width;

                row.style.setProperty('--marquee-x', x.toFixed(2) + 'px');
            });

            // Nezeten kivul nincs mit sodorni - ilyenkor elengedjuk a hurkot.
            if ('IntersectionObserver' in window) {
                new IntersectionObserver(function (entries) {
                    var visible = entries[0].isIntersecting;
                    if (visible === live) return;

                    live = visible;

                    if (live) {
                        last = 0;
                        if (!holding) { holding = true; hold(); }
                    } else if (holding) {
                        holding = false;
                        release();
                    }

                    request();
                }).observe(el);
            } else {
                live = true;
                holding = true;
                hold();
            }

            window.addEventListener('resize', remeasure, { passive: true });
            window.addEventListener('load', remeasure);
            remeasure();

            onReduceChange(function (reduced) {
                if (!reduced || !holding) return;
                holding = false;
                live = false;
                release();
                row.style.removeProperty('--marquee-x');
            });
        });
    }

    /* ---------------------------------------------------------------
       16. Szamlalok
       ----------------------------------------------------------------
       Korabban minden szamlalo sajat rAF-lancot inditott (about.js); most
       mind a kozos ciklusban fut, es csak addig tartja azt, amig szamol.

       A textContent minden irasa ujraszamoltatja az elem elrendezeset,
       ezert csak akkor irunk, ha a KIIRANDO szam tenylegesen valtozott -
       egy 1400 ms-os felfutas alatt kulonben ugyanazt az erteket irnank ki
       tobb tucat kepkockan keresztul.
       --------------------------------------------------------------- */
    function initCounters() {
        var items = [];

        document.querySelectorAll('.stat-number').forEach(function (el) {
            var target = parseFloat(el.dataset.target);
            if (isNaN(target)) return;

            items.push({
                el: el,
                target: target,
                suffix: el.dataset.suffix || '',
                started: 0,
                running: false,
                shown: null
            });
        });

        if (items.length === 0) return;

        function finish(item) {
            item.el.textContent = item.target + item.suffix;
        }

        if (view.reduced || !('IntersectionObserver' in window)) {
            items.forEach(finish);
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                for (var i = 0; i < items.length; i++) {
                    if (items[i].el !== entry.target) continue;
                    items[i].running = true;
                    items[i].started = window.performance.now();
                    hold();
                    break;
                }

                observer.unobserve(entry.target);
            });
        }, { threshold: 0.4 });

        items.forEach(function (item) { observer.observe(item.el); });

        subscribe(null, function () {
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (!item.running) continue;

                var progress = Math.min((window.performance.now() - item.started) / 1400, 1);
                var eased = 1 - Math.pow(1 - progress, 4);
                var value = Math.round(item.target * eased);

                if (value !== item.shown) {
                    item.shown = value;
                    item.el.textContent = value + item.suffix;
                }

                if (progress >= 1) {
                    item.running = false;
                    release();
                }
            }
        });

        onReduceChange(function (reduced) {
            if (!reduced) return;
            observer.disconnect();
            items.forEach(function (item) {
                if (item.running) {
                    item.running = false;
                    release();
                }
                finish(item);
            });
        });
    }

    /* ---------------------------------------------------------------
       17. Mutato-gyuru
       ----------------------------------------------------------------
       Egy halkan kesve koveto gyuru a mutato korul, ami interaktiv elem
       folott kitagul. A natv kurzort SZANDEKOSAN nem rejtjuk el: egy
       eltuntetett egermutato latvanyos, de minden kattintast bizonytalanna
       tesz - ez a gyuru kiseri a mutatot, nem helyettesiti.

       Nyugalomban elengedi a hurkot, igy az allo eger mellett nem fut
       vegtelen ciklus.
       --------------------------------------------------------------- */
    function initCursor() {
        if (!view.fine || view.reduced) return;
        if (document.querySelector('.cursor-ring')) return;

        var ring = document.createElement('div');
        ring.className = 'cursor-ring';
        ring.setAttribute('aria-hidden', 'true');
        document.body.appendChild(ring);

        var x = 0, y = 0, tx = 0, ty = 0;
        var seen = false;
        var running = false;

        function wake() {
            if (running) return;
            running = true;
            hold();
        }

        document.addEventListener('pointermove', function (event) {
            if (event.pointerType !== 'mouse') return;

            tx = event.clientX;
            ty = event.clientY;

            // Elso mozdulatnal odatesszuk, nem odarepitjuk.
            if (!seen) {
                seen = true;
                x = tx;
                y = ty;
                ring.classList.add('is-live');
            }

            wake();
        }, { passive: true });

        document.addEventListener('pointerover', function (event) {
            var target = event.target.closest
                ? event.target.closest('a, button, [role="button"], input, textarea, summary')
                : null;
            ring.classList.toggle('is-over', !!target);
        }, { passive: true });

        document.addEventListener('pointerleave', function () {
            ring.classList.remove('is-live');
        });

        document.addEventListener('pointerenter', function () {
            if (seen) ring.classList.add('is-live');
        });

        subscribe(null, function () {
            if (!running) return;

            x = lerp(x, tx, 0.18);
            y = lerp(y, ty, 0.18);

            // Beert a mutatot: nincs tovabb mit szamolni, elengedjuk a hurkot.
            if (Math.abs(tx - x) < 0.1 && Math.abs(ty - y) < 0.1) {
                x = tx;
                y = ty;
                running = false;
                release();
            }

            ring.style.setProperty('--cursor-x', x.toFixed(2) + 'px');
            ring.style.setProperty('--cursor-y', y.toFixed(2) + 'px');
        });

        onReduceChange(function (reduced) {
            if (!reduced) return;
            if (running) { running = false; release(); }
            ring.remove();
        });
    }

    /* ---------------------------------------------------------------
       18. Oldalvaltas
       ----------------------------------------------------------------
       Ahol a bongeszo tudja a dokumentumok kozotti nezetvaltast, ott a CSS
       @view-transition intezi: az atmenet valodi, es NEM ad kesest a
       kattintashoz. A kezi kihalvanyitas csak ott marad, ahol ez nincs meg -
       ott a 160 ms az ara annak, hogy a festes valtasa ne vagja el a mozgast.
       --------------------------------------------------------------- */
    function initPageTransition() {
        // A dokumentumok kozotti nezetvaltas a pagereveal esemennyel egyutt
        // erkezett a bongeszokbe, igy ez megbizhato jelzo ra.
        if ('onpagereveal' in window) return;
        if (view.reduced) return;

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

            setTimeout(function () { window.location.href = link.href; }, 160);
        });

        // Vissza-gomb utan a bfcache visszaadhatja a kihalvanyitott allapotot.
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) document.body.classList.remove('is-leaving');
        });
    }
})();

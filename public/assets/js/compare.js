/**
 * Elotte/utana osszehasonlito.
 *
 * A csusztatas a --compare-pos CSS valtozon keresztul tortenik (0-100), amit a
 * clip-path es a fogantyu pozicioja is olvas - igy egyetlen ertek mozgat mindent,
 * es a bongeszo a compositoron tudja tartani az animaciot.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-compare]').forEach(initCompare);
});

function initCompare(root) {
    var stage = root.querySelector('[data-compare-stage]');
    var handle = root.querySelector('[data-compare-handle]');
    if (!stage || !handle) return;

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var pos = parseFloat(root.dataset.compareStart) || 50;
    var dragging = false;
    var settleTimer = null;
    var peekTimers = [];

    // A bemutato mozgas azonnal leall, amint a latogato hozzanyul.
    function cancelPeek() {
        peekTimers.forEach(clearTimeout);
        peekTimers = [];
    }

    function setPos(next, animate) {
        pos = Math.min(100, Math.max(0, next));
        root.style.setProperty('--compare-pos', pos.toFixed(2));
        handle.setAttribute('aria-valuenow', String(Math.round(pos)));

        if (animate && !reduceMotion) {
            stage.classList.add('is-settling');
            clearTimeout(settleTimer);
            settleTimer = setTimeout(function () {
                stage.classList.remove('is-settling');
            }, 600);
        }
    }

    function posFromEvent(event) {
        var rect = stage.getBoundingClientRect();
        if (rect.width === 0) return pos;
        return ((event.clientX - rect.left) / rect.width) * 100;
    }

    function onPointerDown(event) {
        if (stage.classList.contains('is-side')) return;
        cancelPeek();
        // A pointerdown alapertelmezese le van tiltva (kulonben kepet huznank),
        // ezert a fokuszt kezzel adjuk at - igy huzas utan a nyilbillentyuk is elnek.
        handle.focus({ preventScroll: true });
        dragging = true;
        stage.classList.add('is-dragging');
        stage.classList.remove('is-settling');
        stage.setPointerCapture(event.pointerId);
        setPos(posFromEvent(event), false);
        event.preventDefault();
    }

    function onPointerMove(event) {
        if (!dragging) return;
        setPos(posFromEvent(event), false);
    }

    function onPointerUp(event) {
        if (!dragging) return;
        dragging = false;
        stage.classList.remove('is-dragging');
        if (stage.hasPointerCapture(event.pointerId)) {
            stage.releasePointerCapture(event.pointerId);
        }
    }

    stage.addEventListener('pointerdown', onPointerDown);
    stage.addEventListener('pointermove', onPointerMove);
    stage.addEventListener('pointerup', onPointerUp);
    stage.addEventListener('pointercancel', onPointerUp);

    // Billentyuzet: a fogantyu slider szerepben van, nyilakkal es Home/End-del mozog.
    handle.addEventListener('keydown', function (event) {
        cancelPeek();
        var step = event.shiftKey ? 10 : 2;
        var handled = true;

        switch (event.key) {
            case 'ArrowLeft': setPos(pos - step, false); break;
            case 'ArrowRight': setPos(pos + step, false); break;
            case 'Home': setPos(0, true); break;
            case 'End': setPos(100, true); break;
            default: handled = false;
        }

        if (handled) event.preventDefault();
    });

    // Mod valtas (csusztatas / egymas mellett)
    root.querySelectorAll('[data-compare-mode]').forEach(function (button) {
        button.addEventListener('click', function () {
            var mode = button.dataset.compareMode;
            cancelPeek();

            root.querySelectorAll('[data-compare-mode]').forEach(function (other) {
                var active = other === button;
                other.classList.toggle('is-active', active);
                other.setAttribute('aria-pressed', active ? 'true' : 'false');
            });

            stage.classList.toggle('is-side', mode === 'side');

            if (mode === 'slide') setPos(50, true);
        });
    });

    setPos(pos, false);

    // Elso lathatova valaskor egy rovid "kacsintas": megmutatja, hogy huzhato.
    if (reduceMotion || !('IntersectionObserver' in window)) {
        root.classList.add('is-ready');
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            observer.disconnect();
            root.classList.add('is-ready');

            peekTimers = [
                setTimeout(function () { setPos(72, true); }, 420),
                setTimeout(function () { setPos(34, true); }, 1080),
                setTimeout(function () { setPos(50, true); }, 1740),
            ];
        });
    }, { threshold: 0.35 });

    observer.observe(stage);
}

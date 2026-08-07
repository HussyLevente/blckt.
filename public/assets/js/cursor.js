document.addEventListener('DOMContentLoaded', function () {
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    document.documentElement.classList.add('custom-cursor-active');

    var cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    cursor.innerHTML = '<span class="custom-cursor-ring"></span><span class="custom-cursor-label"></span>';
    document.body.appendChild(cursor);

    var label = cursor.querySelector('.custom-cursor-label');
    var interactiveSelector = 'a, button, input, textarea, select, label, [role="button"], .btn-pill, .slider-btn, .slider-item img';

    var pointerX = 0;
    var pointerY = 0;
    var lastTarget = null;
    var frameRequested = false;

    function render() {
        frameRequested = false;
        cursor.classList.add('is-visible');
        cursor.style.transform = 'translate3d(' + pointerX + 'px, ' + pointerY + 'px, 0)';

        if (document.body.classList.contains('is-image-dragging')) {
            cursor.classList.add('is-draggable', 'is-dragging');
            cursor.classList.remove('is-interactive');
            label.textContent = '';
            return;
        }

        var draggableImg = lastTarget && lastTarget.closest ? lastTarget.closest('.lightbox-image.is-draggable') : null;
        if (draggableImg) {
            cursor.classList.add('is-draggable');
            cursor.classList.remove('is-dragging', 'is-interactive');
            label.textContent = 'drag';
            return;
        }

        cursor.classList.remove('is-draggable', 'is-dragging');
        var interactive = lastTarget && lastTarget.closest ? lastTarget.closest(interactiveSelector) : null;
        cursor.classList.toggle('is-interactive', !!interactive);
        label.textContent = interactive ? 'select' : '';
    }

    function scheduleRender() {
        if (!frameRequested) {
            frameRequested = true;
            requestAnimationFrame(render);
        }
    }

    document.addEventListener('mousemove', function (e) {
        pointerX = e.clientX;
        pointerY = e.clientY;
        lastTarget = e.target;
        scheduleRender();
    }, { passive: true });

    document.addEventListener('mouseleave', function () {
        cursor.classList.remove('is-visible');
    });
});

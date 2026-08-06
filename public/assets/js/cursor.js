document.addEventListener('DOMContentLoaded', function () {
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    document.documentElement.classList.add('custom-cursor-active');

    var cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    cursor.innerHTML = '<span class="custom-cursor-ring"></span><span class="custom-cursor-label"></span>';
    document.body.appendChild(cursor);

    var label = cursor.querySelector('.custom-cursor-label');
    var interactiveSelector = 'a, button, input, textarea, select, label, [role="button"], .btn-pill, .slider-btn, .slider-item img';

    document.addEventListener('mousemove', function (e) {
        cursor.classList.add('is-visible');
        cursor.style.transform = 'translate3d(' + e.clientX + 'px, ' + e.clientY + 'px, 0)';

        if (document.body.classList.contains('is-image-dragging')) {
            cursor.classList.add('is-draggable', 'is-dragging');
            cursor.classList.remove('is-interactive');
            label.textContent = '';
            return;
        }

        var draggableImg = e.target.closest('.lightbox-image.is-draggable');
        if (draggableImg) {
            cursor.classList.add('is-draggable');
            cursor.classList.remove('is-dragging', 'is-interactive');
            label.textContent = 'drag';
            return;
        }

        cursor.classList.remove('is-draggable', 'is-dragging');
        var interactive = e.target.closest(interactiveSelector);
        cursor.classList.toggle('is-interactive', !!interactive);
        label.textContent = interactive ? 'select' : '';
    });

    document.addEventListener('mouseleave', function () {
        cursor.classList.remove('is-visible');
    });
});

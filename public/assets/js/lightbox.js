document.addEventListener('DOMContentLoaded', function () {
    var lightbox = document.getElementById('site-lightbox');
    if (!lightbox) return;

    var stage = lightbox.querySelector('.lightbox-stage');
    var image = lightbox.querySelector('.lightbox-image');
    var closeBtn = lightbox.querySelector('.lightbox-close');
    var zoomInBtn = lightbox.querySelector('.lightbox-zoom-in');
    var zoomOutBtn = lightbox.querySelector('.lightbox-zoom-out');

    var MIN_ZOOM = 1;
    var MAX_ZOOM = 3;
    var ZOOM_STEP = 0.5;
    var zoom = MIN_ZOOM;
    var panX = 0;
    var panY = 0;

    var isDragging = false;
    var justDragged = false;
    var dragStartX = 0;
    var dragStartY = 0;
    var panStartX = 0;
    var panStartY = 0;

    function getMaxPan() {
        var stageRect = stage.getBoundingClientRect();
        return {
            x: Math.max(0, (stageRect.width * (zoom - 1)) / 2),
            y: Math.max(0, (stageRect.height * (zoom - 1)) / 2),
        };
    }

    function clampPan() {
        var maxPan = getMaxPan();
        panX = Math.min(maxPan.x, Math.max(-maxPan.x, panX));
        panY = Math.min(maxPan.y, Math.max(-maxPan.y, panY));
    }

    function render() {
        image.style.transform = 'translate(' + panX + 'px, ' + panY + 'px) scale(' + zoom + ')';
    }

    function applyZoom() {
        clampPan();
        render();
        lightbox.classList.toggle('is-zoomed', zoom > MIN_ZOOM);
        image.classList.toggle('is-draggable', zoom > MIN_ZOOM);
        zoomOutBtn.disabled = zoom <= MIN_ZOOM;
        zoomInBtn.disabled = zoom >= MAX_ZOOM;
    }

    function open(src, alt) {
        if (!src) return;
        image.src = src;
        image.alt = alt || '';
        zoom = MIN_ZOOM;
        panX = 0;
        panY = 0;
        applyZoom();
        lightbox.classList.add('is-open');
        document.body.classList.add('no-scroll');
    }

    function close() {
        lightbox.classList.remove('is-open');
        document.body.classList.remove('no-scroll');
        stopDrag();
    }

    function zoomIn() {
        zoom = Math.min(MAX_ZOOM, zoom + ZOOM_STEP);
        applyZoom();
    }

    function zoomOut() {
        zoom = Math.max(MIN_ZOOM, zoom - ZOOM_STEP);
        applyZoom();
    }

    function startDrag(e) {
        if (zoom <= MIN_ZOOM) return;
        isDragging = true;
        dragStartX = e.clientX;
        dragStartY = e.clientY;
        panStartX = panX;
        panStartY = panY;
        document.body.classList.add('is-image-dragging');
        lightbox.classList.add('is-dragging');
        e.preventDefault();
    }

    function onDragMove(e) {
        if (!isDragging) return;
        panX = panStartX + (e.clientX - dragStartX);
        panY = panStartY + (e.clientY - dragStartY);
        clampPan();
        render();
    }

    function stopDrag() {
        if (!isDragging) return;
        isDragging = false;
        justDragged = true;
        setTimeout(function () { justDragged = false; }, 0);
        document.body.classList.remove('is-image-dragging');
        lightbox.classList.remove('is-dragging');
    }

    document.addEventListener('click', function (e) {
        var galleryItem = e.target.closest('.project-gallery-item');
        if (galleryItem) {
            var galleryImg = galleryItem.querySelector('img');
            if (galleryImg) open(galleryImg.src, galleryImg.alt);
            return;
        }

        var sliderImg = e.target.closest('.slider-item img');
        if (sliderImg) {
            open(sliderImg.src, sliderImg.alt);
        }
    });

    image.addEventListener('mousedown', startDrag);
    window.addEventListener('mousemove', onDragMove);
    window.addEventListener('mouseup', stopDrag);
    window.addEventListener('resize', function () {
        if (lightbox.classList.contains('is-open')) {
            clampPan();
            render();
        }
    });

    zoomInBtn.addEventListener('click', zoomIn);
    zoomOutBtn.addEventListener('click', zoomOut);
    closeBtn.addEventListener('click', close);

    lightbox.addEventListener('click', function (e) {
        if (isDragging || justDragged) return;
        if (e.target === lightbox || e.target === stage) close();
    });

    document.addEventListener('keydown', function (e) {
        if (!lightbox.classList.contains('is-open')) return;
        if (e.key === 'Escape') close();
        if (e.key === '+' || e.key === '=') zoomIn();
        if (e.key === '-' || e.key === '_') zoomOut();
    });
});

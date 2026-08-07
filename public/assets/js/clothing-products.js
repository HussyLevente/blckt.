document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('product-search');
    if (searchInput) {
        var cards = Array.prototype.slice.call(document.querySelectorAll('.product-card[data-name]'));
        var placeholders = Array.prototype.slice.call(document.querySelectorAll('.product-card-placeholder'));
        var emptyMessage = document.getElementById('products-empty');

        searchInput.addEventListener('input', function () {
            var query = searchInput.value.trim().toLowerCase();
            var hasQuery = query.length > 0;
            var visibleCount = 0;

            cards.forEach(function (card) {
                var matches = card.dataset.name.indexOf(query) !== -1;
                card.hidden = hasQuery && !matches;
                if (!card.hidden) visibleCount++;
            });

            placeholders.forEach(function (placeholder) {
                placeholder.hidden = hasQuery;
            });

            if (emptyMessage) {
                emptyMessage.hidden = !(hasQuery && visibleCount === 0);
            }
        });
    }

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

    var sizeSwatches = document.querySelectorAll('.size-swatch');
    sizeSwatches.forEach(function (swatch) {
        swatch.addEventListener('click', function () {
            sizeSwatches.forEach(function (s) { s.classList.remove('is-selected'); });
            swatch.classList.add('is-selected');
        });
    });

    var buyBtn = document.querySelector('.buy-btn');
    if (buyBtn) {
        var resetTimer = null;
        buyBtn.addEventListener('click', function () {
            clearTimeout(resetTimer);
            buyBtn.textContent = buyBtn.dataset.confirmLabel;
            buyBtn.classList.add('is-confirmed');
            resetTimer = setTimeout(function () {
                buyBtn.textContent = buyBtn.dataset.defaultLabel;
                buyBtn.classList.remove('is-confirmed');
            }, 2000);
        });
    }
});

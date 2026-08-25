/**
 * Kollekcio-kereso es meretvalaszto.
 * Az osszecsukhato panelek kulon fajlban (accordion.js) elnek.
 */
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

            // Kereses kozben a "hamarosan" helyorzok csak zavarnanak
            placeholders.forEach(function (placeholder) {
                placeholder.hidden = hasQuery;
            });

            if (emptyMessage) {
                emptyMessage.hidden = !(hasQuery && visibleCount === 0);
            }
        });
    }

    var sizes = document.querySelectorAll('.size');
    sizes.forEach(function (size) {
        size.addEventListener('click', function () {
            sizes.forEach(function (s) {
                s.classList.remove('is-selected');
                s.setAttribute('aria-pressed', 'false');
            });
            size.classList.add('is-selected');
            size.setAttribute('aria-pressed', 'true');
        });
    });
});

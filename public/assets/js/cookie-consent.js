(function () {
    var STORAGE_KEY = 'blckt-cookie-consent';

    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('cookie-consent');
        if (!modal) {
            return;
        }

        var acceptBtn = document.getElementById('cookie-consent-accept');

        function hasConsented() {
            try {
                return localStorage.getItem(STORAGE_KEY) === 'accepted';
            } catch (e) {
                return false;
            }
        }

        function openModal() {
            modal.classList.add('is-open');
            document.body.classList.add('no-scroll');
        }

        function closeModal() {
            modal.classList.remove('is-open');
            document.body.classList.remove('no-scroll');
        }

        if (acceptBtn) {
            acceptBtn.addEventListener('click', function () {
                try {
                    localStorage.setItem(STORAGE_KEY, 'accepted');
                } catch (e) {}
                closeModal();
            });
        }

        document.querySelectorAll('[data-cookie-preferences]').forEach(function (el) {
            el.addEventListener('click', function (event) {
                event.preventDefault();
                openModal();
            });
        });

        if (!hasConsented()) {
            openModal();
        }
    });
})();

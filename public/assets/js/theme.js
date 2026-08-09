(function () {
    var STORAGE_KEY = 'blckt-theme';
    var toggle = document.getElementById('theme-toggle');
    if (!toggle) return;

    function currentTheme() {
        return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        toggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    }

    applyTheme(currentTheme());

    toggle.addEventListener('click', function () {
        var next = currentTheme() === 'dark' ? 'light' : 'dark';
        applyTheme(next);
        try { localStorage.setItem(STORAGE_KEY, next); } catch (e) {}
    });
})();

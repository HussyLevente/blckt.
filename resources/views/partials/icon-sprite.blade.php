{{--
    Ikon-keszlet egyetlen, rejtett SVG-ben. Minden ikon egy <symbol>, amit a
    lap tobb helyerol <use> hivatkozik - igy a rajz egyszer szerepel a
    dokumentumban, nem annyiszor, ahanyszor megjelenik, es nincs erte kulon
    halozati keres sem.

    A vonalvastagsag (1.8) es a lekerekites egyezik a fejlec tema-kapcsolojaval,
    hogy az ikonok egyetlen keszletnek latszodjanak.
--}}
<svg width="0" height="0" aria-hidden="true" focusable="false" style="position:absolute">
    <defs>
        <symbol id="i-instagram" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="5.2"></rect>
            <circle cx="12" cy="12" r="4"></circle>
            <circle cx="17.3" cy="6.7" r="1.15" fill="currentColor" stroke="none"></circle>
        </symbol>

        <symbol id="i-messenger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3.2c-5 0-9 3.7-9 8.2 0 2.5 1.2 4.7 3.2 6.2v3.2l3-1.6c.9.2 1.8.4 2.8.4 5 0 9-3.7 9-8.2s-4-8.2-9-8.2z"></path>
            <path d="M7.3 14.1l3.4-3.6 2 2 3.2-2-3.4 3.6-2-2-3.2 2z" fill="currentColor" stroke="none"></path>
        </symbol>

        <symbol id="i-fiverr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="5.2"></rect>
            <path d="M9.4 16.7v-6.5"></path>
            <path d="M9.4 10.2v-.7c0-1.1.7-1.8 1.8-1.8h.9"></path>
            <path d="M7.8 11.1h3.4"></path>
            <path d="M14.5 16.7v-5.6"></path>
            <circle cx="14.5" cy="8.4" r="0.95" fill="currentColor" stroke="none"></circle>
        </symbol>
    </defs>
</svg>

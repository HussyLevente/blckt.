@extends('layout')

@section('title', 'Adatvédelmi tájékoztató | blckt.')
@section('meta_description', 'Tájékoztató a blckt.hu weboldalon történő adatkezelésről, a sütik használatáról és az érintettek GDPR szerinti jogairól.')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="section shell" style="padding-top: calc(72px + var(--space-16))">
        <h1 class="t2 optical-left">Adatvédelmi tájékoztató</h1>
        <p class="t8 ink-faint">Hatályos: 2026. [hónap] [nap] · Verzió: 1.0</p>

        @if (app()->getLocale() !== 'hu')
            <p class="t8 ink-faint">{{ __('This page is only available in Hungarian.') }}</p>
        @endif

        <div class="prose">
            <h2>1. Az adatkezelő</h2>
            <div class="legal-card">
                <p><strong>Név:</strong> Hussy Levente Hunor egyéni vállalkozó (blckt.)</p>
                <p><strong>Székhely:</strong> [IRÁNYÍTÓSZÁM] Solymár, [utca, házszám]</p>
                <p><strong>Nyilvántartási szám:</strong> [NYILVÁNTARTÁSI SZÁM]</p>
                <p><strong>Adószám:</strong> [ADÓSZÁM]</p>
                <p><strong>E-mail:</strong> <a href="mailto:hello@blckt.hu">hello@blckt.hu</a></p>
                <p><strong>Telefon:</strong> <a href="tel:+36302552432">+36 30 255 2432</a></p>
            </div>
            <p>Adatvédelmi tisztviselő kijelölésére a szolgáltató nem köteles, és nem is jelölt ki ilyet. Adatvédelmi kérdésekben a fenti e-mail-címen lehet a szolgáltatóhoz fordulni.</p>

            <h2>2. Mit érdemes tudni elöljáróban</h2>
            <p>A blckt.hu <strong>nem használ analitikát, hirdetési követőkódot, közösségi pixelt és profilalkotást.</strong> A weboldalon nincs Google Analytics, Meta Pixel, Hotjar, reCAPTCHA vagy más mérőeszköz. Nincs felhasználói fiók, nincs regisztráció, nincs hírlevél és nincs fizetési rendszer.</p>
            <p>A weboldalon egyetlen olyan pont van, ahol Ön személyes adatot ad meg: a kapcsolatfelvételi űrlap. Az űrlapon beküldött adatok <strong>nem kerülnek adatbázisba</strong> — kizárólag e-mailben továbbítódnak.</p>

            <h2>3. A kapcsolatfelvételi űrlap</h2>

            <h3>3.1 Kezelt adatok</h3>
            <p>Az Ön által megadott adatok:</p>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Adat</th><th scope="col">Kötelező</th><th scope="col">Megjegyzés</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">Név</th><td>igen</td><td>legfeljebb 120 karakter</td></tr>
                        <tr><th scope="row">E-mail-cím</th><td>igen</td><td>legfeljebb 190 karakter</td></tr>
                        <tr><th scope="row">Keret (költségvetés)</th><td>nem</td><td>szám, opcionális</td></tr>
                        <tr><th scope="row">Üzenet</th><td>igen</td><td>legfeljebb 5000 karakter</td></tr>
                    </tbody>
                </table>
            </div>

            <p>Automatikusan hozzáadott adatok:</p>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Adat</th><th scope="col">Mi ez</th><th scope="col">Miért</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">Küldő oldal címe</th><td>annak az aloldalnak a webcíme, ahonnan az űrlapot elküldte</td><td>hogy az érdeklődés kontextusa kiderüljön</td></tr>
                        <tr><th scope="row">Beküldés időpontja</th><td>dátum és időpont</td><td>válaszadási határidő követése</td></tr>
                        <tr><th scope="row">Nyelv</th><td>magyar vagy angol</td><td>hogy a válasz a megfelelő nyelven érkezzen</td></tr>
                    </tbody>
                </table>
            </div>

            <p>Az űrlapon található egy rejtett, ember számára nem látható mező is (ún. honeypot), amely kizárólag automatizált levélszemét-küldő programok kiszűrésére szolgál. Az ide beírt tartalmat a rendszer nem tárolja és nem továbbítja.</p>

            <h3>3.2 Az adatkezelés célja és jogalapja</h3>
            <p><strong>Cél:</strong> az Ön megkeresésének megválaszolása, ajánlatadás, valamint a szerződéskötés előkészítése.</p>
            <p><strong>Jogalap:</strong> a GDPR 6. cikk (1) bekezdés b) pontja — a szerződés megkötését megelőzően az Ön kérésére történő lépések megtétele. Mivel Ön az űrlap kitöltésével kifejezetten ajánlatot vagy tájékoztatást kér, az adatkezelés ehhez szükséges.</p>
            <p>Amennyiben a megkeresés nem irányul szerződéskötésre (például általános kérdés), az adatkezelés jogalapja a GDPR 6. cikk (1) bekezdés f) pontja szerinti jogos érdek: a beérkező megkeresések megválaszolásához fűződő érdek.</p>

            <h3>3.3 Mi történik a beküldött adatokkal</h3>
            <ul>
                <li>Az üzenet e-mailben érkezik meg a hello@blckt.hu postafiókba, a Rackhost Zrt. levelezőszolgáltatásán keresztül. Az adatok nem kerülnek adatbázisba.</li>
                <li>Az Ön által megadott e-mail-címre automatikus visszaigazolás érkezik arról, hogy az üzenet megérkezett. Ez a levél kizárólag a visszaigazolást tartalmazza. A rendszer az e-mail-címet nem ellenőrzi, ezért ha Ön tévedésből más címét adta meg, az a cím kap egy visszaigazoló levelet — más adat nem kerül továbbításra.</li>
                <li>Ha a levélküldés során technikai hiba lép fel, a hiba a szerver hibanaplójába kerül. A hibanapló bejegyzése tartalmazhatja a beküldött adatokat.</li>
            </ul>

            <h3>3.4 Megőrzési idő</h3>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Eset</th><th scope="col">Megőrzési idő</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">A megkeresésből nem lesz szerződés</th><td>a megválaszolástól számított 1 év, ezután az e-mail törlésre kerül</td></tr>
                        <tr><th scope="row">A megkeresésből szerződés lesz</th><td>a számviteli bizonylatokhoz kapcsolódóan 8 év (2000. évi C. törvény 169. §)</td></tr>
                        <tr><th scope="row">Hibanaplók</th><td>30 nap, ezután automatikusan felülíródnak</td></tr>
                    </tbody>
                </table>
            </div>

            <h2>4. Technikai adatkezelés</h2>

            <h3>4.1 Munkamenet-nyilvántartás</h3>
            <p>A weboldal a látogató munkamenetét a szerver adatbázisában tárolja. A munkamenet-rekord kizárólag a biztonsági (CSRF) tokent és a választott nyelvet tartalmazza, más adatot nem. Élettartama 120 perc.</p>
            <p><strong>Jogalap:</strong> GDPR 6. cikk (1) f) — a weboldal biztonságos működtetéséhez fűződő jogos érdek.</p>

            <h3>4.2 IP-cím alapú visszaélésvédelem</h3>
            <p>A kapcsolatfelvételi űrlap percenként legfeljebb 5 beküldést enged ugyanarról az IP-címről. Ehhez a rendszer az IP-címet legfeljebb 1 percig tárolja az átmeneti tárban.</p>
            <p><strong>Jogalap:</strong> GDPR 6. cikk (1) f) — a levélszemét és a visszaélés elleni védelemhez fűződő jogos érdek.</p>

            <h3>4.3 Szervernaplók</h3>
            <p>A tárhelyszolgáltató (Rackhost Zrt.) a webszerver működése során hozzáférési naplót vezet, amely tartalmazza a látogató IP-címét, a böngésző azonosítóját és a kérés időpontját. Ezek a naplók a szolgáltató rendszerében keletkeznek, megőrzési idejükre a Rackhost Zrt. saját adatkezelési tájékoztatója irányadó.</p>
            <p><strong>Jogalap:</strong> GDPR 6. cikk (1) f) — az üzemeltetés biztonságához és a hibakereséshez fűződő jogos érdek.</p>

            <h2 id="sutik">5. Sütik és böngészőben tárolt adatok</h2>
            <p>A weboldal kizárólag a működéshez feltétlenül szükséges sütiket használ. Nincs analitikai, hirdetési vagy profilalkotási süti, ezért a sütik használatához nem szükséges az Ön hozzájárulása.</p>

            <h3>5.1 Sütik (cookie)</h3>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Név</th><th scope="col">Cél</th><th scope="col">Élettartam</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">blckt-session</th><td>munkamenet azonosítója; a biztonsági tokent és a nyelvválasztást hordozza</td><td>120 perc</td></tr>
                        <tr><th scope="row">XSRF-TOKEN</th><td>biztonsági (CSRF) token, amelyet az oldal JavaScript kódja is olvas</td><td>munkamenet</td></tr>
                    </tbody>
                </table>
            </div>
            <p>A munkamenet-süti beállításai: elérési út <code>/</code>, HttpOnly, SameSite=Lax, HTTPS kapcsolat esetén Secure.</p>

            <h3>5.2 Böngészőben tárolt adatok (localStorage)</h3>
            <p>Ezek nem sütik: az Ön böngészőjében maradnak, a szerverre soha nem kerülnek el.</p>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Név</th><th scope="col">Cél</th><th scope="col">Élettartam</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">blckt-theme</th><td>világos vagy sötét megjelenés választása</td><td>törlésig</td></tr>
                        <tr><th scope="row">blckt-cookie-consent</th><td>megjegyzi, hogy Ön elolvasta a süti-tájékoztatót</td><td>törlésig</td></tr>
                        <tr><th scope="row">blckt-saved</th><td>az Ön által elmentett sablonok listája</td><td>törlésig</td></tr>
                    </tbody>
                </table>
            </div>

            <h3>5.3 A Playground (IndexedDB)</h3>
            <p>A weboldal Playground funkciója lehetővé teszi, hogy Ön saját szövegét és fényképeit próbálja ki egy bemutató oldalon.</p>
            <p><strong>Az itt megadott tartalom soha nem hagyja el az Ön eszközét.</strong> A szövegek és a képek a böngésző IndexedDB tárolójában (<code>blckt-playground</code> adatbázis) maradnak, feltöltés és továbbítás nélkül. A képek mérete legfeljebb 25 MB lehet, és a böngésző automatikusan átméretezi őket.</p>
            <p>Egy fontos pontosítás: miközben az Ön saját tartalma valóban nem kerül sehova, a bemutató oldalak illusztrációs fényképeket töltenek be az Unsplash képszolgáltatótól. Ez azt jelenti, hogy a bemutató oldal megnyitásakor az Ön IP-címe eljut az Unsplash szervereihez. Erről bővebben a 6. pontban.</p>
            <p>Az itt tárolt adatokat Ön bármikor törölheti a böngészője beállításaiban, vagy a Playground felületén található visszaállítás gombbal.</p>

            <h3>5.4 A sütik kezelése</h3>
            <p>A sütiket böngészője beállításaiban bármikor törölheti vagy letilthatja. Felhívjuk a figyelmét, hogy a munkamenet-süti letiltása esetén a kapcsolatfelvételi űrlap nem fog működni.</p>

            <h2>6. Adatfeldolgozók és címzettek</h2>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Kinek</th><th scope="col">Milyen szerepben</th><th scope="col">Mi jut el hozzá</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">Rackhost Zrt.<br>(6722 Szeged, Tisza Lajos krt. 41.)</th><td>tárhely- és levelezőszolgáltató, adatfeldolgozó</td><td>a szerver naplói, a munkamenet-adatbázis, valamint a beérkező és kimenő e-mailek tartalma</td></tr>
                        <tr><th scope="row">KBOSS.hu Kft. (Számlázz.hu)</th><td>számlázási szolgáltató, adatfeldolgozó</td><td>kizárólag megrendelés esetén: a számlázási adatok</td></tr>
                        <tr><th scope="row">Google LLC (Google Fonts)</th><td>betűtípus-szolgáltató</td><td>a látogató IP-címe, minden oldalbetöltéskor</td></tr>
                        <tr><th scope="row">Unsplash Inc.</th><td>képszolgáltató</td><td>a látogató IP-címe, a bemutató oldalak és a Playground megnyitásakor</td></tr>
                    </tbody>
                </table>
            </div>
            <p>A Google Fonts és az Unsplash esetében az adattovábbítás oka, hogy ezek a szolgáltatások a weboldal megjelenítéséhez szükséges elemeket (betűtípusokat, illetve illusztrációs fényképeket) közvetlenül a saját szervereikről szolgáltatják. Mindkét szolgáltató az Amerikai Egyesült Államokban működik; az adattovábbítás az Európai Bizottság megfelelőségi határozata (EU–USA Adatvédelmi Keret), illetve általános szerződési feltételek alapján történik.</p>
            <p><strong>Jogalap ezekre:</strong> GDPR 6. cikk (1) f) — a weboldal egységes megjelenítéséhez fűződő jogos érdek.</p>
            <p>A weboldal a bemutató oldalakon külső webcímekre mutató hivatkozásokat is tartalmaz (például térkép-, közösségi média- vagy időpontfoglaló szolgáltatásokra). Ezek kizárólag hivatkozások: a szolgáltatások tartalma nem töltődik be automatikusan, adattovábbítás csak akkor történik, ha Ön rákattint.</p>
            <p>Adatait a szolgáltató harmadik félnek nem adja el, marketingcélra nem továbbítja, és profilalkotást nem végez.</p>

            <h2>7. Az Ön jogai</h2>
            <p>A GDPR alapján Önt az alábbi jogok illetik meg:</p>
            <ul>
                <li><strong>Tájékoztatáshoz és hozzáféréshez való jog</strong> — kérheti, hogy tájékoztassuk arról, kezeljük-e személyes adatait, és ha igen, melyeket.</li>
                <li><strong>Helyesbítéshez való jog</strong> — kérheti pontatlan adatai kijavítását.</li>
                <li><strong>Törléshez való jog</strong> — kérheti adatai törlését, kivéve ha azok megőrzésére jogszabály kötelez (például számviteli bizonylatok esetén).</li>
                <li><strong>Az adatkezelés korlátozásához való jog.</strong></li>
                <li><strong>Adathordozhatósághoz való jog</strong> — kérheti adatait géppel olvasható formában.</li>
                <li><strong>Tiltakozáshoz való jog</strong> — jogos érdeken alapuló adatkezelés ellen bármikor tiltakozhat.</li>
            </ul>
            <p><strong>Hogyan élhet ezekkel:</strong> írjon a <a href="mailto:hello@blckt.hu">hello@blckt.hu</a> címre. A kérelmet legkésőbb 30 napon belül megválaszoljuk. Ha a kérelem összetett, ez a határidő legfeljebb további két hónappal meghosszabbítható, amiről Önt tájékoztatjuk.</p>
            <p>Az azonosítás érdekében szükség lehet arra, hogy arról az e-mail-címről írjon, amelyről a megkeresés érkezett.</p>

            <h2>8. Jogorvoslat</h2>
            <p>Ha úgy érzi, hogy adatai kezelése során jogsérelem érte, először forduljon hozzánk a <a href="mailto:hello@blckt.hu">hello@blckt.hu</a> címen — a legtöbb kérdés így rendezhető a leggyorsabban.</p>
            <p>Emellett panasszal élhet a felügyeleti hatóságnál:</p>
            <div class="legal-card">
                <p>Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH)</p>
                <p>1055 Budapest, Falk Miksa utca 9-11.</p>
                <p>Postacím: 1363 Budapest, Pf. 9.</p>
                <p>Telefon: <a href="tel:+3613911400">+36 1 391 1400</a></p>
                <p>E-mail: <a href="mailto:ugyfelszolgalat@naih.hu">ugyfelszolgalat@naih.hu</a></p>
                <p><a href="https://naih.hu" target="_blank" rel="noopener">naih.hu</a></p>
            </div>
            <p>Ezen felül Ön bírósághoz is fordulhat. A pert a lakóhelye vagy tartózkodási helye szerint illetékes törvényszék előtt is megindíthatja.</p>

            <h2>9. Adatbiztonság</h2>
            <p>A szolgáltató a rendelkezésére álló eszközökkel gondoskodik az adatok biztonságáról:</p>
            <ul>
                <li>a weboldal titkosított (HTTPS) kapcsolaton érhető el;</li>
                <li>a kapcsolatfelvételi űrlap keresztoldali kérés-hamisítás (CSRF) elleni védelemmel rendelkezik;</li>
                <li>automatizált visszaélés ellen rejtett mező és beküldési korlát véd;</li>
                <li>a levelezés titkosított kapcsolaton (SMTP over SSL) történik;</li>
                <li>a szerver hibakeresési üzemmódja éles környezetben ki van kapcsolva;</li>
                <li>az adatokhoz kizárólag a szolgáltató fér hozzá.</li>
            </ul>

            <h2>10. A tájékoztató módosítása</h2>
            <p>A szolgáltató fenntartja a jogot, hogy a jelen tájékoztatót egyoldalúan módosítsa. A módosított tájékoztató a weboldalon való közzététellel lép hatályba, a hatálybalépés napjának feltüntetésével. Lényeges változás esetén a szolgáltató a weboldalon jól látható módon hívja fel erre a figyelmet. Az egyes verziók dátumát a dokumentum tetején található „Hatályos” mező tartalmazza.</p>
        </div>
    </section>
@endsection

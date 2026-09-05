@extends('layout')

@section('title', 'Impresszum | blckt.')
@section('meta_description', 'A blckt.hu weboldal üzemeltetőjének hivatalos adatai: név, székhely, adószám, nyilvántartási szám, tárhelyszolgáltató, valamint e-mail és telefonos elérhetőség.')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="section shell" style="padding-top: calc(72px + var(--space-16))">
        <h1 class="t2 optical-left">Impresszum</h1>
        <p class="t8 ink-faint">Hatályos: 2026. [hónap] [nap]</p>

        @if (app()->getLocale() !== 'hu')
            <p class="t8 ink-faint">{{ __('This page is only available in Hungarian.') }}</p>
        @endif

        <div class="prose">
            <p>Az elektronikus kereskedelmi szolgáltatások, valamint az információs társadalommal összefüggő szolgáltatások egyes kérdéseiről szóló 2001. évi CVIII. törvény (Ekertv.) 4. §-a alapján közzétett szolgáltatói adatok.</p>

            <h2>1. A szolgáltató adatai</h2>
            <div class="legal-card">
                <p><strong>Név:</strong> Hussy Levente Hunor egyéni vállalkozó</p>
                <p><strong>Vállalkozói név / márkanév:</strong> blckt.</p>
                <p><strong>Székhely:</strong> [IRÁNYÍTÓSZÁM] Solymár, [utca, házszám]</p>
                <p><strong>Levelezési cím:</strong> megegyezik a székhellyel</p>
                <p><strong>Nyilvántartási szám:</strong> [EGYÉNI VÁLLALKOZÓI NYILVÁNTARTÁSI SZÁM]</p>
                <p><strong>Adószám:</strong> [ADÓSZÁM]</p>
                <p><strong>Közösségi adószám:</strong> [KÖZÖSSÉGI ADÓSZÁM, ha van]</p>
                <p><strong>Nyilvántartást vezető szerv:</strong> Nemzeti Adó- és Vámhivatal (egyéni vállalkozók nyilvántartása)</p>
                <p><strong>Kamarai regisztráció:</strong> Pest Vármegyei és Érdi Kereskedelmi és Iparkamara</p>
                <p><strong>E-mail:</strong> <a href="mailto:hello@blckt.hu">hello@blckt.hu</a></p>
                <p><strong>Telefon:</strong> <a href="tel:+36302552432">+36 30 255 2432</a></p>
                <p><strong>Weboldal:</strong> <a href="https://blckt.hu">https://blckt.hu</a></p>
            </div>

            <p><strong>Adózási forma:</strong> átalányadózó egyéni vállalkozó<br>
            <strong>Áfa-státusz:</strong> alanyi adómentes (Áfa tv. XIII. fejezet)</p>

            <h2>2. Tevékenységi kör</h2>
            <ul>
                <li>Weboldalak tervezése és fejlesztése (egyedi fejlesztés és sablon alapú megoldások)</li>
                <li>Weblap tervezés, webdizájn</li>
                <li>Számítógépes programozás</li>
                <li>Grafikai tervezés (ruházati grafika)</li>
            </ul>
            <p>A tevékenység nem engedélyköteles, és nem tartozik szakmai kamarai felügyelet alá.</p>

            <h2>3. Tárhelyszolgáltató</h2>
            <div class="legal-card">
                <p><strong>Név:</strong> Rackhost Zrt.</p>
                <p><strong>Székhely:</strong> 6722 Szeged, Tisza Lajos körút 41.</p>
                <p><strong>E-mail:</strong> <a href="mailto:info@rackhost.hu">info@rackhost.hu</a></p>
                <p><strong>Weboldal:</strong> <a href="https://www.rackhost.hu" target="_blank" rel="noopener">rackhost.hu</a></p>
            </div>
            <p>A weboldal a szolgáltató által bérelt virtuális szerveren (VPS) fut, a domain regisztrátora szintén a Rackhost Zrt.</p>

            <h2>4. Szerzői jogok</h2>
            <p>A blckt.hu weboldalon megjelenő valamennyi tartalom — így különösen a design, a szöveges tartalom, a grafikai elemek, a fényképek, a forráskód és az oldal szerkezete — a szolgáltató szellemi tulajdonát képezi, és a szerzői jogról szóló 1999. évi LXXVI. törvény védelme alatt áll.</p>
            <p>A tartalom bármely részének másolása, többszörözése, terjesztése, átdolgozása vagy nyilvánossághoz közvetítése kizárólag a szolgáltató előzetes írásbeli engedélyével lehetséges.</p>
            <p><strong>Kivételek.</strong> A weboldalon megjelenő ügyfélprojektek esetében az ügyfél márkaneve, logója, fényképei és szöveges tartalmai az adott ügyfél tulajdonát képezik, és azok a bemutatás céljából, az ügyfél hozzájárulásával szerepelnek. A bemutató (demó) oldalakon szereplő vállalkozások fiktívek, az azokon látható fényképek szabadon felhasználható állományból (Unsplash) származnak.</p>

            <h2>5. Panaszkezelés és jogorvoslat</h2>
            <p><strong>Közvetlen panasz:</strong> <a href="mailto:hello@blckt.hu">hello@blckt.hu</a></p>
            <p><strong>Fogyasztóvédelmi hatóság:</strong> a fogyasztóvédelmi hatósági feladatokat a fogyasztó lakóhelye szerint illetékes vármegyei kormányhivatal látja el. Elérhetőségek: <a href="https://kormanyhivatalok.hu" target="_blank" rel="noopener">kormanyhivatalok.hu</a></p>

            <p><strong>Békéltető testület</strong> (a szolgáltató székhelye szerint illetékes):</p>
            <div class="legal-card">
                <p>Pest Vármegyei Békéltető Testület</p>
                <p>1055 Budapest, Balassi Bálint u. 25. IV/2.</p>
                <p>Telefon: <a href="tel:+3617927881">+36 1 792 7881</a></p>
                <p>E-mail: <a href="mailto:pmbekelteto@pmkik.hu">pmbekelteto@pmkik.hu</a></p>
                <p><a href="https://panaszrendezes.hu" target="_blank" rel="noopener">panaszrendezes.hu</a></p>
            </div>

            <p><strong>Online vitarendezési platform (ODR):</strong> <a href="https://ec.europa.eu/odr" target="_blank" rel="noopener">ec.europa.eu/odr</a></p>

            <p><strong>Adatvédelmi hatóság:</strong></p>
            <div class="legal-card">
                <p>Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH)</p>
                <p>1055 Budapest, Falk Miksa utca 9-11.</p>
                <p>Postacím: 1363 Budapest, Pf. 9.</p>
                <p>Telefon: <a href="tel:+3613911400">+36 1 391 1400</a></p>
                <p>E-mail: <a href="mailto:ugyfelszolgalat@naih.hu">ugyfelszolgalat@naih.hu</a></p>
                <p><a href="https://naih.hu" target="_blank" rel="noopener">naih.hu</a></p>
            </div>

            <h2>6. Magatartási kódex</h2>
            <p>A szolgáltató nem vetette alá magát semmilyen magatartási kódexnek.</p>

            <p>Ez az impresszum a 2001. évi CVIII. törvény, a 2011. évi CXII. törvény és a fogyasztóvédelemről szóló 1997. évi CLV. törvény rendelkezéseinek figyelembevételével készült.</p>
        </div>
    </section>
@endsection

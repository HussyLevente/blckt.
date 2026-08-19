@extends('layout')

@section('title', 'Adatvédelmi tájékoztató | blckt.')
@section('meta_description', 'Tájékoztató a blckt.hu weboldalon történő adatkezelésről és a sütik használatáról.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/legal.css') }}">
@endpush

@section('content')
    <section class="legal-section reveal">
        <h1 class="legal-title">Adatvédelmi tájékoztató</h1>
        <p class="legal-updated">Utolsó frissítés: 2026. augusztus</p>

        @if (app()->getLocale() !== 'hu')
            <p class="legal-locale-note">{{ __('This page is only available in Hungarian.') }}</p>
        @endif

        <div class="legal-content">
            <p>Ez a tájékoztató bemutatja, hogy a blckt. (Hussy Levente, a továbbiakban: <strong>Adatkezelő</strong>) milyen személyes adatokat kezel a blckt.hu weboldal használata során, milyen célból, és milyen jogok illetik meg az érintetteket az Európai Unió Általános Adatvédelmi Rendelete (GDPR) alapján.</p>

            <h2>1. Adatkezelő</h2>
            <div class="legal-card">
                <p>Hussy Levente</p>
                <p>E-mail: <a href="mailto:hello@blckt.hu">hello@blckt.hu</a></p>
            </div>

            <h2>2. Milyen adatokat kezelünk</h2>
            <p><strong>Kapcsolatfelvételi űrlap.</strong> Ha a weboldalon található kapcsolatfelvételi űrlapot kitöltöd, a következő adatokat kezeljük:</p>
            <ul>
                <li>Teljes név</li>
                <li>E-mail cím</li>
                <li>Büdzsé (opcionális)</li>
                <li>Az üzeneted szövege</li>
            </ul>
            <p>Ezeket az adatokat kizárólag a megkeresésed megválaszolására, illetve az esetleges együttműködés előkészítésére használjuk. Az adatkezelés jogalapja az érintett önkéntes hozzájárulása (GDPR 6. cikk (1) bekezdés a) pont), amelyet az űrlap kitöltésével és elküldésével adsz meg.</p>
            <p>A weboldal <strong>nem tárolja</strong> az űrlapon megadott adatokat adatbázisban — azok kizárólag e-mail formájában jutnak el hozzánk (hello@blckt.hu), és addig őrizzük meg, amíg a megkeresésed elintézése, illetve az esetleges üzleti kapcsolat indokolja. Bármikor kérheted a törlésüket a hello@blckt.hu címen.</p>
            <p>A form elküldésekor egy visszaigazoló e-mailt is kapsz a megadott címre, hogy tudd, az üzeneted megérkezett.</p>

            <h2 id="sutik">3. Sütik (cookie-k) és helyi tárolás</h2>
            <p>A weboldal a következő, kizárólag a működéshez szükséges adatokat tárolja a böngésződben:</p>
            <ul>
                <li><strong>Munkamenet-süti (session cookie):</strong> a weboldal biztonságos működéséhez (pl. CSRF-védelem az űrlapok ellen visszaélésekkel szemben) és a nyelvi beállításod (magyar/angol) megjegyzéséhez szükséges. Legfeljebb 2 óra inaktivitás után lejár.</li>
                <li><strong>Sötét/világos mód beállítás:</strong> ezt nem sütiben, hanem a böngésződ helyi tárolójában (localStorage) őrizzük meg, hogy legközelebb is a választott megjelenítéssel lásd az oldalt. Nem kerül elküldésre semmilyen szerverre.</li>
            </ul>
            <p>A weboldal <strong>nem használ</strong> hirdetési, elemző (pl. Google Analytics) vagy közösségimédia-követő sütiket.</p>

            <h2>4. Külső szolgáltatók</h2>
            <ul>
                <li><strong>Tárhely- és e-mail szolgáltató:</strong> Rackhost Zrt. (6722 Szeged, Tisza Lajos körút 41.)</li>
                <li><strong>Betűtípusok:</strong> a weboldal a Google Fonts szolgáltatását használja, ami során a böngésződ IP-címe eljut a Google szervereihez a betűtípus-fájlok letöltésekor.</li>
            </ul>

            <h2>5. Az érintett jogai</h2>
            <p>A GDPR alapján jogod van:</p>
            <ul>
                <li>tájékoztatást kérni a rólad kezelt adatokról,</li>
                <li>kérni az adatok helyesbítését vagy törlését,</li>
                <li>tiltakozni az adatkezelés ellen,</li>
                <li>panaszt tenni a Nemzeti Adatvédelmi és Információszabadság Hatóságnál.</li>
            </ul>
            <div class="legal-card">
                <p>Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH)</p>
                <p>1055 Budapest, Falk Miksa utca 9-11.</p>
                <p><a href="mailto:ugyfelszolgalat@naih.hu">ugyfelszolgalat@naih.hu</a> · <a href="https://naih.hu" target="_blank" rel="noopener">naih.hu</a></p>
            </div>
            <p>Jogaid gyakorlásához, vagy ha bármilyen kérdésed van az adatkezeléssel kapcsolatban, írj a <a href="mailto:hello@blckt.hu">hello@blckt.hu</a> címre.</p>
        </div>
    </section>
@endsection

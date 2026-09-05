@extends('layout')

@section('title', 'Általános Szerződési Feltételek | blckt.')
@section('meta_description', 'A blckt. weboldal-fejlesztési és sablon alapú szolgáltatásaira vonatkozó általános szerződési feltételek: árak, határidők, fizetés, jogátruházás és fogyasztói jogok.')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="section shell" style="padding-top: calc(72px + var(--space-16))">
        <h1 class="t2 optical-left">Általános Szerződési Feltételek</h1>
        <p class="t8 ink-faint">Hatályos: 2026. [hónap] [nap] · Verzió: 1.0</p>

        @if (app()->getLocale() !== 'hu')
            <p class="t8 ink-faint">{{ __('This page is only available in Hungarian.') }}</p>
        @endif

        <div class="prose">
            <h2>1. A Szolgáltató adatai és az ÁSZF hatálya</h2>

            <h3>1.1 Szolgáltató</h3>
            <div class="legal-card">
                <p><strong>Név:</strong> Hussy Levente Hunor egyéni vállalkozó (a továbbiakban: Szolgáltató)</p>
                <p><strong>Márkanév:</strong> blckt.</p>
                <p><strong>Székhely:</strong> [IRÁNYÍTÓSZÁM] Solymár, [utca, házszám]</p>
                <p><strong>Nyilvántartási szám:</strong> [NYILVÁNTARTÁSI SZÁM]</p>
                <p><strong>Adószám:</strong> [ADÓSZÁM]</p>
                <p><strong>E-mail:</strong> <a href="mailto:hello@blckt.hu">hello@blckt.hu</a></p>
                <p><strong>Telefon:</strong> <a href="tel:+36302552432">+36 30 255 2432</a></p>
                <p><strong>Tárhelyszolgáltató:</strong> Rackhost Zrt., 6722 Szeged, Tisza Lajos krt. 41.</p>
            </div>
            <p><strong>Áfa-státusz:</strong> a Szolgáltató alanyi adómentes. A közzétett árak véglegesek, áfa nem kerül felszámításra.</p>

            <h3>1.2 Hatály</h3>
            <p>A jelen ÁSZF a Szolgáltató által a blckt.hu weboldalon kínált szolgáltatásokra vonatkozik. A megrendelő (a továbbiakban: Megrendelő) lehet gazdálkodó szervezet vagy magánszemély.</p>
            <p>Fogyasztó az a természetes személy, aki a szakmája, önálló foglalkozása vagy üzleti tevékenysége körén kívül eső célból veszi igénybe a szolgáltatást. A fogyasztókra vonatkozó külön rendelkezéseket a 13. fejezet tartalmazza.</p>

            <h3>1.3 Irányadó jogszabályok</h3>
            <ul>
                <li>2013. évi V. törvény a Polgári Törvénykönyvről</li>
                <li>2001. évi CVIII. törvény az elektronikus kereskedelmi szolgáltatásokról</li>
                <li>1997. évi CLV. törvény a fogyasztóvédelemről</li>
                <li>45/2014. (II. 26.) Korm. rendelet a fogyasztó és a vállalkozás közötti szerződések részletes szabályairól</li>
                <li>1999. évi LXXVI. törvény a szerzői jogról</li>
                <li>Az Európai Parlament és a Tanács (EU) 2016/679 rendelete (GDPR)</li>
            </ul>

            <h2>2. A szerződés létrejötte</h2>

            <h3>2.1 A folyamat</h3>
            <ul>
                <li><strong>Megkeresés</strong> — a Megrendelő a weboldal kapcsolatfelvételi űrlapján, e-mailben vagy telefonon jelzi igényét.</li>
                <li><strong>Egyeztetés</strong> — a Szolgáltató tisztázza a feladat terjedelmét: hány oldal, milyen funkciók, milyen határidő.</li>
                <li><strong>Írásos ajánlat</strong> — a Szolgáltató fix összegű, írásos ajánlatot küld, amely tartalmazza a szolgáltatás pontos tartalmát, a díjat, a határidőt és a fizetési ütemezést.</li>
                <li><strong>Elfogadás</strong> — a szerződés akkor jön létre, amikor a Megrendelő az ajánlatot írásban (e-mailben is) elfogadja.</li>
            </ul>

            <h3>2.2 Fontos</h3>
            <p>A kapcsolatfelvételi űrlap kitöltése önmagában nem hoz létre szerződést, és a Szolgáltatóra nézve nem keletkeztet kötelezettséget. Az űrlapra érkező automatikus visszaigazolás kizárólag azt jelzi, hogy az üzenet megérkezett — nem minősül az ajánlat elfogadásának.</p>
            <p>A Szolgáltató fenntartja a jogot, hogy megrendelést indokolás nélkül elutasítson.</p>

            <h3>2.3 Kapacitás</h3>
            <p>A Szolgáltató kapacitása korlátozott. A weboldalon feltüntetett szabad kapacitásra vonatkozó tájékoztatás (például „negyedévente két szabad hely”) tájékoztató jellegű, és nem jelent lekötött kapacitást a szerződés létrejöttéig.</p>

            <h2>3. Szolgáltatások és díjak</h2>

            <h3>3.1 Egyedi fejlesztés</h3>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Csomag</th><th scope="col">Díj</th><th scope="col">Oldalszám</th><th scope="col">Vállalt határidő</th><th scope="col">Háttérrendszer</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">Alap</th><td>80 000 Ft</td><td>1</td><td>1–2 nap</td><td>nincs</td></tr>
                        <tr><th scope="row">Standard</th><td>150 000 Ft</td><td>4</td><td>5 nap</td><td>igen, űrlapokkal</td></tr>
                        <tr><th scope="row">Prémium</th><td>350 000 Ft</td><td>6</td><td>14 nap</td><td>webshop + adminfelület</td></tr>
                        <tr><th scope="row">Felújítás</th><td>70 000 Ft-tól</td><td>—</td><td>a meglévő oldaltól függ</td><td>felület újraépítése</td></tr>
                        <tr><th scope="row">Egyedi</th><td>egyedi ajánlat szerint</td><td>—</td><td>az ajánlatban meghatározva</td><td>—</td></tr>
                    </tbody>
                </table>
            </div>

            <h3>3.2 Sablon alapú megoldások</h3>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Csomag</th><th scope="col">Díj</th><th scope="col">Vállalt határidő</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">Alap</th><td>50 000 Ft</td><td>1 nap</td></tr>
                        <tr><th scope="row">Standard</th><td>100 000 Ft</td><td>2 nap</td></tr>
                        <tr><th scope="row">Prémium</th><td>200 000 Ft</td><td>5 nap</td></tr>
                    </tbody>
                </table>
            </div>

            <h3>3.3 Kiegészítő szolgáltatások</h3>
            <div class="legal-scroll">
                <table class="legal-table">
                    <thead>
                        <tr><th scope="col">Kiegészítő</th><th scope="col">Díj</th><th scope="col">Alapból tartalmazza</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">E-mail űrlap</th><td>+ 20 000 Ft</td><td>Standard, Prémium</td></tr>
                        <tr><th scope="row">Sötét mód</th><td>+ 25 000 Ft</td><td>—</td></tr>
                        <tr><th scope="row">Plusz oldal (oldalanként)</th><td>+ 25 000 Ft</td><td>—</td></tr>
                        <tr><th scope="row">Plusz nyelv</th><td>+ 40 000 Ft</td><td>—</td></tr>
                        <tr><th scope="row">Belépés és regisztráció</th><td>+ 60 000 Ft</td><td>Prémium</td></tr>
                        <tr><th scope="row">Plusz javítási kör</th><td>+ 15 000 Ft</td><td>—</td></tr>
                    </tbody>
                </table>
            </div>

            <h3>3.4 Az árakról</h3>
            <ul>
                <li>A feltüntetett árak véglegesek; a Szolgáltató alanyi adómentessége miatt áfa nem kerül felszámításra.</li>
                <li>A Szolgáltató nem küld utólagos vagy meglepetésszerű számlát. Az elfogadott ajánlatban szereplő összeg a szerződés teljes díja.</li>
                <li>A díj nem tartalmazza a domain, a tárhely, valamint a fizetős betűtípusok, képek vagy külső szolgáltatások díját. Ezekről a Szolgáltató előzetesen tájékoztatja a Megrendelőt.</li>
                <li>A weboldalon közzétett árak tájékoztató jellegűek mindaddig, amíg a Szolgáltató konkrét írásos ajánlatot nem ad. Az ajánlatban szereplő ár ezt követően kötött.</li>
            </ul>

            <h2>4. Fizetés</h2>

            <h3>4.1 Ütemezés</h3>
            <ul>
                <li>50% a szerződés létrejöttekor, előlegként;</li>
                <li>50% az átadáskor, a Megrendelő jóváhagyását követően.</li>
            </ul>
            <p>A Szolgáltató mindkét részletről elektronikus számlát állít ki a Számlázz.hu rendszerén keresztül. A számla a megadott e-mail-címre érkezik.</p>

            <h3>4.2 Fizetési határidő és késedelem</h3>
            <p>A számlák fizetési határideje a kiállítástól számított 8 nap, eltérő megállapodás hiányában.</p>
            <p>Késedelmes fizetés esetén a Szolgáltató a Ptk. szerinti késedelmi kamatra jogosult. Gazdálkodó szervezet Megrendelő esetén a Szolgáltató a behajtási költségátalányra is jogosult (2016. évi IX. törvény).</p>
            <p>Ha a végszámla a határidőt követő 15 napon belül sem kerül kiegyenlítésre, a Szolgáltató jogosult a szolgáltatás átadását visszatartani. A 8. fejezet szerinti jogátruházás ilyenkor nem következik be.</p>

            <h2>5. Határidők</h2>

            <h3>5.1 Mikor indul a határidő</h3>
            <p>A vállalt határidő attól a naptól számítódik, amelyen mindkét feltétel teljesült:</p>
            <ul>
                <li>az előleg a Szolgáltató számláján jóváíródott, és</li>
                <li>a Megrendelő az összes szükséges tartalmat (szövegek, képek, logó, elérhetőségek) hiánytalanul átadta.</li>
            </ul>
            <p>A határidő tehát nem a megkeresés vagy az ajánlat elfogadásának napjától indul.</p>

            <h3>5.2 Ha a tartalom késik</h3>
            <p>Ha a Megrendelő a tartalmat késedelmesen adja át, a vállalt határidő a késedelem időtartamával automatikusan meghosszabbodik. Ha a Megrendelő a szerződés létrejöttétől számított 60 napon belül nem szolgáltatja a szükséges tartalmat, a Szolgáltató jogosult a szerződéstől elállni; ebben az esetben az előleg az addig elvégzett munka ellenértékeként a Szolgáltatót illeti meg.</p>

            <h3>5.3 Akadályoztatás</h3>
            <p>A Szolgáltató haladéktalanul tájékoztatja a Megrendelőt, ha a határidő tartása bármely okból veszélybe kerül, és új határidőt javasol.</p>

            <h2>6. A Megrendelő kötelezettségei</h2>
            <p>A Megrendelő vállalja, hogy:</p>
            <ul>
                <li>a domain nevet és — ha a megoldás igényli — a szervert a saját nevére vásárolja meg. A Szolgáltató előzetesen tanácsot ad arról, mit érdemes megvásárolni és nagyjából mennyiért, még mielőtt a Megrendelő bármit is kifizetne;</li>
                <li>a szükséges tartalmakat (szöveg, kép, logó, elérhetőségek) a Szolgáltató által megadott lista szerint, a megállapodott határidőre átadja;</li>
                <li>az átadott tartalmak tekintetében rendelkezik a felhasználáshoz szükséges jogokkal, és szavatolja, hogy azok harmadik személy jogát nem sértik;</li>
                <li>a részteljesítéseket ésszerű időn belül véleményezi és jóváhagyja.</li>
            </ul>
            <p><strong>Harmadik személy jogsértése esetén</strong> a Megrendelő által átadott tartalmakért a Megrendelő felel. Ha ebből eredően a Szolgáltatóval szemben igényt érvényesítenek, a Megrendelő köteles a Szolgáltatót mentesíteni.</p>

            <h2>7. Javítási körök</h2>
            <ul>
                <li>Minden csomag három javítási kört tartalmaz.</li>
                <li>A negyedik és minden további javítási kör díja 15 000 Ft.</li>
                <li>Javítási körnek minősül a Megrendelő összegyűjtött észrevételeinek egy csomagban történő átvezetése.</li>
            </ul>
            <p><strong>Mikor lesz a javításból új munka:</strong> ha a Megrendelő a harmadik javítási kört követően olyan változtatást kér, amely a koncepció, az elrendezés vagy a szerkezet lényegi újratervezését igényli, az új munkának minősül, és külön ajánlat alapján, külön díjazás ellenében készül el. A Szolgáltató erről a Megrendelőt előzetesen tájékoztatja.</p>

            <h2>8. Átadás és a jogok átszállása</h2>

            <h3>8.1 Az átadás menete</h3>
            <ul>
                <li>a Megrendelő írásban jóváhagyja az elkészült munkát;</li>
                <li>a Szolgáltató kiállítja a végszámlát;</li>
                <li>a Megrendelő a végszámlát kiegyenlíti;</li>
                <li>a Szolgáltató átadja a teljes forráskódot tömörített állományban, a hozzáférési adatokat, valamint egy rövid átadási bemutatót arról, hogyan kell az oldalt kezelni.</li>
            </ul>

            <h3>8.2 Jogátruházás</h3>
            <p>A végszámla kiegyenlítésével a Megrendelő <strong>teljes körű és korlátlan felhasználási jogot</strong> szerez az elkészült weboldal felett: területi és időbeli korlátozás nélkül, átdolgozás és továbbadás jogával együtt.</p>
            <p>A Megrendelő a Szolgáltatótól semmilyen előfizetéssel nem függ. Az elkészült weboldal működése nem kötődik a Szolgáltató szolgáltatásaihoz, és a kapcsolat megszűnése esetén sem szűnik meg semmilyen funkció.</p>
            <p>A végszámla kiegyenlítéséig a Szolgáltató a mű valamennyi szerzői jogát fenntartja.</p>

            <h3>8.3 Referenciajog</h3>
            <p>A Szolgáltató jogosult az elkészült munkát referenciaként bemutatni saját weboldalán és közösségi médiafelületein, ideértve a Megrendelő nevét, logóját, a weboldalról készült képernyőképeket, valamint egy rövid bemutató videót.</p>
            <p>A Megrendelő ezt bármikor, indokolás nélkül megtilthatja a <a href="mailto:hello@blckt.hu">hello@blckt.hu</a> címre küldött üzenettel. A Szolgáltató ilyenkor a referenciát a lehető leghamarabb, de legkésőbb 5 munkanapon belül eltávolítja.</p>
            <p>A Szolgáltató a Megrendelő üzleti adatait (látogatottság, bevétel, konverziós számok) referenciaként nem teszi közzé.</p>

            <h2>9. Sablonlicenc</h2>
            <p>A sablon alapú megoldásokra az alábbi külön feltételek vonatkoznak.</p>

            <h3>9.1 A licencek száma</h3>
            <p>Minden sablonterv <strong>legfeljebb háromszor</strong> kerül értékesítésre. Egy adott terv továbbá nem kerül kétszer értékesítésre ugyanabban az iparágban, ugyanazon a településen.</p>
            <p>A Szolgáltató az egyes tervekhez tartozó szabad licencek számát a weboldalon feltünteti. A licenc a szerződés létrejöttekor kerül lefoglalásra.</p>

            <h3>9.2 Nincs kizárólagosság</h3>
            <p>A sablonlicenc <strong>nem biztosít kizárólagosságot.</strong> A Megrendelő tudomásul veszi, hogy az adott terv legfeljebb további két másik megrendelőnél is megjelenhet, a 9.1 pontban írt korlátozásokkal.</p>
            <p>Ha a Megrendelő kizárólagosságot kíván szerezni, erről a felek külön állapodnak meg, külön díjazás ellenében.</p>

            <h3>9.3 Mi módosítható és mi nem</h3>
            <p><strong>Módosítható:</strong> szövegek; fényképek és illusztrációk; logó; színek; betűtípus; elérhetőségi adatok; nem kívánt oldalszakaszok eltávolítása.</p>
            <p><strong>Nem módosítható a sablon díjában:</strong> az elrendezés; az oldalszerkezet; a navigáció felépítése; az animációk.</p>
            <p>Az e körön kívüli módosítás egyedi fejlesztésnek minősül, és külön ajánlat alapján készül el.</p>

            <h3>9.4 Jogi szövegek a sablonokban</h3>
            <p>A sablonok tartalmaznak impresszum-, adatvédelmi és süti-tájékoztató mintaszövegeket. Ezek kiindulási alapként szolgálnak.</p>
            <p>A Szolgáltató <strong>nem szavatolja</strong>, hogy ezek a mintaszövegek a Megrendelő konkrét tevékenységére és adatkezelésére nézve megfelelnek a jogszabályi követelményeknek. A mintaszövegek kitöltése, testreszabása és jogi ellenőrzése a Megrendelő felelőssége. A Szolgáltató nem nyújt jogi tanácsadást.</p>

            <h2>10. Válaszidő és kapcsolattartás</h2>
            <p>A Szolgáltató törekszik arra, hogy a megkeresésekre 24 órán belül válaszoljon. Ez a vállalás célkitűzés, nem szerződéses kötelezettség, és a válaszadás elmaradása nem alapoz meg igényt.</p>
            <p>A kapcsolattartás elsődleges csatornája az e-mail (<a href="mailto:hello@blckt.hu">hello@blckt.hu</a>).</p>

            <h2>11. Szavatosság és felelősség</h2>

            <h3>11.1 Hibás teljesítés</h3>
            <p>A Szolgáltató a Ptk. szabályai szerint szavatol azért, hogy az elkészült weboldal az átadáskor megfelel az elfogadott ajánlatban rögzített tartalomnak, és rendeltetésszerű használatra alkalmas.</p>
            <p>A Szolgáltató az átadástól számított <strong>30 napig díjmentesen javítja</strong> az elkészült munkában felmerülő hibákat. Ez a vállalás nem érinti a Megrendelő jogszabályon alapuló szavatossági jogait.</p>

            <h3>11.2 Amiért a Szolgáltató nem felel</h3>
            <p>A Szolgáltató nem felel:</p>
            <ul>
                <li>a Megrendelő által beszerzett domain, tárhely vagy szerver működéséért, elérhetőségéért;</li>
                <li>a Megrendelő által átadott vagy utóbb feltöltött tartalmakért;</li>
                <li>az átadást követően a Megrendelő vagy harmadik személy által végzett módosítások következményeiért;</li>
                <li>külső szolgáltatások (például térkép, közösségi média, fizetési szolgáltató) működéséért vagy feltételeik változásáért;</li>
                <li>keresőoptimalizálási eredményekért, találati helyezésekért, látogatószámért vagy üzleti eredményért — a Szolgáltató technikailag jól felépített oldalt készít, de a keresőmotorok működését nem befolyásolja.</li>
            </ul>

            <h3>11.3 Felelősségkorlátozás</h3>
            <p>A Szolgáltató kártérítési felelőssége — a szándékosan okozott, valamint az emberi életet, testi épséget vagy egészséget megkárosító szerződésszegés esetét kivéve — az adott szerződés alapján ténylegesen kifizetett díj összegére korlátozódik.</p>
            <p>Ez a korlátozás nem alkalmazandó fogyasztóval kötött szerződés esetén, ha azt jogszabály kizárja.</p>

            <h2>12. A szerződés megszűnése</h2>

            <h3>12.1 Elállás a Megrendelő részéről</h3>
            <p>A Megrendelő a szerződéstől bármikor elállhat. Ilyen esetben:</p>
            <ul>
                <li>ha a Szolgáltató még nem kezdte meg a munkát, az előleg teljes egészében visszajár;</li>
                <li>ha a munka már megkezdődött, a Szolgáltatót az addig elvégzett munka arányos ellenértéke illeti meg, a fennmaradó összeg visszajár.</li>
            </ul>
            <p>A már elkészült, de ki nem fizetett munkarészek felhasználási joga a Megrendelőre nem száll át.</p>

            <h3>12.2 Elállás a Szolgáltató részéről</h3>
            <p>A Szolgáltató elállhat a szerződéstől, ha:</p>
            <ul>
                <li>a Megrendelő az 5.2 pont szerinti határidőn belül nem szolgáltat tartalmat;</li>
                <li>a Megrendelő a fizetési kötelezettségével 30 napot meghaladó késedelembe esik;</li>
                <li>a Megrendelő jogszabályba ütköző tartalom közzétételét kéri.</li>
            </ul>

            <h3>12.3 Együttműködés hiánya</h3>
            <p>Ha a felek együttműködése bármely okból ellehetetlenül, a felek elszámolnak egymással a fenti elvek szerint.</p>

            <h2>13. Fogyasztókra vonatkozó külön rendelkezések</h2>
            <p>Ez a fejezet kizárólag azokra a Megrendelőkre vonatkozik, akik fogyasztónak minősülnek (természetes személy, aki szakmáján és üzleti tevékenységén kívüli célból rendel).</p>

            <h3>13.1 Elállási jog</h3>
            <p>A 45/2014. (II. 26.) Korm. rendelet alapján a fogyasztó a szerződés megkötésétől számított <strong>14 napon belül indokolás nélkül elállhat</strong> a szerződéstől.</p>
            <p>Az elállási jog gyakorolható a <a href="mailto:hello@blckt.hu">hello@blckt.hu</a> címre küldött egyértelmű nyilatkozattal, vagy a 45/2014. Korm. rendelet 2. mellékletében található nyilatkozatminta felhasználásával.</p>
            <p>A nyilatkozatmintát a Szolgáltató a szerződéskötéskor minden fogyasztó megrendelőnek átadja. A minta a jelen ÁSZF mellékletét képezi.</p>

            <h3>13.2 Az elállási jog elvesztése — fontos</h3>
            <p>Ha a fogyasztó kifejezetten kéri, hogy a Szolgáltató a 14 napos elállási határidő lejárta előtt kezdje meg a teljesítést, akkor:</p>
            <ul>
                <li>a szolgáltatás teljes egészében történő teljesítése után a fogyasztó elveszíti elállási jogát;</li>
                <li>ha a fogyasztó a teljesítés megkezdése után, de a teljes teljesítés előtt áll el, köteles megtéríteni a Szolgáltatónak az addig teljesített szolgáltatás arányos ellenértékét.</li>
            </ul>
            <p>A Szolgáltató a munkát csak azt követően kezdi meg, hogy a fogyasztó ezt a nyilatkozatot írásban megtette, és tudomásul vette az elállási jog elvesztésére vonatkozó tájékoztatást. E nyilatkozat nélkül a munka a 14 nap letelte után indul.</p>
            <p>A nyilatkozat mintáját a Szolgáltató a szerződéskötéskor bocsátja a fogyasztó rendelkezésére; a nyilatkozat elektronikus úton, e-mailben is megtehető.</p>

            <h3>13.3 Kellékszavatosság</h3>
            <p>A fogyasztó a Szolgáltató hibás teljesítése esetén kellékszavatossági igényt érvényesíthet a Ptk. szabályai szerint: kérhet kijavítást, vagy — ha ez nem lehetséges — árleszállítást, illetve elállhat a szerződéstől.</p>
            <p>A fogyasztó a teljesítéstől számított két éven belül érvényesítheti kellékszavatossági igényét. A teljesítéstől számított egy éven belül a hiba felismerésén túl mást nem kell bizonyítania; egy év elteltével a fogyasztónak kell bizonyítania, hogy a hiba már a teljesítés időpontjában megvolt.</p>

            <h3>13.4 Panaszkezelés</h3>
            <p>A fogyasztó panaszát a <a href="mailto:hello@blckt.hu">hello@blckt.hu</a> címen vagy a <a href="tel:+36302552432">+36 30 255 2432</a> telefonszámon terjesztheti elő. A Szolgáltató az írásbeli panaszt annak beérkezésétől számított 30 napon belül érdemben megválaszolja, és álláspontját indokolja.</p>

            <h3>13.5 Békéltető testület</h3>
            <p>A fogyasztó a fogyasztói jogvita bírósági eljáráson kívüli rendezése érdekében békéltető testülethez fordulhat.</p>
            <div class="legal-card">
                <p>Pest Vármegyei Békéltető Testület</p>
                <p>1055 Budapest, Balassi Bálint u. 25. IV/2.</p>
                <p>Telefon: <a href="tel:+3617927881">+36 1 792 7881</a></p>
                <p>E-mail: <a href="mailto:pmbekelteto@pmkik.hu">pmbekelteto@pmkik.hu</a></p>
                <p><a href="https://panaszrendezes.hu" target="_blank" rel="noopener">panaszrendezes.hu</a></p>
            </div>
            <p>A fogyasztó a lakóhelye szerint illetékes békéltető testülethez is fordulhat. A testületek listája: <a href="https://bekeltetes.hu" target="_blank" rel="noopener">bekeltetes.hu</a></p>
            <p>A Szolgáltatót a békéltető testületi eljárásban együttműködési kötelezettség terheli. A Szolgáltató alávetési nyilatkozatot nem tett.</p>

            <h3>13.6 Online vitarendezés</h3>
            <p>A fogyasztó az Európai Bizottság online vitarendezési platformját is igénybe veheti: <a href="https://ec.europa.eu/odr" target="_blank" rel="noopener">ec.europa.eu/odr</a></p>

            <h3>13.7 Fogyasztóvédelmi hatóság</h3>
            <p>A fogyasztó panasszal fordulhat a lakóhelye szerint illetékes vármegyei kormányhivatal fogyasztóvédelmi osztályához. Elérhetőségek: <a href="https://kormanyhivatalok.hu" target="_blank" rel="noopener">kormanyhivatalok.hu</a></p>

            <h2>14. Titoktartás</h2>
            <p>A felek kötelesek bizalmasan kezelni minden olyan információt, amelyet a szerződés teljesítése során a másik félről szereznek. Ez különösen vonatkozik az üzleti adatokra, a hozzáférési adatokra és a még nyilvánosságra nem hozott tervekre.</p>
            <p>A titoktartási kötelezettség a szerződés megszűnését követően is fennmarad.</p>
            <p>Nem minősül a titoktartás megsértésének a 8.3 pont szerinti referenciajog gyakorlása.</p>

            <h2>15. Adatkezelés</h2>
            <p>A Szolgáltató a személyes adatokat a weboldalon közzétett <a href="{{ route('legal.adatvedelem') }}">Adatvédelmi tájékoztató</a> szerint kezeli.</p>

            <h2>16. Záró rendelkezések</h2>

            <h3>16.1 Irányadó jog és joghatóság</h3>
            <p>A szerződésre a magyar jog az irányadó. A felek a jogvitákat elsősorban egyeztetéssel rendezik. Ennek eredménytelensége esetén — fogyasztói szerződés kivételével, ahol a Pp. általános szabályai érvényesülnek — a Szolgáltató székhelye szerint illetékes bíróság jár el.</p>

            <h3>16.2 Az ÁSZF módosítása</h3>
            <p>A Szolgáltató jogosult a jelen ÁSZF-et egyoldalúan módosítani. A módosítás a weboldalon való közzététellel lép hatályba, és a már létrejött szerződésekre nem hat ki — azokra a szerződéskötéskor hatályos ÁSZF az irányadó.</p>

            <h3>16.3 Nyelv</h3>
            <p>Az ÁSZF magyar nyelven készült. Amennyiben idegen nyelvű fordítás készül, eltérés esetén a magyar nyelvű változat az irányadó.</p>

            <h3>16.4 Részleges érvénytelenség</h3>
            <p>Ha az ÁSZF valamely rendelkezése érvénytelennek bizonyul, az a többi rendelkezés érvényességét nem érinti.</p>

            <p>Jelen ÁSZF a fenti hatálybalépési naptól visszavonásig, illetve módosításig érvényes.</p>
        </div>
    </section>
@endsection

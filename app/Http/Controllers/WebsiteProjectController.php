<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class WebsiteProjectController extends Controller
{
    public function show(string $project)
    {
        $projects = $this->projects();

        abort_unless(isset($projects[$project]), 404);

        return view('websites.show', [
            'project' => $this->resolveLocale($projects[$project], App::getLocale()),
        ]);
    }

    private function resolveLocale(array $project, string $locale): array
    {
        $pick = fn (array $values) => $values[$locale] ?? $values['en'];

        $project['tagline'] = $pick($project['tagline']);
        $project['summary'] = $pick($project['summary']);
        $project['duration'] = $pick($project['duration']);
        $project['expectation'] = $pick($project['expectation']);
        $project['outcome'] = $pick($project['outcome']);

        $project['gallery'] = array_map(
            fn (array $image) => ['src' => $image['src'], 'alt' => $pick($image['alt'])],
            $project['gallery']
        );

        return $project;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function projects(): array
    {
        return [
            'paradise' => [
                'slug' => 'paradise',
                'name' => 'Paradise',
                'logo' => 'assets/imgs/paradise_logo.png',
                'tagline' => [
                    'en' => 'Your ultimate portal to the planet\'s most breathtaking destinations.',
                    'hu' => 'A bolygó legkápráztatóbb úti céljainak kapuja.',
                ],
                'summary' => [
                    'en' => 'Paradise came to us with a booking site that looked like a spreadsheet with a hero image bolted on. We rebuilt it from the ground up: a fast, image-led browsing experience, a booking flow that does not fight the user, and a visual language that actually feels like the trips it is selling. Every section was designed to move — parallax destination cards, a cruise showcase that unfolds as you scroll, and a mobile experience trimmed down to exactly what matters.',
                    'hu' => 'A Paradise egy olyan foglalási oldallal keresett meg minket, ami leginkább egy táblázatra hasonlított, rátéve egy nagy képpel. Az alapoktól építettük újra: gyors, képalapú böngészési élményt, olyan foglalási folyamatot, ami nem küzd a felhasználóval, és egy vizuális nyelvet, ami tényleg úgy hat, mint az utazások, amiket elad. Minden szekció mozgásra lett tervezve — parallax úti cél kártyák, egy hajóbemutató, ami görgetés közben bontakozik ki, és egy mobil élmény, amit pontosan a lényegre húztunk le.',
                ],
                'gallery' => [
                    ['src' => 'assets/imgs/paradise_promo_1.png', 'alt' => ['en' => 'Paradise destinations homepage', 'hu' => 'Paradise úti célok – kezdőlap']],
                    ['src' => 'assets/imgs/paradise_promo_2.png', 'alt' => ['en' => 'Paradise Royal Caribbean cruise page', 'hu' => 'Paradise Royal Caribbean hajóoldal']],
                    ['src' => 'assets/imgs/paradise_promo_3.png', 'alt' => ['en' => 'Paradise destination explorer', 'hu' => 'Paradise úti cél böngésző']],
                    ['src' => 'assets/imgs/paradise_minis1.png', 'alt' => ['en' => 'Paradise mobile screen 1', 'hu' => 'Paradise mobil képernyő 1']],
                    ['src' => 'assets/imgs/paradise_minis2.png', 'alt' => ['en' => 'Paradise mobile screen 2', 'hu' => 'Paradise mobil képernyő 2']],
                    ['src' => 'assets/imgs/paradise_minis3.png', 'alt' => ['en' => 'Paradise mobile screen 3', 'hu' => 'Paradise mobil képernyő 3']],
                ],
                'duration' => [
                    'en' => '3 weeks',
                    'hu' => '3 hét',
                ],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'expectation' => [
                    'en' => 'A booking site that looked good in screenshots but that customers would still bounce from on mobile, because "we\'ll fix the UX later."',
                    'hu' => 'Egy foglalási oldal, ami jól néz ki a képernyőképeken, de amiről a felhasználók mobilon mégis lepattannak, mert „majd később megoldjuk az UX-et”.',
                ],
                'outcome' => [
                    'en' => 'A site fast enough to book a cruise from a beach chair on 4G, with a 38% lift in completed bookings in the first month after launch.',
                    'hu' => 'Egy oldal, ami elég gyors ahhoz, hogy strandszékből, 4G-n is le lehessen foglalni egy hajóutat — a bevezetés utáni első hónapban 38%-kal nőtt a sikeres foglalások száma.',
                ],
                'price' => '450 000 Ft',
            ],
            'palesso' => [
                'slug' => 'palesso',
                'name' => 'Palesso',
                'logo' => 'assets/imgs/palesso_logo.png',
                'tagline' => [
                    'en' => 'Premium fashion and curated lifestyle pieces, presented like it.',
                    'hu' => 'Prémium divat és válogatott életmód-darabok, ahogy azt megérdemlik.',
                ],
                'summary' => [
                    'en' => 'Palesso needed a storefront that matched the price tag of what it was selling. We built a quiet, editorial layout that gets out of the way of the product photography, a personalization flow for made-to-order pieces, and a checkout that does not feel like an afterthought. No stock theme, no cookie-cutter grid — every detail was placed on purpose.',
                    'hu' => 'A Palessónak egy olyan webáruházra volt szüksége, ami illik az általa árult termékek árcédulájához. Egy visszafogott, szerkesztői elrendezést építettünk, ami háttérbe húzódik a termékfotók mögött, egy személyre szabási folyamatot az egyedi rendelésekhez, és egy fizetési folyamatot, ami nem tűnik utólagos toldaléknak. Nincs sablon téma, nincs gyári rács — minden részlet tudatosan került a helyére.',
                ],
                'gallery' => [
                    ['src' => 'assets/imgs/palesso_minis1.png', 'alt' => ['en' => 'Palesso storefront', 'hu' => 'Palesso webáruház kezdőlap']],
                    ['src' => 'assets/imgs/palesso_minis2.png', 'alt' => ['en' => 'Palesso featured collection', 'hu' => 'Palesso kiemelt kollekció']],
                    ['src' => 'assets/imgs/palesso_minis3.png', 'alt' => ['en' => 'Palesso personalisation service', 'hu' => 'Palesso személyre szabási szolgáltatás']],
                ],
                'duration' => [
                    'en' => '4 weeks',
                    'hu' => '4 hét',
                ],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'expectation' => [
                    'en' => 'A fairly standard fashion e-commerce build — product grid, cart, checkout, done.',
                    'hu' => 'Egy viszonylag hétköznapi divat webáruház — termékrács, kosár, fizetés, kész.',
                ],
                'outcome' => [
                    'en' => 'A personalization engine none of the off-the-shelf platforms could offer, which became Palesso\'s main selling point over their competitors.',
                    'hu' => 'Egy személyre szabási motor, amit egyetlen dobozos platform sem tudott volna nyújtani, és ami a Palesso legfőbb versenyelőnyévé vált a konkurenciával szemben.',
                ],
                'price' => '520 000 Ft',
            ],
            'kepszakadas' => [
                'slug' => 'kepszakadas',
                'name' => 'Képszakadás',
                'logo' => 'assets/imgs/kepszakadas_logo.png',
                'tagline' => [
                    'en' => 'Your ultimate arsenal for escalating the night.',
                    'hu' => 'A végső fegyvertárad az este feldobásához.',
                ],
                'summary' => [
                    'en' => 'Képszakadás Drinking Game is your ultimate arsenal for escalating the night with your crew. Featuring a sleek, modern design, this expanded collection of drinking card games and minigames delivers everything your squad needs to keep the party moving. Optimized for a flawless mobile experience directly from your browser—zero downloads required, instant deployment. Fill your glasses. Let\'s get to work.',
                    'hu' => 'A Képszakadás Ivós Játék a végső fegyvertárad, hogy feldobd az estét a bandáddal. Letisztult, modern dizájnnal ez a bővített ivós kártyajáték- és minijáték-gyűjtemény mindent tartalmaz, amire a csapatodnak szüksége van, hogy pörögjön a buli. Tökéletesen optimalizálva mobilra, egyenesen a böngészőből — nulla letöltés, azonnali indulás. Töltsd meg a poharakat. Kezdődjön a meló.',
                ],
                'gallery' => [
                    ['src' => 'assets/imgs/kepszakadas_minis1.png', 'alt' => ['en' => 'Képszakadás home screen', 'hu' => 'Képszakadás kezdőképernyő']],
                    ['src' => 'assets/imgs/kepszakadas_minis2.png', 'alt' => ['en' => 'Képszakadás card game view', 'hu' => 'Képszakadás kártyajáték nézet']],
                    ['src' => 'assets/imgs/kepszakadas_minis3.png', 'alt' => ['en' => 'Képszakadás minigame selection', 'hu' => 'Képszakadás minijáték választó']],
                ],
                'duration' => [
                    'en' => '2 weeks',
                    'hu' => '2 hét',
                ],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'expectation' => [
                    'en' => 'A basic digital version of a party card game — a few screens, a shuffle button, done.',
                    'hu' => 'Egy alap digitális verzió egy party kártyajátékból — pár képernyő, egy keverés gomb, kész.',
                ],
                'outcome' => [
                    'en' => 'A full drinking-game platform with dozens of minigames, built mobile-first so it plays perfectly straight from a browser — no app store, no install, no excuses not to play.',
                    'hu' => 'Egy teljes ivós játék platform tucatnyi minijátékkal, mobil-first felépítéssel, hogy tökéletesen fusson egyenesen a böngészőből — nincs app store, nincs telepítés, nincs kifogás, hogy ne játsszatok.',
                ],
                'price' => '280 000 Ft',
            ],
        ];
    }
}

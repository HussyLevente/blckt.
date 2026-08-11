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

    /**
     * @return string[]
     */
    public function slugs(): array
    {
        return array_keys($this->projects());
    }

    private function resolveLocale(array $project, string $locale): array
    {
        $pick = fn (array $values) => $values[$locale] ?? $values['en'];

        $project['tagline'] = $pick($project['tagline']);
        $project['summary'] = $pick($project['summary']);
        $project['duration'] = $pick($project['duration']);
        $project['expectation'] = $pick($project['expectation']);
        $project['outcome'] = $pick($project['outcome']);

        $images = $this->gallery($project['slug']);

        $project['gallery'] = array_map(
            fn (string $src, int $index) => [
                'src' => $src,
                'alt' => $project['name'] . ' — ' . $index,
            ],
            $images,
            range(1, count($images))
        );

        return $project;
    }

    /**
     * Every website's gallery images follow the {slug}_whole{n}.{ext} convention.
     * Discover however many exist on disk rather than hard-coding a list.
     * Checks each extension per index so PNG and JPG can be mixed freely.
     *
     * @return string[]
     */
    private function gallery(string $slug, int $max = 20): array
    {
        $images = [];

        for ($n = 1; $n <= $max; $n++) {
            $found = null;

            foreach (['jpg', 'jpeg', 'png'] as $ext) {
                $relative = "assets/imgs/websites/{$slug}/{$slug}_whole{$n}.{$ext}";

                if (file_exists(public_path($relative))) {
                    $found = $relative;
                    break;
                }
            }

            if ($found === null) {
                break;
            }

            $images[] = $found;
        }

        return $images;
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
                'logo' => 'assets/imgs/websites/paradise/paradise_logo.png',
                'tagline' => [
                    'en' => 'Your ultimate portal to the planet\'s most breathtaking destinations.',
                    'hu' => 'A bolygó legkápráztatóbb úti céljainak kapuja.',
                ],
                'summary' => [
                    'en' => 'Paradise came to me with a booking site that looked like a spreadsheet with a hero image bolted on. I rebuilt it from the ground up: a fast, image-led browsing experience, a booking flow that does not fight the user, and a visual language that actually feels like the trips it is selling. Every section was designed to move — parallax destination cards, a cruise showcase that unfolds as you scroll, and a mobile experience trimmed down to exactly what matters.',
                    'hu' => 'A Paradise egy olyan foglalási oldallal keresett meg engem, ami leginkább egy táblázatra hasonlított, rátéve egy nagy képpel. Az alapoktól építettem újra: gyors, képalapú böngészési élményt, olyan foglalási folyamatot, ami nem küzd a felhasználóval, és egy vizuális nyelvet, ami tényleg úgy hat, mint az utazások, amiket elad. Minden szekció mozgásra lett tervezve — parallax úti cél kártyák, egy hajóbemutató, ami görgetés közben bontakozik ki, és egy mobil élmény, amit pontosan a lényegre húztam le.',
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
                'logo' => 'assets/imgs/websites/palesso/palesso_logo.png',
                'tagline' => [
                    'en' => 'Premium fashion and curated lifestyle pieces, presented like it.',
                    'hu' => 'Prémium divat és válogatott életmód-darabok, ahogy azt megérdemlik.',
                ],
                'summary' => [
                    'en' => 'Palesso needed a storefront that matched the price tag of what it was selling. I built a quiet, editorial layout that gets out of the way of the product photography, a personalization flow for made-to-order pieces, and a checkout that does not feel like an afterthought. No stock theme, no cookie-cutter grid — every detail was placed on purpose.',
                    'hu' => 'A Palessónak egy olyan webáruházra volt szüksége, ami illik az általa árult termékek árcédulájához. Egy visszafogott, szerkesztői elrendezést építettem, ami háttérbe húzódik a termékfotók mögött, egy személyre szabási folyamatot az egyedi rendelésekhez, és egy fizetési folyamatot, ami nem tűnik utólagos toldaléknak. Nincs sablon téma, nincs gyári rács — minden részlet tudatosan került a helyére.',
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
                'logo' => 'assets/imgs/websites/kepszakadas/kepszakadas_logo.png',
                'tagline' => [
                    'en' => 'Your ultimate arsenal for escalating the night.',
                    'hu' => 'A végső fegyvertárad az este feldobásához.',
                ],
                'summary' => [
                    'en' => 'Képszakadás Drinking Game is your ultimate arsenal for escalating the night with your crew. Featuring a sleek, modern design, this expanded collection of drinking card games and minigames delivers everything your squad needs to keep the party moving. Optimized for a flawless mobile experience directly from your browser—zero downloads required, instant deployment. Fill your glasses. Let\'s get to work.',
                    'hu' => 'A Képszakadás Ivós Játék a végső fegyvertárad, hogy feldobd az estét a bandáddal. Letisztult, modern dizájnnal ez a bővített ivós kártyajáték- és minijáték-gyűjtemény mindent tartalmaz, amire a csapatodnak szüksége van, hogy pörögjön a buli. Tökéletesen optimalizálva mobilra, egyenesen a böngészőből — nulla letöltés, azonnali indulás. Töltsd meg a poharakat. Kezdődjön a meló.',
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
            'juiced' => [
                'slug' => 'juiced',
                'name' => 'Juiced',
                'logo' => 'assets/imgs/websites/juiced/juiced_logo.png',
                'logo_invert' => true,
                'tagline' => [
                    'en' => 'Where taste meets productivity.',
                    'hu' => 'Ahol az íz találkozik a produktivitással.',
                ],
                'summary' => [
                    'en' => 'Juiced sells flavour on a page that used to feel like a spec sheet. I rebuilt the storefront around the product itself: bold, full-bleed flavour sections that change colour with every drink, a sticks-vs-drinks toggle that actually feels instant, and a browsing experience built mobile-first since that is where the whole catalogue gets shopped. No stock theme, no beige e-commerce grid — every flavour gets its own moment.',
                    'hu' => 'A Juicednek egy olyan oldalra volt szüksége, ami az ízt adja el, nem egy termékkatalógust. A bolti felületet magára a termékre építettem újra: merész, teljes szélességű íz-szekciók, amik minden itallal színt váltanak, egy szívószálak-vs-italok váltó, ami tényleg azonnalinak érződik, és egy mobil-first böngészési élmény, hiszen ott vásárolják végig a teljes kínálatot. Nincs sablon téma, nincs unalmas webáruház-rács — minden íznek saját pillanata van.',
                ],
                'duration' => [
                    'en' => '3 weeks',
                    'hu' => '3 hét',
                ],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'expectation' => [
                    'en' => 'A standard drinks e-commerce catalogue — flavour list, add to cart, checkout.',
                    'hu' => 'Egy hétköznapi ital webáruház-katalógus — íz lista, kosárba tesz, fizetés.',
                ],
                'outcome' => [
                    'en' => 'A full-bleed, colour-shifting storefront that sells the flavour before the ingredient list ever loads — and a flavour-sticks line that outsells the bottles two to one.',
                    'hu' => 'Egy teljes szélességű, színt váltó webáruház, ami már az összetevőlista betöltése előtt eladja az ízt — és egy szívószál-termékvonal, ami kétszer annyit ad el, mint a palackos termékek.',
                ],
                'price' => '390 000 Ft',
            ],
        ];
    }
}

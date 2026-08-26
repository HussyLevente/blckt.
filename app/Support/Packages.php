<?php

namespace App\Support;

use Illuminate\Support\Facades\App;

/**
 * Arak es csomagok - egyetlen helyen.
 *
 * Harom szint van, es MINDKET szolgaltatasban ugyanaz a harom: az egyedi
 * epitesnel es a kesz sablonoknal is. Csak az ar es az atfutas ter el, mert
 * a sablonnal a terv mar megvan. Ez nem veletlen parhuzam - ez maga az
 * ajanlat, ezert egy tablabol jon mind a ketto.
 *
 * Azert all itt es nem a sablonokban, mert harom kulonbozo lap irja ki
 * ugyanezeket a szamokat (arak, cimlap, sablonok). Harom masolatbol elobb-
 * utobb az egyik elcsuszna, es a lap csendben hazudna egy arat.
 */
class Packages
{
    public const BASIC = 'basic';

    public const STANDARD = 'standard';

    public const PREMIUM = 'premium';

    /**
     * Ezer forintos csoportositas, ahogy az oldal tobbi resze is irja.
     */
    public static function money(int $huf): string
    {
        return number_format($huf, 0, ',', ' ').' Ft';
    }

    /**
     * Egyedi epites: a harom csomag.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function services(): array
    {
        return static::localise([
            self::BASIC => [
                'key' => self::BASIC,
                'index' => '01',
                'price' => 80000,
                'pages' => 1,
                'days' => 2,
                'backend' => false,
                'days_label' => ['en' => '1–2 days', 'hu' => '1–2 nap'],
                'summary' => [
                    'en' => 'One page that does one job. Everything a visitor needs is on it, and there is nothing behind it to maintain.',
                    'hu' => 'Egy oldal, egyetlen feladatra. Minden rajta van, amire a látogatónak szüksége van, és nincs mögötte semmi, amit karban kellene tartani.',
                ],
            ],
            self::STANDARD => [
                'key' => self::STANDARD,
                'index' => '02',
                'price' => 150000,
                'pages' => 4,
                'days' => 5,
                'backend' => true,
                'days_label' => ['en' => '5 days', 'hu' => '5 nap'],
                'summary' => [
                    'en' => 'The usual small-business site: home, what you do, who you are, how to reach you. Forms land in your inbox.',
                    'hu' => 'A szokásos kisvállalkozói oldal: főoldal, mit csinálsz, ki vagy, hogyan érnek el. Az űrlapok a postafiókodba érkeznek.',
                ],
            ],
            self::PREMIUM => [
                'key' => self::PREMIUM,
                'index' => '03',
                'price' => 350000,
                'pages' => 6,
                'days' => 14,
                'backend' => true,
                'days_label' => ['en' => '14 days', 'hu' => '14 nap'],
                'summary' => [
                    'en' => 'A site that does something, not just says something. Sell products, take orders, and change the content yourself from an admin panel.',
                    'hu' => 'Olyan oldal, ami csinál is valamit, nem csak mond. Termékeket adsz el, rendeléseket veszel fel, és admin felületről magad írod át a tartalmat.',
                ],
            ],
        ]);
    }

    /**
     * Kesz sablonok: ugyanaz a harom szint, csak a terv mar all.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function templateTiers(): array
    {
        return static::localise([
            self::BASIC => [
                'key' => self::BASIC,
                'index' => '01',
                'price' => 50000,
                'pages' => 1,
                'days' => 1,
                'backend' => false,
                'days_label' => ['en' => '1 day', 'hu' => '1 nap'],
                'summary' => [
                    'en' => 'A single page with your words and pictures in it. Nothing behind the scenes, nothing to log in to, nothing to break.',
                    'hu' => 'Egyetlen oldal, benne a te szavaiddal és képeiddel. Semmi a színfalak mögött, nincs mibe belépni, nincs mi elromoljon.',
                ],
            ],
            self::STANDARD => [
                'key' => self::STANDARD,
                'index' => '02',
                'price' => 100000,
                'pages' => 4,
                'days' => 2,
                'backend' => true,
                'days_label' => ['en' => '2 days', 'hu' => '2 nap'],
                'summary' => [
                    'en' => 'Four pages and working forms. Enough site for most small businesses, at the price of a good phone.',
                    'hu' => 'Négy oldal és működő űrlapok. A legtöbb kisvállalkozásnak ennyi oldal elég, egy jobb telefon áráért.',
                ],
            ],
            self::PREMIUM => [
                'key' => self::PREMIUM,
                'index' => '03',
                'price' => 200000,
                'pages' => 6,
                'days' => 5,
                'backend' => true,
                'days_label' => ['en' => '5 days', 'hu' => '5 nap'],
                'summary' => [
                    'en' => 'Six pages, a webshop or booking flow where the template calls for it, and an admin panel so you are not emailing me to change a price.',
                    'hu' => 'Hat oldal, webáruház vagy foglalási folyamat ott, ahol a sablon megkívánja, és admin felület, hogy egy árváltoztatásért ne nekem kelljen írnod.',
                ],
            ],
        ]);
    }

    /**
     * A ket kulon eset, ami nem fer bele a harom szintbe.
     *
     * Csak az egyedi oldalon van ertelmuk: sablonbol nincs "egyedi", es a
     * felujitas fogalmilag mindig meglevo oldalon dolgozik.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function extras(): array
    {
        $locale = App::getLocale();
        $pick = fn (array $v) => $v[$locale] ?? $v['en'];

        return array_map(fn (array $e) => [
            'key' => $e['key'],
            'index' => $e['index'],
            'name' => $pick($e['name']),
            'price' => $e['price'],
            'price_label' => $e['price'] ? static::money($e['price']) : $pick($e['price_label']),
            'days_label' => $pick($e['days_label']),
            'summary' => $pick($e['summary']),
            'features' => array_map($pick, $e['features']),
        ], [
            [
                'key' => 'custom',
                'index' => '04',
                'name' => ['en' => 'Custom', 'hu' => 'Egyedi'],
                'price' => null,
                'price_label' => ['en' => 'let’s talk', 'hu' => 'beszéljük meg'],
                'days_label' => ['en' => 'Quoted with the scope', 'hu' => 'A terjedelemmel együtt'],
                'summary' => [
                    'en' => 'Your project does not fit in a box. Something the three packages do not cover, something bigger, or something nobody has built before.',
                    'hu' => 'A projekted nem fér dobozba. Valami, amit a három csomag nem fed le, valami nagyobb, vagy valami, amit még senki nem épített meg.',
                ],
                'features' => [
                    ['en' => 'We scope it together first', 'hu' => 'Először közösen felmérjük'],
                    ['en' => 'Fixed written price before anything starts', 'hu' => 'Fix, írásos ár, mielőtt bármi elindul'],
                    ['en' => 'Same three revisions, same handover', 'hu' => 'Ugyanaz a három javítási kör, ugyanaz az átadás'],
                ],
            ],
            [
                'key' => 'revamp',
                'index' => '05',
                'name' => ['en' => 'Revamp', 'hu' => 'Felújítás'],
                'price' => 70000,
                'price_label' => ['en' => '', 'hu' => ''],
                'days_label' => ['en' => 'Depends on the site', 'hu' => 'Az oldaltól függ'],
                'summary' => [
                    'en' => 'You already have a website. It works, it just looks dated and loads slowly. I rebuild the front of it — the design and the speed — and leave your content and your address where they are.',
                    'hu' => 'Már van weboldalad. Működik, csak elavultnak néz ki és lassan tölt. Újraépítem az elejét — a kinézetet és a sebességet —, a tartalmadat és a címedet pedig ott hagyom, ahol van.',
                ],
                'features' => [
                    ['en' => 'New design on your existing content', 'hu' => 'Új dizájn a meglévő tartalmadra'],
                    ['en' => 'Speed pass: images, code, loading order', 'hu' => 'Sebesség: képek, kód, betöltési sorrend'],
                    ['en' => 'Your URLs stay alive, so your rankings do too', 'hu' => 'A linkjeid életben maradnak, így a helyezéseid is'],
                ],
            ],
        ]);
    }

    /**
     * A harom szint kozos jellemzoi.
     *
     * A szoveg egy helyen all, mert a szolgaltatasok es a sablonok lapja is
     * ugyanezt sorolja fel - csak mas arral.
     *
     * @return array<string, array<int, array<string, string>>>
     */
    private static function features(): array
    {
        return [
            self::BASIC => [
                ['en' => 'One page, everything on it', 'hu' => 'Egy oldal, rajta minden'],
                ['en' => 'Responsive — phone, tablet, desktop', 'hu' => 'Reszponzív — telefon, tablet, számítógép'],
                ['en' => 'Speed-optimised and fast to load', 'hu' => 'Sebességre optimalizálva, gyorsan tölt'],
                ['en' => 'No backend: no forms, no admin, no database', 'hu' => 'Nincs backend: se űrlap, se admin, se adatbázis'],
            ],
            self::STANDARD => [
                ['en' => 'Up to 4 pages', 'hu' => 'Legfeljebb 4 oldal'],
                ['en' => 'Forms that land straight in your inbox', 'hu' => 'Űrlapok, amik egyből a postafiókodba érkeznek'],
                ['en' => 'Responsive — phone, tablet, desktop', 'hu' => 'Reszponzív — telefon, tablet, számítógép'],
                ['en' => 'Speed-optimised and fast to load', 'hu' => 'Sebességre optimalizálva, gyorsan tölt'],
                ['en' => 'Backend included', 'hu' => 'Backenddel'],
            ],
            self::PREMIUM => [
                ['en' => 'Up to 6 pages', 'hu' => 'Legfeljebb 6 oldal'],
                ['en' => 'Forms, and whatever else the site has to do', 'hu' => 'Űrlapok, és bármi más, amit az oldalnak tudnia kell'],
                ['en' => 'Webshop: catalogue, cart, checkout', 'hu' => 'Webáruház: katalógus, kosár, fizetés'],
                ['en' => 'Admin panel — you edit the content yourself', 'hu' => 'Admin felület — a tartalmat magad írod át'],
                ['en' => 'Responsive, speed-optimised, fast to load', 'hu' => 'Reszponzív, sebességre optimalizálva, gyorsan tölt'],
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    private static function names(): array
    {
        return [
            self::BASIC => ['en' => 'Basic', 'hu' => 'Alap'],
            self::STANDARD => ['en' => 'Standard', 'hu' => 'Standard'],
            self::PREMIUM => ['en' => 'Premium', 'hu' => 'Prémium'],
        ];
    }

    /**
     * @param  array<string, array<string, mixed>>  $packages
     * @return array<string, array<string, mixed>>
     */
    private static function localise(array $packages): array
    {
        $locale = App::getLocale();
        $pick = fn (array $v) => $v[$locale] ?? $v['en'];
        $features = static::features();
        $names = static::names();

        foreach ($packages as $key => $package) {
            $packages[$key]['name'] = $pick($names[$key]);
            $packages[$key]['price_label'] = static::money($package['price']);
            $packages[$key]['days_label'] = $pick($package['days_label']);
            $packages[$key]['summary'] = $pick($package['summary']);
            $packages[$key]['features'] = array_map($pick, $features[$key]);
        }

        return $packages;
    }
}

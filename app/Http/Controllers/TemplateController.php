<?php

namespace App\Http\Controllers;

use App\Support\Packages;
use Illuminate\Support\Facades\App;

/**
 * Kesz sablonok.
 *
 * Az egyedi epitestol egyetlen dologban ternek el: a terv mar kesz, es
 * tobbszor is eladhato. A kod ugyanaz, a kez ugyanaz - csak a kizarolagossag
 * nincs benne az arban. Ezt a feluleten sehol nem szepitjuk: a "mi valtozik /
 * mi nem" lista es a licenc-szamlalo pont ezert van kint.
 *
 * A harom csomag (Alap / Standard / Premium) nem itt lakik, hanem a
 * Packages-ben, mert az egyedi epites ugyanazt a harmat arulja - csak
 * dragabban. Egy sablon annyit mond magarol, hogy melyik szintre esik; az
 * arat, az oldalszamot es az atfutast onnan kapja.
 */
class TemplateController extends Controller
{
    /** Hany peldany kelhet el egyetlen sablonbol, mielott kivezetjuk. */
    public const LICENCE_CAP = 3;

    public function index(PlaygroundController $playground)
    {
        $locale = App::getLocale();

        // array_values, hogy a nezet szamozott listat kapjon - a slug-kulcsos
        // tomb elszamolna a strukturalt adat ItemList poziciit.
        $templates = array_values(array_map(
            fn (array $t) => $this->resolveLocale($t, $locale),
            $this->available()
        ));

        return view('templates', [
            'templates' => $templates,
            'tiers' => Packages::templateTiers(),
            'services' => Packages::services(),
            'tradeOff' => $this->tradeOff(),
            'floor' => $this->floor(),
            'fastest' => $this->fastest(),
            'demoCount' => $this->demoCount(),
            // A playground sajat kontrollere mondja meg, hany demo
            // szerkesztheto - igy a szam nem tud elcsuszni attol, hogy
            // egy demohoz meg nincs szerkeszto-beallitas.
            'playgrounds' => $playground->count(),
        ]);
    }

    public function show(string $template)
    {
        $templates = $this->available();

        abort_unless(isset($templates[$template]), 404);

        $locale = App::getLocale();
        $slugs = array_keys($templates);
        $position = array_search($template, $slugs, true);
        $nextSlug = $slugs[($position + 1) % count($slugs)];

        return view('templates.show', [
            'template' => $this->resolveLocale($templates[$template], $locale),
            'nextTemplate' => $this->resolveLocale($templates[$nextSlug], $locale),
            'tradeOff' => $this->tradeOff(),
        ]);
    }

    /**
     * A cimlapi sav sablonjai - egy mindegyik szintrol.
     *
     * Igy a harom kartya egyben az arskalat is megmutatja, nem csak harom
     * veletlen tervet.
     *
     * @return array<int, array<string, mixed>>
     */
    public function featured(): array
    {
        $locale = App::getLocale();
        $picked = [];

        foreach (array_keys(Packages::templateTiers()) as $tier) {
            foreach ($this->available() as $template) {
                if ($template['tier'] === $tier) {
                    $picked[] = $this->resolveLocale($template, $locale);

                    break;
                }
            }
        }

        return $picked;
    }

    /**
     * Nehany sablon a lablec oszlopaba.
     *
     * Szamolva, nem kezzel felsorolva: egy kivezetett sablon igy nem hagy
     * 404-re mutato linket a lablecben.
     *
     * @return array<int, array{slug: string, name: string}>
     */
    public function navLinks(int $limit = 4): array
    {
        return array_slice(array_map(
            fn (array $t) => ['slug' => $t['slug'], 'name' => $t['name']],
            array_values($this->available())
        ), 0, $limit);
    }

    /**
     * A legolcsobb sablon ara, illetve a leggyorsabb atfutas.
     *
     * A cimlap es a szolgaltatasok oldal is ebbol dolgozik, hogy a
     * ":price-tol" allitas ne csuszhasson el egy arvaltozas utan.
     */
    public function floor(): int
    {
        return min(array_column(Packages::templateTiers(), 'price'));
    }

    public function fastest(): int
    {
        return min(array_column(Packages::templateTiers(), 'days'));
    }

    public function count(): int
    {
        return count($this->available());
    }

    /**
     * Hany elo demo all osszesen.
     *
     * A lap ebbol irja ki, hany kesz oldal nyithato meg. Nem kezzel beirt
     * szam: amint egy uj demo bekerul a public/demo mappaba es a
     * sablonjahoz ide, a mondat magatol pontos marad.
     */
    public function demoCount(): int
    {
        return count($this->demos());
    }


    /**
     * @return string[]
     */
    public function slugs(): array
    {
        return array_keys($this->available());
    }

    /**
     * Egy demo cime.
     *
     * Az index.html SZANDEKOSAN ki van irva. Az nginx nem olvas .htaccess-t,
     * ezert a public/demo/.htaccess "DirectoryIndex index.html" sora ott nem
     * ervenyesul: a mappara mutato cim 403-at ad, mert van mappa, de nincs
     * kijelolt indexfajl es a listazas tiltva van. A fajlra mutato cimnek
     * viszont minden kiszolgalon mukodnie kell, szerverbeallitas nelkul is.
     *
     * A szep, mappara mutato cimhez lasd: deploy/nginx.conf.
     */
    public static function demoUrl(string $slug): string
    {
        return url('/demo/'.$slug.'/index.html');
    }

    /**
     * Ugyanaz a cim, ahogy a bongeszo-keret cimsavaban all.
     *
     * A latogatonak a mappa a cim; az index.html csak a kiszolgalonak szol,
     * es egy elonezeti keretben csak zaj lenne.
     */
    public static function demoDisplayUrl(string $slug): string
    {
        return preg_replace('~^https?://~', '', url('/demo/'.$slug)).'/';
    }

    /**
     * Minden elo demo, sablontol fuggetlenul, slug szerint kulcsolva.
     *
     * A playground ebbol dolgozik: ott egy demo onalloan is cim, nem csak a
     * sablonja tartozeka. A nevsor viszont marad egy helyen - egy uj demo
     * igy nem igenyel masodik felsorolast.
     *
     * @return array<string, array<string, mixed>>
     */
    public function demos(): array
    {
        $out = [];

        foreach ($this->available() as $template) {
            $resolved = $this->resolveLocale($template, App::getLocale());

            foreach ($resolved['demos'] as $demo) {
                $out[$demo['slug']] = $demo + [
                    'template' => [
                        'slug' => $resolved['slug'],
                        'name' => $resolved['name'],
                        'tier_name' => $resolved['tier_name'],
                        'price_label' => $resolved['price_label'],
                        'url' => $resolved['url'],
                    ],
                ];
            }
        }

        return $out;
    }

    /**
     * Ami minden sablonban valtozik, es ami egyikben sem.
     *
     * Egy helyen tarolva, mert a lista a katalogusban es minden
     * sablonoldalon is megjelenik - ket masolat elobb-utobb elcsuszna.
     *
     * @return array{swap: string[], fixed: string[]}
     */
    public function tradeOff(): array
    {
        $locale = App::getLocale();
        $pick = fn (array $v) => $v[$locale] ?? $v['en'];

        return [
            'swap' => array_map($pick, [
                ['en' => 'Every word on every page', 'hu' => 'Minden szó, minden oldalon'],
                ['en' => 'Every photo, icon and logo', 'hu' => 'Minden fotó, ikon és logó'],
                ['en' => 'Colours and the typeface', 'hu' => 'Színek és a betűtípus'],
                ['en' => 'Your domain, your hosting, your name', 'hu' => 'A saját domained, tárhelyed, neved'],
                ['en' => 'Contact details, opening hours, map', 'hu' => 'Elérhetőség, nyitvatartás, térkép'],
                ['en' => 'Sections you do not need, removed', 'hu' => 'A nem kellő szekciók törölve'],
            ]),
            'fixed' => array_map($pick, [
                ['en' => 'The layout — where things sit on the page', 'hu' => 'Az elrendezés — mi hol áll az oldalon'],
                ['en' => 'The page structure and navigation', 'hu' => 'Az oldalszerkezet és a menü'],
                ['en' => 'The animations and how it moves', 'hu' => 'Az animációk és a mozgás'],
                ['en' => 'Anything that needs new design work', 'hu' => 'Bármi, amihez új tervezés kellene'],
                ['en' => 'Being the only business that has it', 'hu' => 'Hogy csak neked legyen ilyen'],
            ]),
        ];
    }

    private function resolveLocale(array $template, string $locale): array
    {
        $pick = fn (array $values) => $values[$locale] ?? $values['en'];

        foreach (['sector', 'tagline', 'summary', 'best_for'] as $key) {
            $template[$key] = $pick($template[$key]);
        }

        foreach (['structure', 'includes'] as $key) {
            $template[$key] = array_map($pick, $template[$key]);
        }

        // Az ar, az oldalszam es az atfutas a csomagbol jon, nem a sablonbol.
        // Igy egy arvaltozas egy helyen tortenik, es nem lehet olyan sablon,
        // aminek a szintjehez nem illik az ara.
        $tier = Packages::templateTiers()[$template['tier']];

        $template['tier_name'] = $tier['name'];
        $template['price'] = $tier['price'];
        $template['price_label'] = $tier['price_label'];
        $template['pages'] = $tier['pages'];
        $template['days'] = $tier['days'];
        $template['days_label'] = $tier['days_label'];
        $template['backend'] = $tier['backend'];
        $template['tier_features'] = $tier['features'];

        $template['url'] = route('templates.show', $template['slug']);
        $template['left'] = max(0, self::LICENCE_CAP - $template['taken']);
        $template['sold_out'] = $template['left'] === 0;

        // Elo demok.
        $template['demos'] = array_map(fn (array $demo) => [
            'slug' => $demo['slug'],
            'name' => $demo['name'],
            'sector' => $pick($demo['sector']),
            'url' => self::demoUrl($demo['slug']),
            'display_url' => self::demoDisplayUrl($demo['slug']),
            'playground' => route('playground.show', $demo['slug']),
        ], $template['demos'] ?? []);

        $template['has_demo'] = $template['demos'] !== [];

        // A keresok kb. 155 karakter utan vagnak; a tagline + ar ennel
        // rovidebb, es pont azt mondja, amit egy talalati sorban erdemes.
        $template['meta'] = $template['name'].' — '.$template['tagline'].' '
            .__(':price, live in :days.', [
                'price' => $template['price_label'],
                'days' => mb_strtolower($template['days_label']),
            ]);

        return $template;
    }

    /**
     * A megvasarolhato sablonok.
     *
     * Egy sablon kivezetesehez eleg a 'retired' => true kulcs: eltunik a
     * listabol, a cimlaprol, a sitemapbol es a kozvetlen cimrol is, de az
     * adatai megmaradnak.
     *
     * @return array<string, array<string, mixed>>
     */
    private function available(): array
    {
        return array_filter($this->templates(), fn (array $t) => empty($t['retired']));
    }

    /**
     * A sablonok, szintenkent csoportositva.
     *
     * A sorrend nem veletlen: olcsotol dragaig halad, mert a katalogus is
     * igy olvasodik, es a cimlapi sav szintenkent az elsot emeli ki.
     *
     * @return array<string, array<string, mixed>>
     */
    private function templates(): array
    {
        return [
            // ── Alap: egy oldal, backend nelkul ──────────────────────
            'signal' => [
                'slug' => 'signal',
                'name' => 'SIGNAL',
                'tier' => Packages::BASIC,
                'taken' => 0,
                'preview' => 'assets/imgs/templates/signal.webp',
                'sector' => [
                    'en' => 'One-page launch',
                    'hu' => 'Egyoldalas indítás',
                ],
                'tagline' => [
                    'en' => 'One page, one job: get the phone to ring.',
                    'hu' => 'Egy oldal, egy feladat: csörögjön a telefon.',
                ],
                'summary' => [
                    'en' => 'The smallest thing that still counts as a real website. Everything sits on one scrolling page — what you do, why you are worth it, and how to reach you. Nothing behind it, so there is nothing to maintain.',
                    'hu' => 'A legkisebb dolog, ami még valódi weboldalnak számít. Minden egyetlen görgethető oldalon van — mit csinálsz, miért érsz annyit, és hogyan érnek el. Nincs mögötte semmi, tehát nincs is mit karbantartani.',
                ],
                'best_for' => [
                    'en' => 'A new business, a single service, or anyone who currently has nothing but a Facebook page.',
                    'hu' => 'Új vállalkozásnak, egyetlen szolgáltatáshoz, vagy bárkinek, akinek most csak egy Facebook-oldala van.',
                ],
                'structure' => [
                    ['en' => 'Hero with your one-line pitch', 'hu' => 'Nyitókép egysoros ajánlattal'],
                    ['en' => 'Three things you do', 'hu' => 'Három dolog, amit csinálsz'],
                    ['en' => 'Proof — reviews or numbers', 'hu' => 'Bizonyíték — vélemények vagy számok'],
                    ['en' => 'Contact details and a map', 'hu' => 'Elérhetőség és térkép'],
                ],
                'includes' => [
                    ['en' => 'Tap to call, tap to email, tap to open the map', 'hu' => 'Koppintásra hívás, e-mail és térkép'],
                    ['en' => 'Google Business profile linked up', 'hu' => 'Google cégprofil bekötve'],
                    ['en' => 'No form, so no spam and nothing to maintain', 'hu' => 'Nincs űrlap, így nincs spam és nincs mit karbantartani'],
                ],
                // Ket demo ugyanabbol a sablonbol, szandekosan a lehető
                // legtavolabbi ket szakmaval: egy ugyvedi iroda es egy
                // burgerezo. Ez bizonyitja azt, amit elmondani nem lehet -
                // hogy a tartalom tenyleg kicserelheto benne.
                'demos' => [
                    [
                        'slug' => 'signal-burger',
                        'name' => 'Signal Burger',
                        'sector' => ['en' => 'Burger restaurant', 'hu' => 'Burgerező'],
                    ],
                    [
                        'slug' => 'signal-attorney',
                        'name' => 'Armitage & Co.',
                        'sector' => ['en' => 'Law firm', 'hu' => 'Ügyvédi iroda'],
                    ],
                ],
            ],

            'aperture' => [
                'slug' => 'aperture',
                'name' => 'APERTURE',
                'tier' => Packages::BASIC,
                'taken' => 0,
                'preview' => 'assets/imgs/templates/aperture.webp',
                'sector' => [
                    'en' => 'Portfolio & photography',
                    'hu' => 'Portfólió és fotó',
                ],
                'tagline' => [
                    'en' => 'Your work at full size, nothing else in the way.',
                    'hu' => 'A munkád teljes méretben, semmi nem áll az útjában.',
                ],
                'summary' => [
                    'en' => 'Built around images rather than text. One long page of edge-to-edge galleries, with typography quiet enough to stay out of the pictures.',
                    'hu' => 'Képekre épül, nem szövegre. Egyetlen hosszú oldal, széltől szélig érő galériákkal, és annyira csendes tipográfiával, hogy ne szóljon bele a képekbe.',
                ],
                'best_for' => [
                    'en' => 'Photographers, illustrators, architects, tattoo artists — anyone whose work sells itself once someone actually sees it.',
                    'hu' => 'Fotósoknak, illusztrátoroknak, építészeknek, tetoválóknak — bárkinek, akinek a munkája eladja magát, ha egyszer tényleg meglátják.',
                ],
                'structure' => [
                    ['en' => 'Hero with the one image that does the work', 'hu' => 'Nyitókép azzal az egy képpel, ami elvégzi a munkát'],
                    ['en' => 'Gallery — your series, full width', 'hu' => 'Galéria — a sorozataid, teljes szélességben'],
                    ['en' => 'About and how you work', 'hu' => 'Rólad és a munkamódszeredről'],
                    ['en' => 'Contact details', 'hu' => 'Elérhetőség'],
                ],
                'includes' => [
                    ['en' => 'Lightbox viewer with zoom', 'hu' => 'Nagyítható képnézegető'],
                    ['en' => 'Images compressed so the gallery stays fast', 'hu' => 'Tömörített képek, hogy a galéria gyors maradjon'],
                    ['en' => 'Right-click protection on the gallery', 'hu' => 'Jobbklikk-védelem a galérián'],
                ],
                'demos' => [
                    [
                        'slug' => 'aperture-portfolio',
                        'name' => 'Ada Vale',
                        'sector' => ['en' => 'Photographer', 'hu' => 'Fotós'],
                    ],
                    [
                        'slug' => 'aperture-contentcreator',
                        'name' => 'Ari Vale',
                        'sector' => ['en' => 'Content creator', 'hu' => 'Tartalomgyártó'],
                    ],
                ],
            ],

            // ── Standard: negy oldal, urlapokkal ─────────────────────
            'atrium' => [
                'slug' => 'atrium',
                'name' => 'ATRIUM',
                'tier' => Packages::STANDARD,
                'taken' => 0,
                'preview' => 'assets/imgs/templates/atrium.webp',
                'sector' => [
                    'en' => 'Restaurant & café',
                    'hu' => 'Étterem és kávézó',
                ],
                'tagline' => [
                    'en' => 'The menu, the hours, the table. In that order.',
                    'hu' => 'Az étlap, a nyitvatartás, az asztal. Ebben a sorrendben.',
                ],
                'summary' => [
                    'en' => 'Everything a hungry person on a phone needs within one thumb-scroll: today’s menu, whether you are open, and how to book. The gallery comes after that, not before.',
                    'hu' => 'Minden, ami egy éhes embernek kell a telefonján, egyetlen hüvelykujjnyi görgetésen belül: a mai étlap, hogy nyitva vagy-e, és hogyan lehet asztalt foglalni. A galéria ez után jön, nem előtte.',
                ],
                'best_for' => [
                    'en' => 'Restaurants, cafés, bakeries, bars — anywhere the menu changes more often than the website should.',
                    'hu' => 'Éttermeknek, kávézóknak, pékségeknek, bároknak — ahol az étlap gyakrabban változik, mint amilyen gyakran a weboldalnak kellene.',
                ],
                'structure' => [
                    ['en' => 'Home with hours and today’s highlight', 'hu' => 'Főoldal nyitvatartással és a nap ajánlatával'],
                    ['en' => 'Menu, split by course', 'hu' => 'Étlap, fogások szerint bontva'],
                    ['en' => 'Gallery of the room and the plates', 'hu' => 'Galéria a térről és a tányérokról'],
                    ['en' => 'Find us and book a table', 'hu' => 'Hol vagyunk és asztalfoglalás'],
                ],
                'includes' => [
                    ['en' => 'Booking form that lands in your inbox', 'hu' => 'Foglalási űrlap, ami a postafiókodba érkezik'],
                    ['en' => 'Opening hours that show open or closed now', 'hu' => 'Nyitvatartás, ami mutatja: most nyitva vagy zárva'],
                    ['en' => 'Allergen markers on menu items', 'hu' => 'Allergénjelölések az ételeknél'],
                ],
                'demos' => [
                    [
                        'slug' => 'atrium-restaurant',
                        'name' => 'Atrium',
                        'sector' => ['en' => 'Restaurant', 'hu' => 'Étterem'],
                    ],
                    [
                        'slug' => 'atrium-caffee',
                        'name' => 'Atrium Coffee',
                        'sector' => ['en' => 'Coffee shop', 'hu' => 'Kávézó'],
                    ],
                ],
            ],

            'poise' => [
                'slug' => 'poise',
                'name' => 'POISE',
                'tier' => Packages::STANDARD,
                'taken' => 0,
                'preview' => 'assets/imgs/templates/poise.webp',
                'sector' => [
                    'en' => 'Salon, barber & studio',
                    'hu' => 'Szalon, fodrász és stúdió',
                ],
                'tagline' => [
                    'en' => 'Price list up front, booking one tap away.',
                    'hu' => 'Árlista elöl, foglalás egy koppintásra.',
                ],
                'summary' => [
                    'en' => 'People check two things before they book: what it costs and who is doing it. Both are above the fold here, and the booking button follows you down the page.',
                    'hu' => 'Foglalás előtt két dolgot néznek meg: mennyibe kerül és ki csinálja. Itt mindkettő rögtön látszik, a foglalás gomb pedig végigkísér az oldalon.',
                ],
                'best_for' => [
                    'en' => 'Hairdressers, barbers, nail and beauty studios, massage, physio — anything sold by the appointment.',
                    'hu' => 'Fodrászoknak, barbereknek, köröm- és szépségstúdióknak, masszázsnak, gyógytornának — bárminek, amit időpontra adnak el.',
                ],
                'structure' => [
                    ['en' => 'Home with the booking call to action', 'hu' => 'Főoldal a foglalás gombbal'],
                    ['en' => 'Services and price list', 'hu' => 'Szolgáltatások és árlista'],
                    ['en' => 'The team', 'hu' => 'A csapat'],
                    ['en' => 'Booking and directions', 'hu' => 'Foglalás és megközelítés'],
                ],
                'includes' => [
                    ['en' => 'Booking form, or your existing system linked up', 'hu' => 'Foglalási űrlap, vagy a meglévő rendszered bekötve'],
                    ['en' => 'Per-stylist profiles and gallery', 'hu' => 'Külön profil és galéria minden kollégának'],
                    ['en' => 'Price list laid out so it stays readable on a phone', 'hu' => 'Árlista úgy tördelve, hogy telefonon is olvasható maradjon'],
                ],
                'demos' => [
                    [
                        'slug' => 'poise-hairdresser',
                        'name' => 'POISE',
                        'sector' => ['en' => 'Hair studio', 'hu' => 'Fodrászat'],
                    ],
                    [
                        'slug' => 'poise-mechanic',
                        'name' => 'POISE Automotive',
                        'sector' => ['en' => 'Garage', 'hu' => 'Autószerviz'],
                    ],
                    [
                        'slug' => 'poise-mechanic2',
                        'name' => 'Blackline Motorworks',
                        'sector' => ['en' => 'Performance garage', 'hu' => 'Teljesítményműhely'],
                    ],
                ],
            ],

            // ── Premium: hat oldal, admin felulettel ─────────────────
            'foundry' => [
                'slug' => 'foundry',
                'name' => 'FOUNDRY',
                'tier' => Packages::PREMIUM,
                'taken' => 0,
                'preview' => 'assets/imgs/templates/foundry.webp',
                'sector' => [
                    'en' => 'Furniture, homeware & craft',
                    'hu' => 'Bútor, lakberendezés, kézműves',
                ],
                'tagline' => [
                    'en' => 'A shop that behaves like a gallery.',
                    'hu' => 'Bolt, ami galériaként viselkedik.',
                ],
                'summary' => [
                    'en' => 'For a catalogue where each piece deserves a page of its own. Serif typography, a lot of air, and a full cart and checkout underneath the calm — plus customer accounts and an admin panel for stock and orders.',
                    'hu' => 'Olyan kínálathoz, ahol minden darab megérdemel egy saját oldalt. Talpas betűk, sok levegő, és a nyugalom alatt egy teljes kosár és fizetés — plusz vásárlói fiókok és admin felület a készlethez és a rendelésekhez.',
                ],
                'best_for' => [
                    'en' => 'Furniture makers, ceramicists, homeware labels, galleries — a small range of considered objects with a considered price.',
                    'hu' => 'Bútorkészítőknek, keramikusoknak, lakberendezési márkáknak, galériáknak — kis kínálat átgondolt tárgyakból, átgondolt áron.',
                ],
                'structure' => [
                    ['en' => 'Home — the manifesto and the featured object', 'hu' => 'Főoldal — a kiáltvány és a kiemelt darab'],
                    ['en' => 'Collection, with filtering', 'hu' => 'Kollekció, szűrővel'],
                    ['en' => 'Bag', 'hu' => 'Kosár'],
                    ['en' => 'Checkout', 'hu' => 'Fizetés'],
                    ['en' => 'Customer profile and past orders', 'hu' => 'Vásárlói profil és korábbi rendelések'],
                    ['en' => 'Studio and contact', 'hu' => 'Stúdió és kapcsolat'],
                ],
                'includes' => [
                    ['en' => 'Card payment and bank transfer', 'hu' => 'Bankkártyás fizetés és átutalás'],
                    ['en' => 'Customer accounts with order history', 'hu' => 'Vásárlói fiókok rendeléstörténettel'],
                    ['en' => 'Admin panel: add finished jobs yourself', 'hu' => 'Admin felület: az elkészült munkákat magad töltöd fel'],
                ],
                'demos' => [
                    [
                        'slug' => 'foundry-furniture',
                        'name' => 'Atelier Noma',
                        'sector' => ['en' => 'Furniture maker', 'hu' => 'Bútorkészítő'],
                    ],
                ],
            ],

            'cargo' => [
                'slug' => 'cargo',
                'name' => 'CARGO',
                'tier' => Packages::PREMIUM,
                'taken' => 0,
                'preview' => 'assets/imgs/templates/cargo.webp',
                'sector' => [
                    'en' => 'Small webshop',
                    'hu' => 'Kis webáruház',
                ],
                'tagline' => [
                    'en' => 'A shop that sells, without the platform rent.',
                    'hu' => 'Bolt, ami elad — platformbérleti díj nélkül.',
                ],
                'summary' => [
                    'en' => 'A real storefront for a catalogue you can count: product pages, cart, checkout and stock. Built to carry roughly fifty products comfortably. Past that you want a custom build, and I will say so.',
                    'hu' => 'Valódi bolt megszámolható kínálathoz: termékoldalak, kosár, fizetés, készlet. Nagyjából ötven termékig kényelmesen elbírja. Efölött már egyedi építés kell, és ezt meg is fogom mondani.',
                ],
                'best_for' => [
                    'en' => 'Makers, small labels, roasters, delis — anyone selling a tight range who is tired of paying a platform every month.',
                    'hu' => 'Kézműveseknek, kis márkáknak, pörkölőknek, csemegeboltoknak — bárkinek, aki szűk kínálatot ad el, és unja a havi platformdíjat.',
                ],
                'structure' => [
                    ['en' => 'Home with featured products', 'hu' => 'Főoldal kiemelt termékekkel'],
                    ['en' => 'Catalogue with categories', 'hu' => 'Katalógus kategóriákkal'],
                    ['en' => 'Product page with variants', 'hu' => 'Termékoldal változatokkal'],
                    ['en' => 'Cart and checkout', 'hu' => 'Kosár és fizetés'],
                    ['en' => 'Shipping, returns and the legal pages', 'hu' => 'Szállítás, visszaküldés és a jogi oldalak'],
                    ['en' => 'About the maker', 'hu' => 'A készítőről'],
                ],
                'includes' => [
                    ['en' => 'Card payment and bank transfer', 'hu' => 'Bankkártyás fizetés és átutalás'],
                    ['en' => 'Hungarian courier options at checkout', 'hu' => 'Magyar futárszolgálatok a fizetésnél'],
                    ['en' => 'Admin panel: products, prices, stock, orders', 'hu' => 'Admin felület: termékek, árak, készlet, rendelések'],
                ],
                'demos' => [
                    [
                        'slug' => 'cargo-shoes',
                        'name' => 'CARGO/00',
                        'sector' => ['en' => 'Sneaker archive', 'hu' => 'Sneaker-archívum'],
                    ],
                ],
            ],
        ];
    }
}

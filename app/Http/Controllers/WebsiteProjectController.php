<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class WebsiteProjectController extends Controller
{
    /**
     * Where a walkthrough video is expected to live once it is recorded.
     * Nothing breaks while the file is missing - the player falls back to a
     * poster-only "recording soon" state.
     */
    private const VIDEO_DIR = 'assets/videos';

    public function show(string $project)
    {
        // Szandekosan a lathato listaval dolgozunk: egy elrejtett munka igy
        // kozvetlen cimen sem nyithato meg, es a "kovetkezo projekt"
        // lancba sem kerul bele.
        $projects = $this->visible();

        abort_unless(isset($projects[$project]), 404);

        $locale = App::getLocale();
        $slugs = array_keys($projects);
        $position = array_search($project, $slugs, true);
        $nextSlug = $slugs[($position + 1) % count($slugs)];

        return view('websites.show', [
            'project' => $this->resolveLocale($projects[$project], $locale),
            'nextProject' => $this->resolveLocale($projects[$nextSlug], $locale),
        ]);
    }

    /**
     * The /websites index: live work first, upcoming work kept deliberately secondary.
     */
    public function index()
    {
        $locale = App::getLocale();

        return view('websites', [
            'live' => $this->byKind('live', $locale),
            'concepts' => $this->byKind('concept', $locale),
            'designs' => $this->byKind('design', $locale),
            'upcoming' => array_map(
                fn (array $entry) => $this->resolveUpcomingLocale($entry, $locale),
                $this->upcoming()
            ),
        ]);
    }

    /**
     * Elo, atadott munkak vagy csak tervek.
     *
     * A ketto sosem keveredik a feluleten: ami nem all elo cimen, azt
     * kifejezetten tervkent kell jelolni.
     *
     * @return array<int, array<string, mixed>>
     */
    private function byKind(string $kind, string $locale): array
    {
        return array_values(array_map(
            fn (array $project) => $this->resolveLocale($project, $locale),
            array_filter($this->visible(), fn (array $p) => ($p['kind'] ?? 'design') === $kind)
        ));
    }

    /**
     * A publikusan lathato munkak.
     *
     * Az elrejtes egyetlen ponton dol el, ezert nem lehet elfelejteni valahol:
     * a listakat, a cimlapot, a sitemapet, a "kovetkezo projekt" linket es a
     * kozvetlen cimet is ez a szures hatarozza meg. Egy munka elrejtesehez
     * eleg a 'hidden' => true kulcs - az adatai bantatlanul megmaradnak.
     *
     * @return array<string, array<string, mixed>>
     */
    private function visible(): array
    {
        return array_filter($this->projects(), fn (array $p) => empty($p['hidden']));
    }

    /**
     * Nehany munka a lablec "Munkak" oszlopaba: elo munkak elol, utana tervek.
     *
     * Azert szamoljuk es nem kezzel soroljuk fel, mert a kezzel beirt linkek
     * egy elrejtes utan 404-re mutatnanak.
     *
     * @return array<int, array{slug: string, name: string}>
     */
    public function navLinks(int $limit = 4): array
    {
        $visible = $this->visible();
        $isLive = fn (array $p) => ($p['kind'] ?? 'design') === 'live';

        $ordered = array_merge(
            array_values(array_filter($visible, $isLive)),
            array_values(array_filter($visible, fn (array $p) => ! $isLive($p)))
        );

        return array_slice(array_map(
            fn (array $p) => ['slug' => $p['slug'], 'name' => $p['name']],
            $ordered
        ), 0, $limit);
    }

    /**
     * The most recent live builds, for the homepage.
     *
     * @return array<int, array<string, mixed>>
     */
    public function featured(int $limit = 2): array
    {
        return array_slice($this->byKind('live', App::getLocale()), 0, $limit);
    }

    /**
     * Hany elo munka lathato most.
     *
     * A cimlapi teny-sor ebbol dolgozik, hogy az allitas ne csuszhasson el a
     * valosagtol, ha egy munkat elrejtunk vagy visszahozunk.
     */
    public function liveCount(): int
    {
        return count(array_filter(
            $this->visible(),
            fn (array $p) => ($p['kind'] ?? 'design') === 'live'
        ));
    }

    /**
     * @return string[]
     */
    public function slugs(): array
    {
        // A sitemap ebbol epul: elrejtett munka nem kerulhet bele, kulonben a
        // keresok egy 404-es cimet kapnanak felkinalva.
        return array_keys($this->visible());
    }

    private function resolveLocale(array $project, string $locale): array
    {
        $pick = fn (array $values) => $values[$locale] ?? $values['en'];

        foreach (['tagline', 'sector', 'type', 'problem', 'approach', 'value'] as $key) {
            $project[$key] = $pick($project[$key]);
        }

        // Atfutas csak ott van megadva, ahol ismerjuk. A redesign_note
        // szandekosan nyers marad: azt a redesign() oldja fel.
        if (isset($project['duration'])) {
            $project['duration'] = $pick($project['duration']);
        }

        // Ellenorizheto szamok. Elotte/utana metrika mar nincs: azok
        // korabban kitalalt ertekek voltak valodi ugyfelek neveben.
        $project['figures'] = array_map(
            fn (array $figure) => [
                'label' => $pick($figure['label']),
                'value' => $figure['value'],
            ],
            $project['figures'] ?? []
        );

        $project['highlights'] = array_map($pick, $project['highlights']);

        $images = $this->gallery($project['slug']);

        $project['gallery'] = array_map(
            fn (string $src, int $index) => [
                'src' => $src,
                'alt' => $project['name'].' — '.__('screen :n', ['n' => $index]),
            ],
            $images,
            range(1, count($images))
        );

        $project['card'] = $this->firstExisting("assets/imgs/websites/{$project['slug']}/{$project['slug']}_after")
            ?? ($images[0] ?? "assets/imgs/websites/{$project['slug']}/{$project['slug']}_minis1.jpg");

        $project['redesign'] = $this->redesign($project, $images, $pick);
        $project['video'] = $this->video($project, $images, $pick);
        $project['is_live'] = ($project['kind'] ?? 'design') === 'live';

        /* Koncepcio: valodi, megnyithato cimen fut, de kitalalt marka all
           mogotte. Kulon jelzo kell ra, mert a ket meglevo allapot egyike
           sem igaz ra - "elo ugyfelmunkakent" felnagyitana a cimlapi
           szamot, "csak tervkent" pedig letagadna a mukodo linket. */
        $project['is_concept'] = ($project['kind'] ?? 'design') === 'concept';
        $project['has_link'] = ! empty($project['url']);

        // A keresok kb. 155 karakter utan levagjak a leirast. A teljes
        // "problema" bekezdes ennel jóval hosszabb, ezert a meta leirast a
        // taglineból es az eredmenybol allitjuk ossze, szohatáron vágva.
        $project['meta'] = $this->trimTo($project['tagline'].' '.$project['value'], 155);

        return $project;
    }

    /**
     * Szohataron vago rovidites - sosem vag szo kozepen, es nem tesz ki
     * harom pontot, ha egyben elfert.
     */
    private function trimTo(string $text, int $limit): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text));

        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $cut = mb_substr($text, 0, $limit - 1);
        $lastSpace = mb_strrpos($cut, ' ');

        if ($lastSpace !== false) {
            $cut = mb_substr($cut, 0, $lastSpace);
        }

        return rtrim($cut, " ,.;:—-").'…';
    }

    private function resolveUpcomingLocale(array $entry, string $locale): array
    {
        $pick = fn (array $values) => $values[$locale] ?? $values['en'];

        foreach (['sector', 'teaser', 'stage'] as $key) {
            $entry[$key] = $pick($entry[$key]);
        }

        return $entry;
    }

    /**
     * The walkthrough video for a project.
     *
     * The file is optional: drop {slug}_walkthrough.mp4 into public/assets/videos
     * and the player picks it up on the next request. Until then the component
     * renders the poster with a "recording soon" badge instead of a dead <video>.
     *
     * @param  string[]  $gallery
     * @return array<string, mixed>
     */
    private function video(array $project, array $gallery, callable $pick): array
    {
        $source = null;
        $mime = null;

        foreach (['mp4' => 'video/mp4', 'webm' => 'video/webm'] as $ext => $type) {
            $relative = self::VIDEO_DIR."/{$project['slug']}_walkthrough.{$ext}";

            if (file_exists(public_path($relative))) {
                $source = $relative;
                $mime = $type;
                break;
            }
        }

        $poster = $this->firstExisting("assets/imgs/websites/{$project['slug']}/{$project['slug']}_after")
            ?? ($gallery[0] ?? null);

        return [
            'src' => $source,
            'mime' => $mime,
            'poster' => $poster,
            'name' => $project['name'],
            'caption' => isset($project['video_caption']) ? $pick($project['video_caption']) : null,
            'duration' => $project['video_duration'] ?? null,
        ];
    }

    /**
     * The before/after pair for a project.
     *
     * "Before" is the client's original site, dropped in as {slug}_before.{ext}.
     * "After" is the first gallery shot unless the project ships its own {slug}_after.{ext}.
     * Returns null when no before-image exists, so the section simply stays hidden.
     *
     * @param  string[]  $gallery
     * @return array<string, mixed>|null
     */
    private function redesign(array $project, array $gallery, callable $pick): ?array
    {
        $before = $this->firstExisting("assets/imgs/websites/{$project['slug']}/{$project['slug']}_before");

        if ($before === null) {
            return null;
        }

        $after = $this->firstExisting("assets/imgs/websites/{$project['slug']}/{$project['slug']}_after")
            ?? ($gallery[0] ?? null);

        if ($after === null) {
            return null;
        }

        return [
            'slug' => $project['slug'],
            'name' => $project['name'],
            'before' => $before,
            'after' => $after,
            'note' => isset($project['redesign_note']) ? $pick($project['redesign_note']) : null,
        ];
    }

    /**
     * Resolve a path given without an extension to the first image that exists on disk.
     */
    private function firstExisting(string $pathWithoutExtension): ?string
    {
        foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
            $relative = "{$pathWithoutExtension}.{$ext}";

            if (file_exists(public_path($relative))) {
                return $relative;
            }
        }

        return null;
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
     * Work that is booked but not shipped. Deliberately thin - sector, stage and a
     * one-line teaser, no client names until they go live.
     *
     * @return array<int, array<string, mixed>>
     */
    private function upcoming(): array
    {
        return [
            [
                'code' => 'N-01',
                'sector' => ['en' => 'Hospitality', 'hu' => 'Vendéglátás'],
                'stage' => ['en' => 'In build', 'hu' => 'Fejlesztés alatt'],
                'progress' => 70,
                'window' => 'Q4 2026',
                'teaser' => [
                    'en' => 'A table-booking site for a Budapest restaurant group, where reserving takes one screen instead of a phone call.',
                    'hu' => 'Asztalfoglaló oldal egy budapesti étteremcsoportnak, ahol a foglalás egy képernyő, nem egy telefonhívás.',
                ],
            ],
            [
                'code' => 'N-02',
                'sector' => ['en' => 'Specialty retail', 'hu' => 'Szaküzlet'],
                'stage' => ['en' => 'In design', 'hu' => 'Tervezés alatt'],
                'progress' => 40,
                'window' => 'Q1 2027',
                'teaser' => [
                    'en' => 'A webshop for a coffee roastery that sells by taste profile rather than by bean name.',
                    'hu' => 'Webáruház egy kávépörkölőnek, ami ízprofil szerint árul, nem babnév szerint.',
                ],
            ],
            [
                'code' => 'N-03',
                'sector' => ['en' => 'Wellness', 'hu' => 'Wellness'],
                'stage' => ['en' => 'Scoping', 'hu' => 'Felmérés alatt'],
                'progress' => 15,
                'window' => 'Q2 2027',
                'teaser' => [
                    'en' => 'A booking platform for independent studios, built so a class can be reserved in under thirty seconds.',
                    'hu' => 'Foglalási platform független stúdióknak, ahol egy óra harminc másodperc alatt lefoglalható.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function projects(): array
    {
        return [
            /* ---------------------------------------------------------------
               Elo, ugyfelnek atadott munkak.
               Itt csak olyan allitas szerepel, ami magan az elo oldalon
               ellenorizheto - forgalmi vagy konverzios szamot nem kozlunk,
               mert olyan adatunk nincs. Ar es atfutas sem szerepel: az az
               ugyfel uzleti adata.
               --------------------------------------------------------------- */
            'muzsik' => [
                'slug' => 'muzsik',
                'name' => 'Muzsik Fodrászat',
                'logo' => 'assets/imgs/websites/muzsik/muzsik_logo.png',
                'kind' => 'live',

                // Ideiglenesen rejtve. A teljes leiras, a kepek es a videó
                // erintetlenul itt maradnak - a visszahozashoz ezt az egy
                // sort kell torolni. Lasd: visible().
                'hidden' => true,

                'url' => 'https://hussylevente.github.io/muzsik_fodraszat/',
                'year' => '2026',
                'sector' => ['en' => 'Hair salon', 'hu' => 'Fodrászat'],
                'type' => ['en' => 'Salon website', 'hu' => 'Szalon weboldal'],
                'tagline' => [
                    'en' => 'A salon you can judge before you book.',
                    'hu' => 'Egy szalon, amit foglalás előtt meg tudsz ítélni.',
                ],
                'problem' => [
                    'en' => 'A salon lives on trust: people want to see the work, meet the stylists and know what is used on their hair before they hand any of it over. All of that had to fit on one page without turning into a brochure nobody scrolls.',
                    'hu' => 'Egy szalon bizalomból él: az emberek látni akarják a munkát, megismerni a fodrászokat, és tudni, mi kerül a hajukra, mielőtt rábíznák magukat. Mindennek el kellett férnie egy oldalon anélkül, hogy prospektussá váljon, amit senki nem görget végig.',
                ],
                'approach' => [
                    'en' => 'One long, scroll-driven page: a hero where the model’s colour transforms as you scroll, a gallery of real work, the stylists introduced by name, and the Demeral product partnership given its own chapter. Booking stays anchored on screen the whole way down, and the site carries its own blog and on-site search.',
                    'hu' => 'Egyetlen hosszú, görgetésre épülő oldal: egy hero, ahol a modell hajszíne görgetés közben alakul át, valódi munkák galériája, a fodrászok név szerint bemutatva, és a Demeral partnerség saját fejezettel. Az időpontfoglalás végig a képernyőn marad, az oldal pedig saját blogot és keresőt is visz.',
                ],
                'value' => [
                    'en' => 'Everything a first-time client asks before booking — who will cut my hair, what will it look like, what goes on it — is answered on the page, with the booking button never more than one tap away.',
                    'hu' => 'Minden, amit egy új vendég foglalás előtt megkérdez — ki vágja a hajam, hogy fog kinézni, mi kerül rá — meg van válaszolva az oldalon, a foglalás gomb pedig sosincs egy koppintásnál messzebb.',
                ],
                'highlights' => [
                    ['en' => 'Scroll-driven colour transformation', 'hu' => 'Görgetésre épülő színátalakulás'],
                    ['en' => 'Stylists introduced by name', 'hu' => 'Fodrászok név szerint bemutatva'],
                    ['en' => 'Persistent booking call to action', 'hu' => 'Végig elérhető időpontfoglalás'],
                    ['en' => 'Built-in blog and on-site search', 'hu' => 'Beépített blog és kereső'],
                ],
                'figures' => [],
                'tools' => ['Figma', 'HTML', 'CSS', 'JavaScript'],
                'video_duration' => '0:58',
                'video_caption' => [
                    'en' => 'The full page end to end, including the scroll-driven hero.',
                    'hu' => 'A teljes oldal elejétől a végéig, a görgetésre épülő heróval együtt.',
                ],
            ],

            'passion' => [
                'slug' => 'passion',
                'name' => 'Passion Gumiszerviz',
                'kind' => 'live',

                // Ideiglenesen rejtve - lasd a muzsik bejegyzesnel.
                'hidden' => true,

                'url' => 'https://hussylevente.github.io/passion_gumiszerviz/',
                'year' => '2026',
                'sector' => ['en' => 'Tyre service', 'hu' => 'Gumiszerviz'],
                'type' => ['en' => 'Local business site', 'hu' => 'Helyi vállalkozás oldala'],
                'tagline' => [
                    'en' => 'A tyre shop that opens with a car, not a price list.',
                    'hu' => 'Egy gumiszerviz, ami autóval nyit, nem árlistával.',
                ],
                'problem' => [
                    'en' => 'Most tyre-service sites are a phone number buried under a wall of specifications. For a workshop in Solymár the job was simpler and harder: look like somewhere you would trust your car, and make the address, the hours and the booking impossible to miss.',
                    'hu' => 'A legtöbb gumiszerviz-oldal egy telefonszám, elásva a műszaki adatok fala alatt. Egy solymári műhelynél a feladat egyszerűbb és nehezebb volt: úgy kell kinéznie, mint ahol rábíznád az autódat, a címnek, a nyitvatartásnak és a foglalásnak pedig lehetetlen legyen nem észrevenni.',
                ],
                'approach' => [
                    'en' => 'A short, confident page. Full-bleed car photography up top with the service promises annotated straight onto it, then the reasons to choose the workshop, then everything practical — address, phone, opening hours and a map — in one block at the bottom. Booking sits in a floating button that follows you down the page.',
                    'hu' => 'Rövid, magabiztos oldal. Felül teljes szélességű autófotó, a szolgáltatási ígéretekkel közvetlenül rárajzolva, aztán az érvek a műhely mellett, végül minden gyakorlati tudnivaló — cím, telefon, nyitvatartás és térkép — egyetlen blokkban. Az időpontfoglalás egy lebegő gombban követi a görgetést.',
                ],
                'value' => [
                    'en' => 'A visitor can get the address, the opening hours, the phone number and a booking from a single screen — the four things anyone actually opens a tyre-service site for.',
                    'hu' => 'A látogató egyetlen képernyőről megkapja a címet, a nyitvatartást, a telefonszámot és a foglalást — azt a négy dolgot, amiért egy gumiszerviz oldalát egyáltalán megnyitják.',
                ],
                'highlights' => [
                    ['en' => 'Annotated full-bleed hero photography', 'hu' => 'Feliratozott, teljes szélességű hero fotó'],
                    ['en' => 'Floating booking button throughout', 'hu' => 'Végig lebegő foglalás gomb'],
                    ['en' => 'Address, hours and map in one block', 'hu' => 'Cím, nyitvatartás és térkép egy blokkban'],
                ],
                'figures' => [],
                'tools' => ['Figma', 'HTML', 'CSS', 'JavaScript'],
                'video_duration' => null,
                'video_caption' => [
                    'en' => 'A pass down the full page, from the hero to the contact block.',
                    'hu' => 'Végigfutás a teljes oldalon a herótól a kapcsolati blokkig.',
                ],
            ],

            'layzfonts' => [
                'slug' => 'layzfonts',
                'name' => 'Layz',
                'kind' => 'live',
                'url' => 'https://layzfonts.com/',
                'year' => '2026',
                'sector' => ['en' => 'Design tools', 'hu' => 'Tervezői eszközök'],
                'type' => ['en' => 'Web app', 'hu' => 'Webalkalmazás'],
                'tagline' => [
                    'en' => 'Every Google Font, set in your own words.',
                    'hu' => 'Minden Google Font, a saját szavaiddal szedve.',
                ],
                'problem' => [
                    'en' => 'Picking type from a list of names tells you nothing. You only know whether a typeface works when you see your own words in it — and you only know whether two work together when you see them on the same page.',
                    'hu' => 'Névsorból betűt választani semmit nem mond. Csak akkor tudod, működik-e egy betűtípus, ha a saját szavaidat látod benne — és csak akkor tudod, működik-e kettő együtt, ha egy oldalon látod őket.',
                ],
                'approach' => [
                    'en' => 'A searchable library of the whole Google Fonts catalogue, sorted into five families and previewed in whatever text you type. On top of it a shuffle button that keeps dealing title-and-body pairings until one feels right, plus a shortlist you can save to. It sets no cookies and runs no analytics — everything is stored in your own browser.',
                    'hu' => 'A teljes Google Fonts katalógus kereshető könyvtárban, öt családba rendezve, és bármilyen beírt szöveggel előnézve. Efölött egy shuffle gomb, ami addig oszt cím–szöveg párosításokat, amíg egy nem ül, plusz egy menthető rövidlista. Nem tesz ki sütit és nem futtat analitikát — minden a saját böngésződben marad.',
                ],
                'value' => [
                    'en' => 'The whole catalogue becomes browsable by feel instead of by name: 1603 typefaces across five categories, and thirty hand-picked pairings to start from when the blank page is the problem.',
                    'hu' => 'A teljes katalógus érzésre böngészhetővé válik, nem névre: 1603 betűtípus öt kategóriában, és harminc kézzel válogatott párosítás kiindulásnak, amikor épp az üres lap a probléma.',
                ],
                'highlights' => [
                    ['en' => 'Preview any font in your own text', 'hu' => 'Bármelyik betű előnézete saját szöveggel'],
                    ['en' => 'Shuffle for title-and-body pairings', 'hu' => 'Shuffle cím–szöveg párosításokhoz'],
                    ['en' => 'Saveable shortlist, light and dark', 'hu' => 'Menthető rövidlista, világos és sötét mód'],
                    ['en' => 'No cookies, no analytics', 'hu' => 'Nincs süti, nincs analitika'],
                ],
                'figures' => [
                    ['label' => ['en' => 'Typefaces', 'hu' => 'Betűtípus'], 'value' => '1603'],
                    ['label' => ['en' => 'Curated pairs', 'hu' => 'Válogatott páros'], 'value' => '30'],
                ],
                'tools' => ['Figma', 'HTML', 'CSS', 'JavaScript'],
                'video_duration' => null,
                'video_caption' => [
                    'en' => 'Searching the library, then shuffling a pairing.',
                    'hu' => 'Keresés a könyvtárban, majd egy párosítás shuffle-ozása.',
                ],
            ],
            // ── Koncepciok ────────────────────────────────────────────
            // Valodi, megnyithato cimen futo, sajat kezzel epitett oldalak -
            // de a marka mogottuk kitalalt, a fotok pedig keszletbol valok.
            // Ezert NEM 'live': az elo ugyfelmunkak szama a cimlapon
            // ellenorizheto allitas, es ket demo-marka elrontana.
            'pacer' => [
                'slug' => 'pacer',
                'name' => 'PACER',
                'kind' => 'concept',
                'url' => 'https://hussylevente.github.io/pacer_shoes/',
                'year' => '2026',
                'sector' => ['en' => 'Running shoes', 'hu' => 'Futócipő'],
                'type' => ['en' => 'Webshop', 'hu' => 'Webáruház'],
                'tagline' => [
                    'en' => 'A whole shop, down to the last checkout step.',
                    'hu' => 'Teljes bolt, az utolsó fizetési lépésig.',
                ],
                'problem' => [
                    'en' => 'A webshop proves itself in the places nobody puts in a demo: the size that is out of stock, the promo code that does not apply, the third step of checkout. A handsome product grid says nothing about any of them.',
                    'hu' => 'Egy webáruház ott dől el, amit senki nem tesz be a bemutatóba: az elfogyott méretnél, a nem érvényes kuponnál, a fizetés harmadik lépésénél. Egy szép terméklistából mindebből semmi nem látszik.',
                ],
                'approach' => [
                    'en' => 'A complete Hungarian running-shoe shop, built as a concept. Twelve models with colourways and a per-size stock run, so a sold-out size is struck through instead of failing quietly two screens later. Cart, saved products, an account area, promo codes, courier choice and the full checkout are all there and all clickable. Every string, price and image path lives in a single content file, so putting a real business inside is an editing job, not a rebuild.',
                    'hu' => 'Teljes magyar futócipő-bolt, koncepcióként megépítve. Tizenkét modell színváltozatokkal és méretenkénti készlettel — így az elfogyott méret át van húzva, nem két képernyővel később hasal el csendben. Kosár, mentett termékek, fiók, kuponkódok, futárválasztás és a végigvihető fizetés mind ott van, mind kattintható. Minden szöveg, ár és képútvonal egyetlen tartalomfájlban ül, így egy valódi vállalkozás behelyezése szerkesztés, nem újraépítés.',
                ],
                'value' => [
                    'en' => 'The buying path is walkable end to end — twelve models, two languages, and a checkout you can actually finish. That is what a shop has to show before anyone trusts it with a real catalogue.',
                    'hu' => 'A vásárlási út végigjárható — tizenkét modell, két nyelv, és egy ténylegesen befejezhető fizetés. Pontosan ezt kell megmutatnia egy boltnak, mielőtt bárki rábízza a valódi kínálatát.',
                ],
                'highlights' => [
                    ['en' => 'Cart, checkout and order summary', 'hu' => 'Kosár, fizetés és rendelés-összesítő'],
                    ['en' => 'Per-size stock, sold-out sizes struck through', 'hu' => 'Méretenkénti készlet, áthúzott elfogyott méretek'],
                    ['en' => 'Saved products and an account area', 'hu' => 'Mentett termékek és fiókfelület'],
                    ['en' => 'Hungarian and English throughout', 'hu' => 'Végig magyarul és angolul'],
                ],
                'figures' => [
                    ['label' => ['en' => 'Shoe models', 'hu' => 'Cipőmodell'], 'value' => '12'],
                    ['label' => ['en' => 'Languages', 'hu' => 'Nyelv'], 'value' => '2'],
                ],
                'tools' => ['Figma', 'HTML', 'CSS', 'JavaScript'],
                'video_duration' => null,
                'video_caption' => [
                    'en' => 'Walking the shop from the catalogue to the last checkout step.',
                    'hu' => 'A bolt végigjárása a katalógustól az utolsó fizetési lépésig.',
                ],
            ],
            'primo' => [
                'slug' => 'primo',
                'name' => 'Primo',
                'kind' => 'concept',
                'url' => 'https://hussylevente.github.io/primo/',
                'year' => '2026',
                'sector' => ['en' => 'Creative studio', 'hu' => 'Kreatív stúdió'],
                'type' => ['en' => 'Portfolio site', 'hu' => 'Portfólió oldal'],
                'tagline' => [
                    'en' => 'The pictures set the layout.',
                    'hu' => 'A képek szabják meg az elrendezést.',
                ],
                'problem' => [
                    'en' => 'A studio portfolio has one job and a hard constraint: show the work, and do not compete with it. Most solve that with a grid — which flattens every picture to the same size and so says nothing about what the studio itself rates.',
                    'hu' => 'Egy stúdió-portfóliónak egy dolga van, és egy kemény megkötése: mutassa a munkát, és ne versenyezzen vele. A legtöbb ezt ráccsal oldja meg — ami minden képet azonos méretre lapít, és így semmit nem mond arról, mit tart jónak maga a stúdió.',
                ],
                'approach' => [
                    'en' => 'The home page is a scattered wall instead of a grid: pictures sit at different sizes and depths on white, and the wordmark runs underneath them at display size so type and image share one plane. Four screens in all — the wall, the work, the studio and contact — plus a full-screen menu that drops the navigation in at the same scale as the logo. Inertial scrolling throughout, and a cursor that answers whatever it is over.',
                    'hu' => 'A főoldal szórt fal rács helyett: a képek eltérő méretben és mélységben ülnek a fehéren, a szóvédjegy pedig alattuk fut kijelzőméretben, így a tipográfia és a kép egy síkra kerül. Összesen négy képernyő — a fal, a munkák, a stúdió és a kapcsolat —, plusz egy teljes képernyős menü, ami a logóval azonos méretben teszi le a navigációt. Végig tehetetlenségi görgetés, és a tartalomra válaszoló egérmutató.',
                ],
                'value' => [
                    'en' => 'Scale does the editing. What the studio rates gets more room, so the first screen takes a position before a single caption has been read — which is exactly what a portfolio is for.',
                    'hu' => 'A méretezés végzi a szerkesztést. Amit a stúdió többre tart, az több helyet kap, így az első képernyő már az első képaláírás előtt állást foglal — pontosan ezért van egy portfólió.',
                ],
                'highlights' => [
                    ['en' => 'Scattered image wall instead of a grid', 'hu' => 'Szórt képfal rács helyett'],
                    ['en' => 'Wordmark set at display size behind the work', 'hu' => 'Kijelzőméretű szóvédjegy a munkák mögött'],
                    ['en' => 'Full-screen menu at the scale of the logo', 'hu' => 'Teljes képernyős menü a logó méretében'],
                    ['en' => 'Inertial scrolling and a reactive cursor', 'hu' => 'Tehetetlenségi görgetés és reagáló egérmutató'],
                ],
                'figures' => [
                    ['label' => ['en' => 'Screens', 'hu' => 'Képernyő'], 'value' => '4'],
                    ['label' => ['en' => 'Services listed', 'hu' => 'Szolgáltatás'], 'value' => '5'],
                ],
                'tools' => ['Figma', 'HTML', 'CSS', 'JavaScript'],
                'video_duration' => null,
                'video_caption' => [
                    'en' => 'Scrolling the wall, then opening a project.',
                    'hu' => 'Végiggörgetés a falon, majd egy projekt megnyitása.',
                ],
            ],
            'kodama' => [
                'slug' => 'kodama',
                'name' => 'KODAMA',
                'kind' => 'concept',
                'url' => 'https://hussylevente.github.io/kodama/',
                'year' => '2026',
                'sector' => ['en' => 'Photography studio', 'hu' => 'Fotóstúdió'],
                'type' => ['en' => 'Portfolio site', 'hu' => 'Portfólió oldal'],
                'tagline' => [
                    'en' => 'The motion is the argument.',
                    'hu' => 'Maga a mozgás az érv.',
                ],
                'problem' => [
                    'en' => 'A photography studio is judged on presentation before anyone reads a word. The images have to arrive the way they would in print — unhurried, uncrowded — and the site has to stay out of their way without looking like it was never designed at all.',
                    'hu' => 'Egy fotóstúdiót a megjelenése alapján ítélik meg, mielőtt bárki egy szót is elolvasna. A képeknek úgy kell megérkezniük, ahogy nyomtatásban tennének — sietség és zsúfoltság nélkül —, az oldalnak pedig ki kell maradnia az útjukból anélkül, hogy megtervezetlennek látszana.',
                ],
                'approach' => [
                    'en' => 'A dark editorial portfolio built on motion rather than decoration: inertial scrolling, a cursor that reacts to whatever it is over, a film-grain layer drawn on canvas, and headings that arrive line by line. Work, studio, services and archive each get their own chapter, and the two studio locations show their real local time.',
                    'hu' => 'Sötét, editorial hangvételű portfólió, ami a mozgásra épül, nem a díszítésre: tehetetlenségi görgetés, a tartalomra reagáló egérmutató, vászonra rajzolt filmszemcse-réteg, és soronként érkező címsorok. A munkák, a stúdió, a szolgáltatások és az archívum külön fejezetet kap, a két stúdióhelyszín pedig a valós helyi időt mutatja.',
                ],
                'value' => [
                    'en' => 'Everything moves on the compositor, so a page this heavy with imagery still scrolls clean — and the restraint reads as confidence rather than as a studio with nothing to show.',
                    'hu' => 'Minden a kompozitoron mozog, így egy ennyire képekkel teli oldal is tisztán görget — a visszafogottság pedig magabiztosságnak hat, nem üres portfóliónak.',
                ],
                'highlights' => [
                    ['en' => 'Inertial scrolling, headings that arrive line by line', 'hu' => 'Tehetetlenségi görgetés, soronként érkező címsorok'],
                    ['en' => 'Film grain drawn on canvas over the whole page', 'hu' => 'Vászonra rajzolt filmszemcse az egész oldalon'],
                    ['en' => 'Cursor that reacts to what it is over', 'hu' => 'A tartalomra reagáló egérmutató'],
                    ['en' => 'Live local time for both studios', 'hu' => 'Valós helyi idő mindkét stúdióhoz'],
                ],
                'figures' => [],
                'tools' => ['Figma', 'HTML', 'CSS', 'JavaScript'],
                'video_duration' => null,
                'video_caption' => [
                    'en' => 'Scrolling the work index, then opening a project.',
                    'hu' => 'Végiggörgetés a munkákon, majd egy projekt megnyitása.',
                ],
            ],
            'paradise' => [
                'slug' => 'paradise',
                'name' => 'Paradise',
                'logo' => 'assets/imgs/websites/paradise/paradise_logo.png',
                'kind' => 'design',
                // The real address goes here once the site is published. Null renders
                // the placeholder state instead of a link that goes nowhere.
                'url' => null,
                'year' => '2025',
                'sector' => ['en' => 'Travel booking', 'hu' => 'Utazásfoglalás'],
                'type' => ['en' => 'Booking platform', 'hu' => 'Foglalási platform'],
                'tagline' => [
                    'en' => 'Booking a trip should take three taps, not seven.',
                    'hu' => 'Egy utat három koppintással kell lefoglalni, nem héttel.',
                ],
                'problem' => [
                    'en' => 'Paradise sold breathtaking destinations through an interface that looked like a spreadsheet. Seven steps to book, six seconds to load on mobile, and most visitors left before they ever reached the form.',
                    'hu' => 'A Paradise lélegzetelállító úti célokat árult egy táblázatra hasonlító felületen. Hét lépés a foglalásig, hat másodperc betöltés mobilon — a látogatók többsége el sem jutott az űrlapig.',
                ],
                'approach' => [
                    'en' => 'I rebuilt the booking flow around the photography and cut it to three steps. Destination cards load progressively, the cruise showcase unfolds on scroll, and the booking action sits within reach on the very first screen.',
                    'hu' => 'A foglalási folyamatot a fotókra építettem újra, és három lépésre csökkentettem. Az úti cél kártyák fokozatosan töltődnek, a hajóbemutató görgetésre bontakozik ki, a foglalás pedig már az első képernyőn elérhető.',
                ],
                'value' => [
                    'en' => 'A booking flow short enough to finish from a beach chair on 4G, with the photography doing the selling instead of a specification table.',
                    'hu' => 'Olyan rövid foglalási folyamat, amit strandszékből, 4G-n is végig lehet vinni — és ahol a fotók adnak el, nem egy adattáblázat.',
                ],
                'highlights' => [
                    ['en' => 'Three-step booking flow', 'hu' => 'Háromlépéses foglalás'],
                    ['en' => 'Progressive image loading', 'hu' => 'Fokozatos képbetöltés'],
                    ['en' => 'Mobile-first from the start', 'hu' => 'Mobil-first az elejétől'],
                ],
                'duration' => ['en' => '3 weeks', 'hu' => '3 hét'],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'price' => '450 000 Ft',
                'video_duration' => '1:12',
                'video_caption' => [
                    'en' => 'The full booking flow, start to confirmation, on a phone.',
                    'hu' => 'A teljes foglalási folyamat a kezdéstől a visszaigazolásig, telefonon.',
                ],
                'redesign_note' => [
                    'en' => 'The old site buried every destination in a dense list — small thumbnails, cramped type, the booking form three scrolls down. The rebuild puts the photography first and the booking action on screen one.',
                    'hu' => 'A régi oldal minden úti célt egy sűrű listába temetett — apró bélyegképek, zsúfolt tipográfia, a foglalási űrlap három görgetéssel lejjebb. Az új verzió a fotókat teszi előre, a foglalást pedig az első képernyőre.',
                ],
            ],
            'palesso' => [
                'slug' => 'palesso',
                'name' => 'Palesso',
                'logo' => 'assets/imgs/websites/palesso/palesso_logo.png',
                'kind' => 'design',
                'url' => null,
                'year' => '2025',
                'sector' => ['en' => 'Premium fashion', 'hu' => 'Prémium divat'],
                'type' => ['en' => 'Webshop', 'hu' => 'Webáruház'],
                'tagline' => [
                    'en' => 'Premium pieces should not look like clearance stock.',
                    'hu' => 'A prémium daraboknak nem szabad kiárusításnak látszaniuk.',
                ],
                'problem' => [
                    'en' => 'Palesso was running a stock theme. Six-figure inventory sat in the same beige grid as everything else, and the made-to-order service — their actual differentiator — was buried three clicks deep.',
                    'hu' => 'A Palesso sablon témát használt. A hatszámjegyű készlet ugyanabban az unalmas rácsban ült, mint bármi más, az egyedi rendelés — a valódi megkülönböztetőjük — pedig három kattintás mélyen volt eltemetve.',
                ],
                'approach' => [
                    'en' => 'A quiet editorial layout that gets out of the way of the product photography, plus a personalisation engine wired directly into the product page — fabric, monogram and fit chosen without ever leaving the item.',
                    'hu' => 'Visszafogott, szerkesztői elrendezés, ami háttérbe húzódik a termékfotók mögött, és egy személyre szabási motor közvetlenül a termékoldalon — anyag, monogram és szabás a termék elhagyása nélkül.',
                ],
                'value' => [
                    'en' => 'Personalisation becomes the reason to buy here rather than anywhere else, because it sits on the product page instead of three clicks behind it.',
                    'hu' => 'A személyre szabás lesz az ok, amiért itt vásárolnak és nem máshol — mert a termékoldalon ül, nem három kattintással mögötte.',
                ],
                'highlights' => [
                    ['en' => 'In-page personalisation engine', 'hu' => 'Beépített személyre szabás'],
                    ['en' => 'Editorial product layout', 'hu' => 'Szerkesztői termékelrendezés'],
                    ['en' => 'In-store collection flow', 'hu' => 'Üzleti átvételi folyamat'],
                ],
                'duration' => ['en' => '4 weeks', 'hu' => '4 hét'],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'price' => '520 000 Ft',
                'video_duration' => '1:26',
                'video_caption' => [
                    'en' => 'Configuring a made-to-order piece, from fabric to checkout.',
                    'hu' => 'Egy egyedi darab összeállítása az anyagtól a fizetésig.',
                ],
                'redesign_note' => [
                    'en' => 'The stock theme made every item look identical. The rebuild strips the interface back so the photography carries the page, and personalisation finally sits where customers look for it.',
                    'hu' => 'A sablon témában minden termék egyformán nézett ki. Az új verzió visszahúzza a felületet, hogy a fotók vigyék az oldalt, a személyre szabás pedig végre ott van, ahol keresik.',
                ],
            ],
            'kepszakadas' => [
                'slug' => 'kepszakadas',
                'name' => 'Képszakadás',
                'logo' => 'assets/imgs/websites/kepszakadas/kepszakadas_logo.png',
                'kind' => 'design',
                'url' => null,
                'year' => '2025',
                'sector' => ['en' => 'Games & social', 'hu' => 'Játék és közösség'],
                'type' => ['en' => 'Web app', 'hu' => 'Webalkalmazás'],
                'tagline' => [
                    'en' => 'A game played one-handed at a table, finally built for one hand.',
                    'hu' => 'Egy asztalnál, fél kézzel játszott játék — végre fél kézre építve.',
                ],
                'problem' => [
                    'en' => 'A drinking game gets played on a phone, at a table, with one hand and no patience. The original page was a desktop layout squeezed onto a screen: pinch to zoom, five taps to start, six games in total.',
                    'hu' => 'Egy ivós játékot telefonon játszanak, asztalnál, fél kézzel, türelem nélkül. Az eredeti oldal egy asztali elrendezés volt képernyőre préselve: nagyítás ujjal, öt koppintás az indulásig, összesen hat játék.',
                ],
                'approach' => [
                    'en' => 'Rebuilt mobile-first with thumb-sized targets, no zoom and one tap to start. It runs straight from the browser — no app store, no install, nothing between opening the link and playing.',
                    'hu' => 'Mobil-first újraépítés hüvelykujjnyi gombokkal, nagyítás nélkül, egy koppintással induló játékkal. Egyenesen a böngészőből fut — nincs app store, nincs telepítés, semmi a link megnyitása és a játék között.',
                ],
                'value' => [
                    'en' => 'A catalogue of forty-plus games that opens in one tap straight from a browser, with nothing between the link and the first round.',
                    'hu' => 'Negyven fölötti játékkínálat, ami egy koppintással, egyenesen a böngészőből indul — semmi nincs a link és az első kör között.',
                ],
                'highlights' => [
                    ['en' => 'One tap to start playing', 'hu' => 'Egy koppintás az indulásig'],
                    ['en' => '40+ games and minigames', 'hu' => '40+ játék és minijáték'],
                    ['en' => 'Zero install, browser only', 'hu' => 'Nulla telepítés, csak böngésző'],
                ],
                'duration' => ['en' => '2 weeks', 'hu' => '2 hét'],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'price' => '280 000 Ft',
                'video_duration' => '0:48',
                'video_caption' => [
                    'en' => 'Opening the link and starting a round, one-handed.',
                    'hu' => 'A link megnyitása és egy kör indítása, fél kézzel.',
                ],
                'redesign_note' => [
                    'en' => 'The original was a desktop layout squeezed onto a phone — exactly the wrong way round. The rebuild is mobile-first: thumb-sized targets, no pinch-zoom, one tap to play.',
                    'hu' => 'Az eredeti egy asztali elrendezés volt telefonra préselve — pont fordítva. Az új verzió mobil-first: hüvelykujjnyi gombok, nincs nagyítás, egy koppintás a játékig.',
                ],
            ],
            'juiced' => [
                'slug' => 'juiced',
                'name' => 'Juiced',
                'logo' => 'assets/imgs/websites/juiced/juiced_logo.png',
                'logo_invert' => true,
                'kind' => 'design',
                'url' => null,
                'year' => '2026',
                'sector' => ['en' => 'Food & drink', 'hu' => 'Étel és ital'],
                'type' => ['en' => 'Webshop', 'hu' => 'Webáruház'],
                'tagline' => [
                    'en' => 'You cannot sell flavour from a spec sheet.',
                    'hu' => 'Ízt nem lehet terméktáblázatból eladni.',
                ],
                'problem' => [
                    'en' => 'Juiced sells taste, but the old storefront presented every drink as a row in a spec-sheet grid. Twelve flavours, one identical thumbnail each, and a five-megabyte page that most phones gave up on.',
                    'hu' => 'A Juiced ízt árul, de a régi bolt minden italt egy terméktáblázat sorának mutatott. Tizenkét íz, mind ugyanolyan bélyegkép, és egy ötmegabájtos oldal, amit a legtöbb telefon feladott.',
                ],
                'approach' => [
                    'en' => 'Every flavour gets a full-bleed section that shifts the whole page colour as you scroll. A sticks-versus-drinks toggle switches the catalogue instantly, and the whole thing was built mobile-first because that is where it gets shopped.',
                    'hu' => 'Minden íz teljes szélességű szekciót kap, ami görgetés közben az egész oldal színét váltja. Egy szívószál–ital váltó azonnal cseréli a kínálatot, az egész pedig mobil-first épült, mert ott vásárolnak.',
                ],
                'value' => [
                    'en' => 'The page sells the taste before the ingredient list loads, and the flavour-sticks line gets the same billing as the bottles instead of a footnote.',
                    'hu' => 'Az oldal már az összetevőlista betöltése előtt eladja az ízt, a szívószál-termékvonal pedig ugyanakkora teret kap, mint a palackos — nem lábjegyzetet.',
                ],
                'highlights' => [
                    ['en' => 'Colour-shifting flavour sections', 'hu' => 'Színt váltó íz-szekciók'],
                    ['en' => 'Instant sticks/drinks toggle', 'hu' => 'Azonnali szívószál/ital váltó'],
                    ['en' => 'Built mobile-first for a heavy catalogue', 'hu' => 'Mobil-first felépítés nagy kínálathoz'],
                ],
                'duration' => ['en' => '3 weeks', 'hu' => '3 hét'],
                'tools' => ['Figma', 'Laravel', 'JavaScript', 'VS Code'],
                'price' => '390 000 Ft',
                'video_duration' => '1:04',
                'video_caption' => [
                    'en' => 'Scrolling the flavour wall and switching to the sticks catalogue.',
                    'hu' => 'Görgetés az íz-falon, majd váltás a szívószál-kínálatra.',
                ],
                'redesign_note' => [
                    'en' => 'Juiced used to present its drinks as a grid where every flavour looked identical. Now each one gets a full-bleed, colour-shifting section of its own.',
                    'hu' => 'A Juiced korábban egy rácsban mutatta az italait, ahol minden íz egyformának látszott. Most mindegyik saját, teljes szélességű, színt váltó szekciót kap.',
                ],
            ],
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

/**
 * Sablon-playground.
 *
 * A demo-nezegeto megmutatja, hogy ugyanaz a sablon ket kulonbozo szakmaban
 * is mukodik. Ez a lap eggyel tovabb megy: a latogato a SAJAT szoveget es a
 * SAJAT fotoit teszi bele, meg vasarlas elott.
 *
 * Ez nem szerkeszto es nem oldalepito. Nem kuld semmit sehova, nem ir
 * fajlt, es nem ad letoltheto oldalt - a valtoztatasok a latogato
 * bongeszojeben elnek (IndexedDB), es ott is maradnak. Egyetlen kerdesre
 * felel: jol nez ki ez az en vallalkozasommal is?
 *
 * A demok nevsorat SZANDEKOSAN nem ez a kontroller tartja, hanem a
 * TemplateController - ott elnek a sablonok is. Itt csak az van, ami a
 * szerkesztesehez kell: melyik CSS valtozo a markaszin, es mit ne lehessen
 * megfogni. Hogy a ketto ne csusszon el, a PlaygroundTest mindket iranyt
 * ellenorzi.
 */
class PlaygroundController extends Controller
{
    public function __construct(private readonly TemplateController $templates) {}

    public function index()
    {
        return view('playground.index', [
            'demos' => $this->editable(),
        ]);
    }

    public function show(string $demo)
    {
        $editable = $this->editable();

        abort_unless(isset($editable[$demo]), 404);

        $current = $editable[$demo];

        return view('playground.show', [
            'demo' => $current,
            // A tobbi demo: ugyanabbol a sablonbol elobb, aztan a tobbi. Igy
            // az "ugyanez a sablon egy masik szakmaban" valtas kerul
            // kozelebb, mert az a lap allitasa.
            'others' => $this->siblings($editable, $current),
            'config' => $this->config($current),
        ]);
    }

    /**
     * Hany demo szerkesztheto. A sablonlap ebbol irja ki a szamot.
     */
    public function count(): int
    {
        return count($this->editable());
    }

    /**
     * @return string[]
     */
    public function slugs(): array
    {
        return array_keys($this->editable());
    }

    /**
     * A szerkesztheto demok.
     *
     * Metszet: ami demokent letezik ES amihez van szerkeszto-beallitas. Igy
     * egy uj demo nem kerul ide magatol, felkeszuletlenul - de kiesni sem
     * tud csendben, mert a teszt szol, ha a ket lista elter.
     *
     * @return array<string, array<string, mixed>>
     */
    public function editable(): array
    {
        $locale = App::getLocale();
        $editors = $this->editors();

        $out = [];

        foreach ($this->templates->demos() as $slug => $demo) {
            if (! isset($editors[$slug])) {
                continue;
            }

            $editor = $editors[$slug];

            $out[$slug] = $demo + [
                'blurb' => $editor['blurb'][$locale] ?? $editor['blurb']['en'],
                'swatches' => array_map(fn (array $s) => [
                    'key' => $s['key'],
                    'label' => $s['label'][$locale] ?? $s['label']['en'],
                    'default' => $s['default'],
                    'vars' => $s['vars'],
                ], $editor['swatches']),
                'skip' => $editor['skip'] ?? [],
            ];
        }

        return $out;
    }

    /**
     * A szerkeszto beallitasai, amiket a bongeszoben futo szkript kap meg.
     *
     * @return array<string, mixed>
     */
    private function config(array $demo): array
    {
        return [
            'demo' => $demo['slug'],
            'src' => $demo['url'],
            'swatches' => $demo['swatches'],
            // Amihez a szerkeszto hozza se nyul. A lista rovid es szandekos:
            // az ugras-a-tartalomhoz link a billentyuzetes navigacio resze,
            // a lightbox tartalmat pedig a demo sajat szkriptje irja felul -
            // mindketto csak osszezavarna.
            'skip' => array_merge(['.skip-link', '.lightbox', '[data-pg-skip]'], $demo['skip']),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function siblings(array $editable, array $current): array
    {
        $others = array_values(array_filter(
            $editable,
            fn (array $d) => $d['slug'] !== $current['slug']
        ));

        $sameTemplate = fn (array $d) => (int) ($d['template']['slug'] === $current['template']['slug']);

        usort($others, fn (array $a, array $b) => $sameTemplate($b) <=> $sameTemplate($a));

        return $others;
    }

    /**
     * Szerkeszto-beallitas demonkent.
     *
     * A 'vars' a demo sajat CSS valtozoira mutat, es minden demo maskepp
     * nevezi oket - ezert nem lehet egyetlen kozos "--accent". A szam a
     * vilagossag eltolasa szazalekpontban: igy egy valasztott szinbol kijon
     * a demo teljes arnyalatsora, es nem lapul ossze egyetlen sik szinne.
     *
     * @return array<string, array<string, mixed>>
     */
    private function editors(): array
    {
        return [
            'signal-burger' => [
                'blurb' => [
                    'en' => 'A loud, warm one-pager. Swap the burgers for whatever you actually sell.',
                    'hu' => 'Hangos, meleg tónusú egyoldalas. Cseréld le a burgereket arra, amit tényleg árulsz.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#f05a28',
                        'vars' => ['--orange' => 0, '--red' => -14],
                    ],
                ],
                'skip' => [],
            ],

            'signal-attorney' => [
                'blurb' => [
                    'en' => 'The same template, played straight. Quiet, formal, and built to be trusted.',
                    'hu' => 'Ugyanaz a sablon, komolyan véve. Csendes, hivatalos, bizalomra tervezve.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#6f9fbd',
                        // Harom arnyalat egy ramp: a vilagosabb ketto a
                        // valasztott szinbol szamolodik, kulonben a finomabb
                        // reszletek eltunnenek.
                        'vars' => ['--gold-500' => 0, '--gold-400' => 12, '--gold-300' => 26],
                    ],
                ],
                'skip' => [],
            ],

            'aperture-portfolio' => [
                'blurb' => [
                    'en' => 'Images at full size. Drop your own photographs into the gallery and see it hold.',
                    'hu' => 'Képek teljes méretben. Tedd be a saját fotóidat a galériába, és nézd meg, hogy áll.',
                ],
                // Ez a terv szandekosan monokrom: nincs benne markaszin, amit
                // el lehetne rontani. Ami valaszthato, az a papir tonusa -
                // ennyi fer bele ugy, hogy ne dolgozzon a terv ellen.
                'swatches' => [
                    [
                        'key' => 'paper',
                        'label' => ['en' => 'Paper tone', 'hu' => 'Papírszín'],
                        'default' => '#f5f5f0',
                        'vars' => ['--paper' => 0],
                    ],
                ],
                'skip' => ['.cursor', '.cursor-dot'],
            ],

            'aperture-contentcreator' => [
                'blurb' => [
                    'en' => 'The sharper cut of the same template, for people who sell attention.',
                    'hu' => 'Ugyanannak a sablonnak az élesebb vágása, azoknak, akik figyelmet adnak el.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#d8ff3e',
                        'vars' => ['--acid' => 0],
                    ],
                ],
                'skip' => [],
            ],
            'atrium-caffee' => [
                'blurb' => [
                    'en' => 'Editorial and unhurried. Put your own room and your own menu in it.',
                    'hu' => 'Magazinos és nyugodt. Tedd bele a saját tereidet és a saját étlapodat.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#b75738',
                        'vars' => ['--terra' => 0],
                    ],
                ],
                'skip' => [],
            ],

            'atrium-restaurant' => [
                'blurb' => [
                    'en' => 'The same four pages, dressed for evening service instead of morning light.',
                    'hu' => 'Ugyanaz a négy oldal, esti szervizre öltöztetve, nem reggeli fényre.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#bc6545',
                        'vars' => ['--clay' => 0],
                    ],
                ],
                'skip' => [],
            ],

            'poise-hairdresser' => [
                'blurb' => [
                    'en' => 'Price list up front, booking one tap away. Swap in your services and your rates.',
                    'hu' => 'Árlista elöl, foglalás egy koppintásra. Cseréld le a szolgáltatásokat és az árakat.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#bc4c30',
                        'vars' => ['--rust' => 0],
                    ],
                ],
                // A szemcses reteg csak textura, nincs benne olvashato tartalom.
                'skip' => ['.grain'],
            ],

            'poise-mechanic' => [
                'blurb' => [
                    'en' => 'The same booking spine, for a trade where trust is the whole sale.',
                    'hu' => 'Ugyanaz a foglalási gerinc, olyan szakmára, ahol a bizalom maga az üzlet.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#d6f54c',
                        'vars' => ['--lime' => 0],
                    ],
                ],
                'skip' => [],
            ],

            'poise-mechanic2' => [
                'blurb' => [
                    'en' => 'A second take on the same brief: hard grid, loud colour, numbers as decoration.',
                    'hu' => 'Egy második megközelítés ugyanarra: kemény rács, hangos szín, számok díszítésként.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#ff4500',
                        'vars' => ['--orange' => 0],
                    ],
                ],
                'skip' => [],
            ],

            'foundry-furniture' => [
                'blurb' => [
                    'en' => 'A shop that behaves like a gallery. Try it with your own pieces and prices.',
                    'hu' => 'Bolt, ami galériaként viselkedik. Próbáld ki a saját darabjaiddal és áraiddal.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#9b5a46',
                        'vars' => ['--terracotta' => 0],
                    ],
                ],
                // A futo szalag sajat szoveget ismetli, es a szerkesztoben
                // ket peldanyban latszana ugyanaz a mondat.
                'skip' => ['.marquee'],
            ],

            'cargo-shoes' => [
                'blurb' => [
                    'en' => 'Built for drops. Put your own product shots and sizes into the shop.',
                    'hu' => 'Dobásokra készült. Tedd be a saját termékfotóidat és méreteidet a boltba.',
                ],
                'swatches' => [
                    [
                        'key' => 'accent',
                        'label' => ['en' => 'Brand colour', 'hu' => 'Márkaszín'],
                        'default' => '#ccff00',
                        'vars' => ['--acid' => 0],
                    ],
                ],
                'skip' => ['.marquee'],
            ],
        ];
    }
}

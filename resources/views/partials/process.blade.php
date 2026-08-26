@php
    use App\Support\Packages;

    /**
     * A megrendelestol az atadasig.
     *
     * Ket valtozat van, de a gerinc ugyanaz, mert a folyamat is ugyanaz:
     * csomag, domain, fele elore, munka, harom javitasi kor, atadas. Csak a
     * negyedik lepes ter el - az egyedinel en epitek, a sablonnal te kuldod
     * a tartalmat.
     *
     * A ket lista SZANDEKOSAN egy fajlban ul: ha a fizetesi feltetel vagy a
     * javitasi korok szama valtozik, ket helyen kellene atirni, es a ketto
     * elobb-utobb mast allitana ugyanarrol az uzletrol.
     *
     * @var string $flow  'services' | 'templates'
     */
    $packages = Packages::services();

    $domain = [
        __('Domain and server'),
        __('The domain goes in your name, not mine, so it stays yours whatever happens between us — you buy it and send me what I need to point it at the site. If the build needs a server rather than plain hosting, same thing. I will tell you exactly what to get and roughly what it costs before you spend anything.'),
    ];

    $deposit = [
        __('Half up front'),
        __('I invoice fifty percent through Számlázz.hu, so you get a proper Hungarian invoice from the start. I begin the day the transfer lands — not before it, and not a week after it.'),
    ];

    // Az atadas sorrendje szandekosan ez: jovahagyas, szamla, utalas, kod.
    // Forditva a kesz kod mar kint van, amikor meg csak a fele van kifizetve.
    $handover = [
        __('Yours, outright'),
        __('When you sign it off I invoice the second half. Once that clears you get the whole thing as a zip of raw code, along with the logins and a walkthrough of anything you can edit yourself. No platform, no monthly fee, nothing to keep paying me for.'),
    ];

    $steps = $flow === 'templates' ? [
        [
            __('Pick a design and a package'),
            __('Six designs across three packages. Tell me which one you want — the price on the card is the price. If you need something the package does not cover, we talk it through by email, Messenger, phone or in person, and I adjust the quote before anything is signed.'),
        ],
        $domain,
        $deposit,
        [
            __('You send the content'),
            __('Logo, photos, and the text for each page, against a checklist I send you. This is the slow part of the whole thing and it is entirely in your hands — the faster it lands, the faster you are live. If the text is not written yet, say so and I will send a fill-in-the-blanks version.'),
        ],
        [
            __('I swap it in'),
            __('Your words, your images, your colours and typeface. Forms wired to your inbox, opening hours and map set, legal pages filled in. You get a private preview link, and three rounds of changes to get it right.'),
        ],
        $handover,
    ] : [
        [
            __('Pick a package'),
            __('Basic, Standard or Premium. If your project needs something outside the package, we talk it through by email, Messenger, phone or in person, and I adjust the quote before anything is signed.'),
        ],
        $domain,
        $deposit,
        [
            __('I build it'),
            __('Basic takes :basic, Standard :standard, Premium :premium. You get a preview link as soon as there is something worth looking at, so you are never waiting in the dark.', [
                'basic' => mb_strtolower($packages[Packages::BASIC]['days_label']),
                'standard' => mb_strtolower($packages[Packages::STANDARD]['days_label']),
                'premium' => mb_strtolower($packages[Packages::PREMIUM]['days_label']),
            ]),
        ],
        [
            __('Three rounds of changes'),
            __('Every package includes three revisions. Tell me what is wrong and I fix it. Most of what people want changed is text and photos, and that is quick — the rounds are there so you never feel you are using up a favour by asking.'),
        ],
        $handover,
    ];
@endphp

<ol class="steps" data-reveal-group>
    @foreach ($steps as $i => [$title, $body])
        <li class="step">
            <span class="step-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <h3 class="t4">{{ $title }}</h3>
            <p class="t6">{{ $body }}</p>
        </li>
    @endforeach
</ol>

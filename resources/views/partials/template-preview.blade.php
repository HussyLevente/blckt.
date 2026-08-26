@php
    /**
     * Sablon-elonezet bongeszo-keretben.
     *
     * A keret nem disz: e nelkul az elonezet egy absztrakt abranak latszik,
     * nem weboldalnak. A cimsav ugyanezt mondja el szoveggel.
     *
     * @var array $template
     * @var bool|null $eager  a hajtas felett allo kep ne varjon a lusta toltesre
     * @var string|null $alt  ures, ha a nev ugyis ott all a kep mellett
     */
    $eager = $eager ?? false;
    $alt = $alt ?? '';
@endphp

<span class="tpl-frame">
    <span class="tpl-chrome" aria-hidden="true">
        <span class="tpl-dot"></span>
        <span class="tpl-dot"></span>
        <span class="tpl-dot"></span>
        {{-- Szandekosan "azonevu.hu", nem valodi cim: ez a helye annak, ami
             a te domained lesz - nem egy demo, amit meg lehetne nyitni. --}}
        <span class="tpl-url">{{ mb_strtolower($template['name']) }}.hu</span>
    </span>

    <span class="tpl-shot">
        {{-- A meret az igazi kepernyokepekhez kiirodik, az SVG vazlatokhoz
             nem (a getimagesize nem meri meg oket) - ott a keret
             aspect-ratio-ja tartja a helyet. Igy egyik esetben sem ugrik
             meg az elrendezes, amikor a kep megerkezik. --}}
        <img
            src="{{ asset($template['preview']) }}"
            alt="{{ $alt }}"
            {!! \App\Support\Media::sizeAttrs($template['preview']) !!}
            loading="{{ $eager ? 'eager' : 'lazy' }}"
            @if ($eager) fetchpriority="high" @endif
            decoding="async"
        >
    </span>
</span>

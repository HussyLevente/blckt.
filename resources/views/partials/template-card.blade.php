@php
    /**
     * Sablon-kartya. A katalogus es a mentett lap is ezt hasznalja, hogy a
     * ketto ne csusszon el egymastol.
     *
     * @var array $template
     * @var bool|null $eager  a hajtas felett allo elso ket kep elore toltodjon
     */
    $eager = $eager ?? false;
@endphp

<article class="tpl-card" data-reveal data-saved-item data-type="template" data-id="{{ $template['slug'] }}">
    {{-- A kep dekorativ: a nev, az agazat es az ar kozvetlenul alatta all
         szovegkent, ezert a kepernyoolvaso nem hallana tole semmi ujat. --}}
    <a href="{{ $template['url'] }}" class="tpl-card-media" tabindex="-1" aria-hidden="true">
        @include('partials.template-preview', ['template' => $template, 'eager' => $eager])
        <span class="tpl-card-open">
            {{ __('Look inside') }} <span class="arrow" aria-hidden="true">&#8594;</span>
        </span>
    </a>

    {{-- A mentes a kepen kivul all, hogy a kartya kepe kattinthato
         maradjon, a gomb pedig sajat fokuszt kapjon. --}}
    @include('partials.save-button', [
        'type' => 'template',
        'id' => $template['slug'],
        'variant' => 'save-btn-float',
    ])

    <div class="tpl-card-body">
        <p class="t8 tpl-card-meta">
            <span class="tpl-card-tier">{{ $template['tier_name'] }}</span>
            <span aria-hidden="true">/</span>
            <span>{{ $template['sector'] }}</span>
            <span aria-hidden="true">/</span>
            <span>{{ trans_choice(':count page|:count pages', $template['pages']) }}</span>
        </p>

        <div class="tpl-card-head">
            <h3 class="t3">
                <a href="{{ $template['url'] }}" class="link-underline">{{ $template['name'] }}</a>
            </h3>
            <span class="tpl-card-price">{{ $template['price_label'] }}</span>
        </div>

        <p class="t6 tpl-card-tagline">{{ $template['tagline'] }}</p>

        <div class="tpl-card-foot">
            @include('partials.licence', ['template' => $template])

            {{-- Ahol all elo demo, ott a playground a legerosebb link a
                 kartyan: a tobbi allitast az bizonyitja be, mert ott a
                 latogato sajat tartalma kerul a tervbe. --}}
            @if ($template['has_demo'])
                <a href="{{ $template['demos'][0]['playground'] }}" class="link-arrow link-underline t8">
                    {{ __('Try it yourself') }} <span class="arrow" aria-hidden="true">&#8594;</span>
                </a>
            @else
                <a href="{{ $template['url'] }}" class="link-arrow link-underline t8">
                    {{ __('Look inside') }} <span class="arrow" aria-hidden="true">&#8594;</span>
                </a>
            @endif
        </div>
    </div>
</article>

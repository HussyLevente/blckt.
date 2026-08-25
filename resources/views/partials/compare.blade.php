@php
    /**
     * Elotte / utana osszehasonlito - kompakt valtozat.
     *
     * A kulon before/after oldal megszunt, ezert ez a komponens most a projekt-
     * oldalon belul el, es a szamszeru osszehasonlitast is magaval hozza: a
     * csuszka melle kerul a nehany legerosebb elotte-utana adat, hogy a latogato
     * egy kepernyon lassa a vizualis es a merheto kulonbseget is.
     *
     * @var array $redesign  before, after, name, note kulcsokkal
     * @var string|null $eyebrow
     * @var string|null $title
     */
    $eyebrow = $eyebrow ?? __('Before & after');
    $title = $title ?? __('The site they had, and the site they have now.');
@endphp

<div class="compare" data-compare data-compare-start="50">
    <div class="compare-head">
        <span class="compare-eyebrow">{{ $eyebrow }}</span>
        <h2 class="compare-title">{{ $title }}</h2>
    </div>

    <div class="compare-layout">
        <div class="compare-visual">
            <div class="compare-stage" data-compare-stage>
                <figure class="compare-pane compare-pane-before">
                    <img
                        src="{{ asset($redesign['before']) }}"
                        alt="{{ __(':name — the original website before the redesign', ['name' => $redesign['name']]) }}"
                        {!! \App\Support\Media::sizeAttrs($redesign['before']) !!}
                        loading="lazy"
                        decoding="async"
                    >
                    <figcaption class="compare-tag compare-tag-before">{{ __('Before') }}</figcaption>
                </figure>

                <figure class="compare-pane compare-pane-after">
                    <img
                        src="{{ asset($redesign['after']) }}"
                        alt="{{ __(':name — the website I designed and built', ['name' => $redesign['name']]) }}"
                        {!! \App\Support\Media::sizeAttrs($redesign['after']) !!}
                        loading="lazy"
                        decoding="async"
                    >
                    <figcaption class="compare-tag compare-tag-after">{{ __('After') }}</figcaption>
                </figure>

                <button
                    type="button"
                    class="compare-handle"
                    data-compare-handle
                    role="slider"
                    aria-label="{{ __('Drag to compare the old and new site') }}"
                    aria-orientation="horizontal"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-valuenow="50"
                >
                    <span class="compare-handle-grip" aria-hidden="true">&#8592;&#8594;</span>
                </button>
            </div>

            <p class="compare-hint">{{ __('Drag the handle, or use the arrow keys, to reveal the rebuild.') }}</p>
        </div>

        <div class="compare-facts">
            @if (!empty($redesign['note']))
                <p class="compare-note">{{ $redesign['note'] }}</p>
            @endif
        </div>
    </div>
</div>

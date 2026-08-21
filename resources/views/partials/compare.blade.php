@php
    /**
     * Elotte / utana osszehasonlito.
     *
     * @var array $redesign  before, after, name, note, metrics kulcsokkal
     * @var string|null $eyebrow
     * @var string|null $title
     */
    $eyebrow = $eyebrow ?? __('Before & after');
    $title = $title ?? __('The old site, and the one I built.');
@endphp

<div class="compare" data-compare data-compare-start="50">
    <div class="compare-head">
        <div class="compare-heading">
            <span class="compare-eyebrow">{{ $eyebrow }}</span>
            <h2 class="compare-title">{{ $title }}</h2>
        </div>

        <div class="compare-modes" role="group" aria-label="{{ __('Comparison view') }}">
            <button type="button" class="compare-mode is-active" data-compare-mode="slide" aria-pressed="true">{{ __('Slide') }}</button>
            <button type="button" class="compare-mode" data-compare-mode="side" aria-pressed="false">{{ __('Side by side') }}</button>
        </div>
    </div>

    <div class="compare-stage" data-compare-stage>
        <figure class="compare-pane compare-pane-before">
            <img
                src="{{ asset($redesign['before']) }}"
                alt="{{ __(':name — the original website before the redesign', ['name' => $redesign['name']]) }}"
                loading="lazy"
                decoding="async"
            >
            <figcaption class="compare-tag compare-tag-before">{{ __('Before') }}</figcaption>
        </figure>

        <figure class="compare-pane compare-pane-after">
            <img
                src="{{ asset($redesign['after']) }}"
                alt="{{ __(':name — the website I designed and built', ['name' => $redesign['name']]) }}"
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

    @if (!empty($redesign['note']))
        <p class="compare-note">{{ $redesign['note'] }}</p>
    @endif

    @if (!empty($redesign['metrics']))
        <div class="compare-metrics">
            @foreach ($redesign['metrics'] as $metric)
                <div class="compare-metric">
                    <span class="compare-metric-value">{{ $metric['value'] }}</span>
                    <span class="compare-metric-label">{{ $metric['label'] }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

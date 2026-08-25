@php
    /**
     * "Elo oldal megnyitasa" gomb.
     *
     * Harom eset:
     *   - van cim -> valodi, uj lapon nyilo link
     *   - elo munka cim nelkul -> "hamarosan" allapot
     *   - csak terv -> semmi, mert nincs mit megnyitni, es a "hamarosan"
     *     azt sugallna, hogy egyszer lesz
     */
@endphp

@if (! empty($project['url']))
    <a href="{{ $project['url'] }}" target="_blank" rel="noopener" class="visit">
        <span class="visit-dot" aria-hidden="true"></span>
        {{ __('Visit live site') }}
        <span class="arrow-ne" aria-hidden="true">&#8599;</span>
    </a>
@elseif ($project['is_live'])
    <span class="visit visit-pending" role="link" aria-disabled="true" tabindex="0">
        <span class="visit-dot" aria-hidden="true"></span>
        {{ __('Live link coming soon') }}
        <span class="visit-note">{{ __('The public address goes live shortly — the build is finished.') }}</span>
    </span>
@else
    <span class="visit visit-pending" role="note">
        <span class="visit-dot" aria-hidden="true"></span>
        {{ __('Never went live') }}
        <span class="visit-note">{{ __('A design project — the screens were built, but the site was never published.') }}</span>
    </span>
@endif

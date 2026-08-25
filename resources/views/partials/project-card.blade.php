@php
    /**
     * Egy projekt kartyaja.
     *
     * Ketfele allapot van, es ranezesre kulonboznie kell:
     *   - elo munka: van valodi cim, a kartyan "Elo" jelzes es megnyithato link
     *   - terv: sosem kerult ki elo cimre, ezert semmi nem sugallhatja, hogy
     *     kattinthato oldal all mogotte
     *
     * @var array $project
     * @var int $index
     * @var bool|null $wide  teljes szelessegu, kiemelt valtozat
     */
    $wide = $wide ?? false;
    $live = $project['is_live'];
    $url = route('websites.show', $project['slug']);
    $figures = $project['figures'] ?? [];

    // Az elso kartya kepe rendszerint a lap legnagyobb elemi kepe, es rogton
    // a nezetben van. Ha ezt is halasztjuk, a bongeszo csak az elrendezes utan
    // kezd hozza - ezert ez az egy elore kap prioritast, a tobbi marad lusta.
    $eager = ($index ?? 1) === 0;
@endphp

<article class="work-card {{ $wide ? 'work-card-wide' : '' }}" data-reveal>
    <a href="{{ $url }}" class="work-card-media" tabindex="-1" aria-hidden="true">
        <img
            src="{{ asset($project['card']) }}"
            alt=""
            {!! \App\Support\Media::sizeAttrs($project['card']) !!}
            loading="{{ $eager ? 'eager' : 'lazy' }}"
            @if ($eager) fetchpriority="high" @endif
            decoding="async"
        >

        <span class="status {{ $live ? 'status-live' : 'status-pending' }} work-card-status">
            <span class="status-dot" aria-hidden="true"></span>
            {{ $live ? __('Live') : __('Design only') }}
        </span>

        <span class="work-card-open">
            {{ __('Case study') }} <span class="arrow" aria-hidden="true">&#8594;</span>
        </span>
    </a>

    <div class="work-card-body">
        <p class="t8 work-card-meta">
            <span>{{ $project['year'] }}</span>
            <span aria-hidden="true">/</span>
            <span>{{ $project['sector'] }}</span>
            <span aria-hidden="true">/</span>
            <span>{{ $project['type'] }}</span>
        </p>

        <h3 class="t3 work-card-name">
            <a href="{{ $url }}" class="link-underline">{{ $project['name'] }}</a>
        </h3>

        <p class="t5 work-card-tagline">{{ $project['tagline'] }}</p>

        {{-- Szamok csak ott, ahol magan az elo oldalon ellenorizhetok.
             Ahol nincs ilyen, a kepessegek listaja all a helyukon. --}}
        @if ($figures)
            <dl class="figures-inline">
                @foreach ($figures as $figure)
                    <div>
                        <dd class="figures-inline-value">{{ $figure['value'] }}</dd>
                        <dt class="figures-inline-label">{{ $figure['label'] }}</dt>
                    </div>
                @endforeach
            </dl>
        @elseif (! empty($project['highlights']))
            <ul class="card-highlights">
                @foreach (array_slice($project['highlights'], 0, 3) as $highlight)
                    <li>{{ $highlight }}</li>
                @endforeach
            </ul>
        @endif

        <div class="work-card-actions">
            <a href="{{ $url }}" class="btn">
                {{ __('Read the case study') }} <span class="arrow" aria-hidden="true">&#8594;</span>
            </a>
            @include('partials.visit-link', ['project' => $project])
        </div>
    </div>
</article>

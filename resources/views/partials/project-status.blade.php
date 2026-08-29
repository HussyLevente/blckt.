@php
    /**
     * Egy munka allapotjelzoje.
     *
     * Harom eset van, es ranezesre kulonboznie kell mindharomnak:
     *   - elo ugyfelmunka -> tomor pont, "Elo"
     *   - koncepcio       -> rombusz, "Koncepcio": valodi, megnyithato
     *                        cimen fut, de kitalalt marka all mogotte,
     *                        ezert nem szamit elo ugyfelmunkanak
     *   - csak terv       -> ures gyuru, szaggatott keret, "Csak terv"
     *
     * A harom agat SZANDEKOSAN egy helyen tartjuk: korabban a kartya es a
     * projektoldal kulon dontott rola, es ket allapotnal ez meg elment -
     * harommal mar biztosan elcsuszott volna egymastol.
     *
     * @var array $project
     * @var string|null $extra  tovabbi osztaly a kulso elrendezeshez
     */
    if ($project['is_live']) {
        $statusClass = 'status-live';
        $statusLabel = __('Live');
    } elseif ($project['is_concept'] ?? false) {
        $statusClass = 'status-concept';
        $statusLabel = __('Concept build');
    } else {
        $statusClass = 'status-pending';
        $statusLabel = __('Design only');
    }
@endphp

<span class="status {{ $statusClass }} {{ $extra ?? '' }}">
    <span class="status-dot" aria-hidden="true"></span>
    {{ $statusLabel }}
</span>

@php
    /**
     * Mentes gomb.
     *
     * A mentes a latogato bongeszojeben el (localStorage), nincs mogotte
     * fiok - ezert a gomb kiindulo allapota MINDIG "nem mentett", es a
     * szkript allitja at betoltes utan. Forditva villogna: a szerver nem
     * tudhatja, mit mentett el valaki.
     *
     * A 'type' azert van kint, hogy kesobb mas is menthetove valjon
     * (peldaul a munkak) anelkul, hogy a tarolot at kellene irni.
     *
     * @var string $type
     * @var string $id
     * @var string|null $variant  'save-btn-solid' a kartyan, ures a lapon
     */
@endphp

<button
    type="button"
    class="save-btn {{ $variant ?? '' }}"
    data-save
    data-save-type="{{ $type }}"
    data-save-id="{{ $id }}"
    data-label-save="{{ __('Save') }}"
    data-label-saved="{{ __('Saved') }}"
    aria-pressed="false"
>
    <svg class="save-icon" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true">
        <path d="M6 3h12a1 1 0 0 1 1 1v16l-7-4-7 4V4a1 1 0 0 1 1-1z"></path>
    </svg>
    <span class="save-btn-label">{{ __('Save') }}</span>
</button>

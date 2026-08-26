@php
    /**
     * Elo demo-nezegeto.
     *
     * Ket dolgot mutat meg, amit egy kepernyokep nem tud:
     *   1. ugyanaz a sablon ket teljesen kulonbozo szakmaval - ez maga a
     *      termek allitasa ("beleteszem a vallalkozasodat"), es mondat
     *      helyett bizonyitekkal;
     *   2. hogy tenyleg reszponziv - a szelesseg-valtok ugyanazt a lapot
     *      szukitik, amit amugy is arulunk minden csomagban.
     *
     * A fulek valodi linkek: szkript nelkul is megnyilnak, csak uj lapon.
     * A JS csak elkapja oket es a kereten belul valt.
     *
     * @var array $template
     */
    $demos = $template['demos'];
    $first = $demos[0];
@endphp

<div class="demo" data-demo>
    <div class="demo-bar">
        {{-- A ket vallalkozas kozotti valto. --}}
        <div class="demo-tabs" role="tablist" aria-label="{{ __('Demo businesses') }}">
            @foreach ($demos as $i => $demo)
                <a
                    href="{{ $demo['url'] }}"
                    class="demo-tab {{ $i === 0 ? 'is-active' : '' }}"
                    role="tab"
                    target="_blank"
                    rel="noopener"
                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                    data-demo-tab
                    data-demo-url="{{ $demo['url'] }}"
                    data-demo-name="{{ $demo['name'] }}"
                >
                    <span class="demo-tab-name">{{ $demo['name'] }}</span>
                    <span class="demo-tab-sector">{{ $demo['sector'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Szelesseg-valto. Csak szkripttel van ertelme, ezert a JS teszi be. --}}
        <div class="demo-widths" data-demo-widths hidden>
            <button type="button" class="demo-width is-active" data-demo-width="0" aria-pressed="true">{{ __('Desktop') }}</button>
            <button type="button" class="demo-width" data-demo-width="820" aria-pressed="false">{{ __('Tablet') }}</button>
            <button type="button" class="demo-width" data-demo-width="390" aria-pressed="false">{{ __('Phone') }}</button>
        </div>
    </div>

    <div class="demo-shell" data-demo-shell>
        <span class="tpl-frame demo-frame">
            <span class="tpl-chrome" aria-hidden="true">
                <span class="tpl-dot"></span>
                <span class="tpl-dot"></span>
                <span class="tpl-dot"></span>
                <span class="tpl-url" data-demo-address>{{ $first['url'] }}</span>
            </span>

            <span class="demo-stage">
                <iframe
                    src="{{ $first['url'] }}"
                    title="{{ __(':name — live demo of the :template template', ['name' => $first['name'], 'template' => $template['name']]) }}"
                    loading="lazy"
                    data-demo-frame
                ></iframe>
            </span>
        </span>
    </div>

    <div class="demo-foot">
        <p class="t6 demo-note">{{ __('Two businesses, one template. Nothing was redesigned between them — only the words, the photos and the colours changed. That is exactly what happens when you buy it.') }}</p>

        <a href="{{ $first['url'] }}" class="link-arrow link-underline t8" target="_blank" rel="noopener" data-demo-open>
            {{ __('Open full size') }} <span class="arrow-ne" aria-hidden="true">&#8599;</span>
        </a>
    </div>
</div>

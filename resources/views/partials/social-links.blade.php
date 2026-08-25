@php
    /**
     * Csak a tenylegesen kitoltott profilok jelennek meg. Amig egy URL ures a
     * config/social.php-ban, addig a link egyaltalan nem kerul ki - igy sem
     * torott, sem talalgatott cim nem jut el a latogatohoz.
     *
     * $variant: 'row'  - ikon + felirat egymas alatt (lablec)
     *           'inline' - csak ikonok egy sorban (kapcsolat oldal)
     */
    $variant = $variant ?? 'row';
    $socials = collect(config('social.links', []))
        ->filter(fn ($l) => filled($l['url'] ?? null));
@endphp

@if ($socials->isNotEmpty())
    <ul class="social social-{{ $variant }}">
        @foreach ($socials as $key => $link)
            <li>
                {{-- rel="me" a profil-azonositashoz, noopener a biztonsagos uj lapert --}}
                <a href="{{ $link['url'] }}" target="_blank" rel="noopener me" class="social-link">
                    <svg class="social-icon" aria-hidden="true" focusable="false"><use href="#i-{{ $key }}"></use></svg>
                    <span class="social-label">{{ $link['label'] }}</span>
                    <span class="social-arrow" aria-hidden="true">&#8599;</span>
                </a>
            </li>
        @endforeach
    </ul>
@endif

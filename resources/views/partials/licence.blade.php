@php
    /**
     * Licenc-szamlalo.
     *
     * Ez a lap egyetlen valodi valasza arra, hogy "de akkor mas is
     * megveheti". Nem tagadjuk: megmondjuk, hanyan, es hol all a szamlalo.
     * Monokrom rendszerben a forma viszi a jelentest, ezert tomor negyzet =
     * elkelt, ures keret = szabad; a szoveg csak megerositi.
     *
     * @var array $template
     */
    $cap = \App\Http\Controllers\TemplateController::LICENCE_CAP;
@endphp

<span class="licence {{ $template['sold_out'] ? 'licence-sold-out' : '' }}">
    <span class="licence-slots" aria-hidden="true">
        @for ($i = 0; $i < $cap; $i++)
            <span class="licence-slot {{ $i < $template['taken'] ? 'is-taken' : '' }}"></span>
        @endfor
    </span>

    @if ($template['sold_out'])
        {{ __('All :cap licences sold', ['cap' => $cap]) }}
    @else
        {{ __(':left of :cap licences left', ['left' => $template['left'], 'cap' => $cap]) }}
    @endif
</span>

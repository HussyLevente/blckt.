@php
    use App\Support\Packages;

    /**
     * Kiegeszitok - ugyanaz a lista a szolgaltatasok es a sablonok lapjan.
     *
     * Ha egy kiegeszito mar benne van valamelyik szintben, azt kiirjuk. Ez
     * nem szereny, hanem gyakorlatias: aki Premiumot vesz, ne fizessen ki
     * masodszor valamit, ami mar az ové - ha egyszer kiderul, hogy megis,
     * az tobbe kerul egy elmaradt tetelnel.
     *
     * @var string|null $context  'services' | 'templates' - csak a bevezeto
     *                            mondat ter el, az arak nem
     */
    $addOns = Packages::addOns();
    $context = $context ?? 'services';

    $tierNames = [];
    foreach (Packages::services() as $key => $package) {
        $tierNames[$key] = $package['name'];
    }
@endphp

<section class="section shell" aria-labelledby="addons-title">
    <header class="section-head">
        <div>
            <span class="t8 ink-faint">{{ __('Add-ons') }}</span>
            <h2 class="t2 section-head-title" id="addons-title">{{ __('Anything else, priced in advance.') }}</h2>
        </div>
        <p class="t6 section-head-note">
            @if ($context === 'templates')
                {{ __('These work on a template exactly as they do on a custom build — same work, same price. Ask for them with the order and they are in the quote from the start.') }}
            @else
                {{ __('The things people most often want on top of a package. Fixed prices, so an extra never arrives as a surprise line on the invoice.') }}
            @endif
        </p>
    </header>

    <ul class="addons" data-reveal-group="tight">
        @foreach ($addOns as $addOn)
            <li class="addon">
                <div class="addon-head">
                    <h3 class="t4 addon-name">{{ $addOn['name'] }}</h3>
                    <span class="addon-price">+ {{ $addOn['price_label'] }}</span>
                </div>

                <p class="t6 addon-summary">{{ $addOn['summary'] }}</p>

                @if ($addOn['included_in'])
                    <p class="addon-included">
                        {{ __('Already in :tiers — no need to add it there', ['tiers' => implode(' & ', array_map(fn ($k) => $tierNames[$k], $addOn['included_in']))]) }}
                    </p>
                @endif
            </li>
        @endforeach
    </ul>

    <p class="t8 ink-faint" style="margin-top: var(--space-10); max-width: 76ch" data-reveal>{{ __('Prices exclude VAT and are per item — two extra pages is twice the extra-page price. If you want something that is not on this list, ask: most things get a fixed number back the same day.') }}</p>
</section>

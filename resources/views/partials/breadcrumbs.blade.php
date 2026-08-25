@php
    /**
     * Morzsamenu: lathato navigacio es BreadcrumbList strukturalt adat egyben.
     *
     * A ketto mindig egyutt jar, mert a kereso azt varja, hogy a jelolt
     * utvonal a lapon is lathato legyen.
     *
     * @var array $trail  [['label' => '...', 'url' => '...'|null], ...]
     *                    Az utolso elem az aktualis oldal, url nelkul.
     */
    $trail = array_values($trail);
    $home = ['label' => __('Home'), 'url' => url('/')];
    $items = array_merge([$home], $trail);
@endphp

<nav class="crumbs" aria-label="{{ __('Breadcrumb') }}">
    <ol>
        @foreach ($items as $i => $item)
            <li>
                @if (! empty($item['url']) && $i < count($items) - 1)
                    <a href="{{ $item['url'] }}" class="link-underline">{{ $item['label'] }}</a>
                    <span class="crumbs-sep" aria-hidden="true">/</span>
                @else
                    <span aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_map(fn ($i, $item) => array_filter([
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $item['label'],
            'item' => $item['url'] ?? null,
        ]), array_keys($items), $items),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

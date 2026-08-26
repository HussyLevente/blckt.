@php
    use App\Http\Middleware\SetLocale;

    /**
     * Kereso- es valaszmotor-optimalizalas egy helyen.
     *
     * A cim es a leiras a @section('title') / @section('meta_description')
     * blokkokbol jon. A @section inline formaja mar HTML-escapeli az erteket,
     * ezert a kimenet nyers echo - kulonben a "&" ketszer kodolodna.
     */
    $locale = app()->getLocale();
    $isHu = $locale === 'hu';

    $title = trim($__env->yieldContent('title')) ?: e(__('blckt. — Custom Websites & Clothing, Budapest'));
    $description = trim($__env->yieldContent('meta_description')) ?: e(__('I design and build websites and premium streetwear from Budapest. Custom builds and ready-made templates, real code, one person start to finish.'));
    $image = trim($__env->yieldContent('meta_image')) ?: 'assets/imgs/brand/blckt_mainpage_hero_image.webp';
    $robots = trim($__env->yieldContent('robots')) ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

    // A nyelv-valtozatok cime: az alapertelmezett nyelv a tiszta URL-en el,
    // a masodik nyelv ?lang= parameterrel - igy mindketto bejarhato.
    $path = request()->getPathInfo();
    $base = url($path);
    $urlFor = fn (string $lang) => $lang === SetLocale::DEFAULT_LOCALE ? $base : $base.'?lang='.$lang;
    $canonical = $urlFor($locale);

    // A sameAs csak olyan cimeket sorolhat fel, amik magat a studiot
    // azonositjak (lasd config/social.php: 'schema'). Egy uzenetkuldo
    // melylink nem ilyen. Amig egy sincs, a kulcs teljesen kimarad.
    $sameAs = array_values(array_filter(array_map(
        fn ($link) => ($link['schema'] ?? false) ? ($link['url'] ?? '') : '',
        config('social.links', [])
    )));
@endphp

<title>{!! $title !!}</title>
<meta name="description" content="{!! $description !!}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- Nyelvi valtozatok --}}
@foreach (SetLocale::SUPPORTED_LOCALES as $alt)
    <link rel="alternate" hreflang="{{ $alt }}" href="{{ $urlFor($alt) }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ $urlFor(SetLocale::DEFAULT_LOCALE) }}">

{{-- Open Graph --}}
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="blckt.">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{!! $title !!}">
<meta property="og:description" content="{!! $description !!}">
<meta property="og:image" content="{{ asset($image) }}">
<meta property="og:image:alt" content="{!! $title !!}">
<meta property="og:locale" content="{{ $isHu ? 'hu_HU' : 'en_US' }}">
<meta property="og:locale:alternate" content="{{ $isHu ? 'en_US' : 'hu_HU' }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{!! $title !!}">
<meta name="twitter:description" content="{!! $description !!}">
<meta name="twitter:image" content="{{ asset($image) }}">

<meta name="author" content="Levente Hussy">
<meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">
<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">

{{--
    Alap strukturalt adat, minden oldalon.

    Egyetlen @graph-ban ul a studio, a weboldal es a szemely, egymasra
    hivatkozo @id-kkal. A valaszmotorok igy egy lekerdezessel osszerakjak,
    hogy ki all a tartalom mogott, hol dolgozik es mit csinal.
    Cim nincs benne, mert nincs nyilvanos uzleti cim - helyette areaServed.
--}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'ProfessionalService',
            '@id' => url('/').'#studio',
            'name' => 'blckt.',
            'alternateName' => 'blckt. studio',
            'url' => url('/'),
            'image' => asset('assets/imgs/brand/blckt_mainpage_hero_image.webp'),
            'logo' => [
                '@type' => 'ImageObject',
                '@id' => url('/').'#logo',
                'url' => asset('assets/imgs/brand/blckt_logo.png'),
                'caption' => 'blckt.',
            ],
            'description' => __('One-person design studio in Budapest building custom websites, ready-made website templates and premium streetwear. Designed in Figma, written by hand — no page builders.'),
            'email' => 'hello@blckt.hu',
            'telephone' => '+36302552432',
            'founder' => ['@id' => url('/').'#levente'],
            'foundingDate' => '2026',
            'priceRange' => '50000-350000 HUF',
            'currenciesAccepted' => 'HUF, EUR',
            'areaServed' => [
                ['@type' => 'City', 'name' => 'Budapest'],
                ['@type' => 'Country', 'name' => 'Hungary'],
            ],
            'serviceType' => [
                __('Web design'),
                __('Web development'),
                __('Website templates'),
                __('Website redesign'),
                __('E-commerce development'),
                __('Clothing design'),
            ],
            'knowsLanguage' => ['en', 'hu'],
            ...($sameAs ? ['sameAs' => $sameAs] : []),
        ],
        [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => 'blckt.',
            'description' => __('Custom websites and premium streetwear from Budapest.'),
            'publisher' => ['@id' => url('/').'#studio'],
            'inLanguage' => ['en', 'hu'],
        ],
        [
            '@type' => 'Person',
            '@id' => url('/').'#levente',
            'name' => 'Levente Hussy',
            'jobTitle' => __('Designer and developer'),
            'email' => 'hello@blckt.hu',
            'worksFor' => ['@id' => url('/').'#studio'],
            'knowsAbout' => ['Web design', 'Web development', 'Figma', 'Laravel', 'JavaScript', 'Apparel design'],
            'alumniOf' => [
                '@type' => 'CollegeOrUniversity',
                'name' => 'Budapest Business School (BGE)',
            ],
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

@stack('schema')

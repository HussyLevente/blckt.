@extends('layout')

@section('title', 'Általános Szerződési Feltételek | blckt.')
@section('meta_description', 'A blckt.hu weboldalon keresztül kezdeményezett megkeresésekre vonatkozó általános szerződési feltételek.')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="section shell" style="padding-top: calc(72px + var(--space-16))">
        <h1 class="t2 optical-left">Általános Szerződési Feltételek</h1>
        <p class="t8 ink-faint">Utolsó frissítés: 2026. augusztus</p>

        @if (app()->getLocale() !== 'hu')
            <p class="t8 ink-faint">{{ __('This page is only available in Hungarian.') }}</p>
        @endif

        <div class="prose">
            <h2>1. Hatály</h2>
            <p>Jelen Általános Szerződési Feltételek (ÁSZF) a blckt.hu weboldalon keresztül kezdeményezett megkeresésekre és a Hussy Levente (a továbbiakban: <strong>Szolgáltató</strong>) által nyújtott weboldal-fejlesztési szolgáltatásokra vonatkoznak.</p>

            <h2>2. A szolgáltatás jellege</h2>
            <p>A blckt.hu weboldalon jelenleg a kapcsolatfelvételi űrlapon keresztül lehet megkeresést küldeni egyedi weboldal-fejlesztési projektekkel kapcsolatban. A megkeresés elküldése <strong>önmagában nem hoz létre szerződéses jogviszonyt</strong> — a végleges megállapodás (árazás, határidő, terjedelem) minden esetben közvetlen egyeztetés (e-mail, telefon vagy videóhívás) útján, írásban jön létre.</p>

            <h2>3. Ruházati termékek</h2>
            <p>A blckt. clothing kollekció termékei jelenleg bemutató jelleggel szerepelnek a weboldalon, közvetlen online vásárlásra egyelőre nincs lehetőség. Amint elérhetővé válik a vásárlási funkció, jelen ÁSZF ki lesz egészítve a távollévők között kötött szerződésekre vonatkozó jogszabályi előírásokkal (elállási jog, szállítási és fizetési feltételek, jótállás).</p>

            <h2>4. Kapcsolatfelvétel és válaszidő</h2>
            <p>A Szolgáltató törekszik arra, hogy minden megkeresésre 24 órán belül válaszoljon. Ez egy célkitűzés, nem jogi kötelezettségvállalás.</p>

            <h2>5. Fizetés</h2>
            <p>A weboldalon jelenleg nem történik online fizetés vagy automatikus terhelés. A fizetési feltételekről minden esetben a projekt egyeztetése során, külön állapodunk meg.</p>

            <h2>6. Szellemi tulajdon</h2>
            <p>A weboldal tartalma (dizájn, szöveg, grafika, kód) a Szolgáltató szellemi tulajdonát képezi, ezek másolása vagy felhasználása előzetes írásos engedély nélkül tilos.</p>

            <h2>7. Kapcsolat</h2>
            <p>Kérdés esetén: <a href="mailto:hello@blckt.hu">hello@blckt.hu</a></p>
        </div>
    </section>
@endsection

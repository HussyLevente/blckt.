@extends('layout')

@section('title', 'Impresszum | blckt.')
@section('meta_description', 'A blckt.hu weboldal üzemeltetőjének hivatalos adatai: név, székhely, adószám, nyilvántartási szám, valamint e-mail és telefonos elérhetőség.')

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@section('content')
    <section class="section shell" style="padding-top: calc(72px + var(--space-16))">
        <h1 class="t2 optical-left">Impresszum</h1>
        <p class="t8 ink-faint">Utolsó frissítés: 2026. augusztus</p>

        @if (app()->getLocale() !== 'hu')
            <p class="t8 ink-faint">{{ __('This page is only available in Hungarian.') }}</p>
        @endif

        <div class="prose">
            <h2>Szolgáltató adatai</h2>
            <div class="legal-card">
                <p><strong>Név:</strong> Hussy Levente</p>
                <p><strong>Tevékenység:</strong> egyéni weboldal-fejlesztés és -tervezés (blckt. websites), ruházati grafikai tervezés (blckt. clothing)</p>
                <p><strong>Székhely / cégjegyzék adatok:</strong> egyéni vállalkozói regisztráció folyamatban — ez a szakasz frissül, amint rendelkezésre áll</p>
                <p><strong>Adószám:</strong> egyéni vállalkozói regisztráció folyamatban</p>
                <p><strong>Nyilvántartási szám:</strong> egyéni vállalkozói regisztráció folyamatban</p>
                <p><strong>E-mail:</strong> <a href="mailto:hello@blckt.hu">hello@blckt.hu</a></p>
                <p><strong>Telefon:</strong> <a href="tel:+36302552432">+36 30 255 2432</a></p>
            </div>

            <h2>Tárhelyszolgáltató</h2>
            <div class="legal-card">
                <p><strong>Rackhost Zrt.</strong></p>
                <p>6722 Szeged, Tisza Lajos körút 41.</p>
                <p><a href="https://www.rackhost.hu/contact" target="_blank" rel="noopener">rackhost.hu/contact</a></p>
            </div>

            <h2>Szerzői jogok</h2>
            <p>A weboldalon található tartalmak (szöveg, grafika, fotó, dizájn) Hussy Levente / blckt. tulajdonát képezik. Ezek másolása, felhasználása vagy terjesztése előzetes írásos engedély nélkül tilos.</p>
        </div>
    </section>
@endsection

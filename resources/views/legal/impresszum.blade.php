@extends('layout')

@section('title', 'Impresszum | blckt.')
@section('meta_description', 'A blckt.hu weboldal üzemeltetőjének adatai és elérhetőségei.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/legal.css') }}">
@endpush

@section('content')
    <section class="legal-section reveal">
        <h1 class="legal-title">Impresszum</h1>
        <p class="legal-updated">Utolsó frissítés: 2026. augusztus</p>

        @if (app()->getLocale() !== 'hu')
            <p class="legal-locale-note">{{ __('This page is only available in Hungarian.') }}</p>
        @endif

        <div class="legal-content">
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

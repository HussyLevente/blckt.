<?php

/**
 * Kozossegi profilok.
 *
 * Ami ures, az egyszeruen nem rendelodik ki - sem a lablecben, sem a
 * kapcsolat oldalon, sem a sameAs strukturalt adatban. Igy nem lehet
 * torott vagy talalgatott profil-cim az oldalon.
 *
 * A cimek TISZTAN, kovetokodok nelkul szerepelnek. Az Instagram-linket
 * megosztaskor/QR-kodbol "?igsi=...&utm_source=qr" toldalekkal kapjuk meg;
 * ez a megosztas forrasat jeloli, nem a profil resze. A sameAs mezoben a
 * kanonikus profil-cimnek kell allnia, kulonben a kereso nem feltetlenul
 * ugyanazzal a profillal azonositja.
 *
 * Felulirhato a .env fajlbol is:
 *   SOCIAL_INSTAGRAM= / SOCIAL_MESSENGER= / SOCIAL_FIVERR=
 */
return [

    /**
     * A "schema" kulcs azt jeloli, hogy az adott cim bekerul-e a strukturalt
     * adat sameAs mezojebe.
     *
     * A sameAs arra valo, hogy a kereso AZONOSITSA a studiot: olyan
     * profiloldalak valok ide, amik magat a markat mutatjuk be. Az Instagram
     * es a Fiverr ilyen. A Messenger-link viszont nem profiloldal, hanem egy
     * uzenetkuldo melylink egy szemelyes fiokra - kapcsolatfelveteli csatorna.
     * Ha bekerulne a sameAs-ba, a kereso a szemelyes fiokot is a marka
     * azonossagahoz kotne. Ezert lathato linkkent szerepel, a semaban nem.
     */
    'links' => [
        'instagram' => [
            'label' => 'Instagram',
            'url' => env('SOCIAL_INSTAGRAM', 'https://www.instagram.com/blckt.brand'),
            'schema' => true,
        ],
        'messenger' => [
            'label' => 'Messenger',
            'url' => env('SOCIAL_MESSENGER', 'https://m.me/hussy.levente.77'),
            'schema' => false,
        ],
        'fiverr' => [
            'label' => 'Fiverr',
            'url' => env('SOCIAL_FIVERR', 'https://www.fiverr.com/blckt_websites'),
            'schema' => true,
        ],
    ],

];

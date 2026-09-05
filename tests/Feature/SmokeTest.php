<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Minden nyilvanos cim megnyilik, mindket nyelven.
 *
 * A kozos fajlok (layout, seo-head, chrome.css) minden lapot erintenek,
 * ezert egy ott elrontott apro dolog egyszerre visz el mindent. Ez a
 * teszt pont ezt fogja meg, mielott elmenne.
 */
class SmokeTest extends TestCase
{
    public function test_every_public_page_renders_in_both_languages(): void
    {
        $paths = [
            '/', '/websites', '/websites/paradise', '/templates', '/templates/signal',
            '/services', '/about', '/contact',
            '/impresszum', '/adatvedelem', '/aszf', '/sitemap.xml',
        ];

        foreach ($paths as $path) {
            foreach (['en', 'hu'] as $locale) {
                $this->get($path.'?lang='.$locale)
                    ->assertOk("{$path} ({$locale}) did not return 200");
            }
        }
    }
}

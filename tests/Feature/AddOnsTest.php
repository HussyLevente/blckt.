<?php

namespace Tests\Feature;

use App\Support\Packages;
use Tests\TestCase;

class AddOnsTest extends TestCase
{
    /**
     * A kiegeszitok ugyanabbol a tablabol jonnek, mint a csomagok, es
     * mindket arlapon meg kell jelenniuk - kulonben az egyiken tobbet
     * lehet kerni, mint a masikon, ugyanazert a penzert.
     */
    public function test_both_pricing_pages_list_every_add_on(): void
    {
        foreach (['en', 'hu'] as $locale) {
            foreach (['/services', '/templates'] as $path) {
                $response = $this->get($path.'?lang='.$locale)->assertOk();

                foreach (Packages::addOns() as $addOn) {
                    // Escapeltetve keresunk: a "Login & registration" a
                    // kimenetben "&amp;"-tel all, nyersen sosem talalnank meg.
                    $response->assertSee($addOn['name']);
                    $response->assertSee($addOn['price_label']);
                }
            }
        }
    }

    /**
     * Ha egy kiegeszito mar benne van egy szintben, azt a szint tenyleg
     * letezo kulcsara kell hivatkoznia - kulonben a lap egy ures nevet
     * irna ki a "mar benne van" cimkere.
     */
    public function test_included_in_only_names_real_tiers(): void
    {
        $tiers = array_keys(Packages::services());

        foreach (Packages::addOns() as $addOn) {
            foreach ($addOn['included_in'] as $tier) {
                $this->assertContains($tier, $tiers, "'{$addOn['name']}' says it is included in an unknown package '{$tier}'.");
            }
        }
    }

    public function test_add_on_prices_are_quotable_in_the_schema(): void
    {
        $html = $this->get('/services')->assertOk()->getContent();

        foreach (Packages::addOns() as $addOn) {
            $this->assertStringContainsString(
                '"price":"'.$addOn['price'].'"',
                $html,
                "'{$addOn['name']}' is missing from the OfferCatalog structured data."
            );
        }
    }
}

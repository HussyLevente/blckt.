<?php

namespace Tests\Feature;

use App\Http\Controllers\TemplateController;
use App\Support\Packages;
use Tests\TestCase;

class TemplatesTest extends TestCase
{
    public function test_the_catalogue_renders_in_both_languages(): void
    {
        foreach (['en', 'hu'] as $locale) {
            $response = $this->get('/templates?lang='.$locale);

            $response->assertOk();
            $response->assertSee('SIGNAL');
            $response->assertSee('CARGO');
        }
    }

    public function test_every_template_has_its_own_page(): void
    {
        foreach (app(TemplateController::class)->slugs() as $slug) {
            $this->get('/templates/'.$slug)
                ->assertOk()
                ->assertSee(mb_strtoupper($slug));
        }
    }

    public function test_an_unknown_template_is_a_404(): void
    {
        $this->get('/templates/nem-letezik')->assertNotFound();
    }

    /**
     * A cimlapi sav es a szolgaltatasok oldal is a kontrollerbol veszi az
     * also arat. Ha valaki kezzel beir egy szamot valamelyik sablonba, ez
     * a teszt bukik - pont ezert van itt.
     */
    public function test_the_advertised_floor_price_matches_the_cheapest_template(): void
    {
        $floor = Packages::money(app(TemplateController::class)->floor());

        $this->get('/')->assertOk()->assertSee($floor);
        $this->get('/services')->assertOk()->assertSee($floor);
    }

    /**
     * A szoveg helyenkent kimondja a darabszamot es az also arat ("hat
     * weboldal", "90 000 Ft-tol"). Ezeket szandekosan nem valtottuk
     * behelyettesitesre: a magyar mondatok olvashatobbak igy, es a
     * tulajdonos ugyis kezzel szerkeszti oket.
     *
     * Cserebe itt all egy or. Ha valaki felvesz egy hetedik sablont vagy
     * atirja a legolcsobb arat, ez a teszt megbukik, es megmondja, hol kell
     * utananyulni a szovegnek - kulonben csendben hazudna a lap.
     */
    public function test_the_hard_coded_copy_still_matches_the_catalogue(): void
    {
        $controller = app(TemplateController::class);
        $where = 'templates.blade.php, home.blade.php, services.blade.php and lang/hu.json';

        $this->assertSame(6, $controller->count(),
            "The catalogue size changed. The words 'six' / 'hat' are written out in {$where} — update them, then update this test.");

        $this->assertSame(50000, $controller->floor(),
            "The cheapest template changed. '50 000 Ft' is written out in {$where} — update it, then update this test.");
    }

    public function test_the_templates_are_in_the_sitemap(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();

        $response->assertSee(url('/templates'));

        foreach (app(TemplateController::class)->slugs() as $slug) {
            $response->assertSee(url('/templates/'.$slug));
        }
    }

    /**
     * Minden elonezeti kep letezik a lemezen. Enelkul a katalogus toredezett
     * kepekkel jelenne meg, es ez pont az a hiba, amit senki nem vesz eszre
     * addig, amig egy ugyfel nem szol.
     */
    public function test_every_preview_image_exists(): void
    {
        foreach (app(TemplateController::class)->slugs() as $slug) {
            $this->assertFileExists(public_path("assets/imgs/templates/{$slug}.svg"));
        }
    }
}

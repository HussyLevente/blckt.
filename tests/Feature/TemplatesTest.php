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

    /**
     * A demok statikus fajlok a public/demo alatt: nem Laravel utvonalak,
     * ezert a HTTP tesztek nem latjak oket. Ami itt ellenorizheto - es ami
     * el is szokott romlani -, az az, hogy a kontrollerben hivatkozott
     * mappa tenyleg letezik, es hogy nyithato oldal van benne.
     */
    public function test_every_advertised_demo_exists_on_disk(): void
    {
        $controller = app(TemplateController::class);
        $found = 0;

        foreach ($controller->slugs() as $slug) {
            $template = $this->get('/templates/'.$slug)->assertOk();

            foreach ($this->demoSlugsFor($slug) as $demo) {
                $dir = public_path('demo/'.$demo);

                $this->assertDirectoryExists($dir, "{$slug} advertises a demo at /demo/{$demo}/ but the folder is missing");
                $this->assertFileExists($dir.'/index.html', "/demo/{$demo}/ has no index.html, so the URL would 403 or 404");

                // A demok kitalalt vallalkozasok - egyiket sem szabad indexelni.
                $this->assertStringContainsString(
                    'name="robots" content="noindex"',
                    file_get_contents($dir.'/index.html'),
                    "/demo/{$demo}/ is missing its noindex meta tag"
                );

                $template->assertSee('/demo/'.$demo.'/', false);
                $found++;
            }
        }

        $this->assertGreaterThan(0, $found, 'No demos are wired up at all.');
    }

    public function test_the_demos_are_excluded_from_search_engines(): void
    {
        $this->assertStringContainsString(
            'Disallow: /demo/',
            file_get_contents(public_path('robots.txt'))
        );
    }

    /**
     * @return string[]
     */
    private function demoSlugsFor(string $slug): array
    {
        $demos = [
            'signal' => ['signal-burger', 'signal-attorney'],
            'aperture' => ['aperture-portfolio', 'aperture-contentcreator'],
        ];

        return $demos[$slug] ?? [];
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
     * Minden elonezeti kep letezik a lemezen.
     *
     * A kiterjesztes SZANDEKOSAN nincs beegetve: amelyik sablonhoz mar all
     * elo demo, az valodi kepernyokepet kap (.webp), a tobbi marad a
     * vazlatnal (.svg). A teszt azt nezi, amit a kontroller allit magarol -
     * igy egy kicserelt boritot nem kell itt is atirni, egy elgepelt utat
     * viszont elkap.
     */
    public function test_every_preview_image_exists(): void
    {
        // A katalogus mind a hat boritot kiirja; a sablonoldal viszont mar
        // az elo demot mutatja ott, ahol van, ezert a kepeket itt nezzuk.
        $html = $this->get('/templates')->assertOk()->getContent();

        foreach (app(TemplateController::class)->slugs() as $slug) {
            $pattern = '~assets/imgs/templates/'.preg_quote($slug, '~').'\.(svg|webp|png|jpg)~';

            $this->assertMatchesRegularExpression(
                $pattern,
                $html,
                "{$slug} has no cover image on the catalogue page"
            );

            preg_match($pattern, $html, $m);
            $this->assertFileExists(public_path($m[0]));
        }
    }

    /**
     * Amelyik sablonnak van demoja, annak valodi kepernyokep a boritoja -
     * nem a generalt vazlat. Ez az a lepes, amit a legkonnyebb elfelejteni,
     * amikor egy uj demo elkeszul.
     */
    public function test_templates_with_a_demo_use_a_real_screenshot(): void
    {
        $html = $this->get('/templates')->assertOk()->getContent();

        foreach (['signal', 'aperture'] as $slug) {
            $this->assertStringNotContainsString(
                "assets/imgs/templates/{$slug}.svg",
                $html,
                "{$slug} has a live demo but the catalogue still shows the generated wireframe"
            );

            $this->assertStringContainsString(
                "assets/imgs/templates/{$slug}.webp",
                $html,
                "{$slug} should use the screenshot from tools/make-demo-screenshots.php"
            );
        }
    }
}

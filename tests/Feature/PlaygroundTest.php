<?php

namespace Tests\Feature;

use App\Http\Controllers\PlaygroundController;
use App\Http\Controllers\TemplateController;
use Tests\TestCase;

class PlaygroundTest extends TestCase
{
    public function test_the_playground_index_renders_in_both_languages(): void
    {
        foreach (['en', 'hu'] as $locale) {
            $this->get('/playground?lang='.$locale)
                ->assertOk()
                ->assertSee('Signal Burger')
                ->assertSee('Ada Vale');
        }
    }

    public function test_every_editable_demo_has_its_own_page(): void
    {
        foreach (app(PlaygroundController::class)->slugs() as $slug) {
            foreach (['en', 'hu'] as $locale) {
                $this->get('/playground/'.$slug.'?lang='.$locale)
                    ->assertOk()
                    ->assertSee('data-pg-frame', false);
            }
        }
    }

    public function test_an_unknown_playground_is_a_404(): void
    {
        $this->get('/playground/nem-letezik')->assertNotFound();
    }

    /**
     * A demok nevsora a TemplateController-ben van, a szerkeszto
     * beallitasai a PlaygroundController-ben. Ket helyen taroljuk, mert ket
     * kulon dolog - de ha elcsusznak, az csendben tor el valamit:
     *
     *   - beallitas nelkuli demo: a sablonlap kartyaja 404-re mutatna,
     *   - demo nelkuli beallitas: halott kod, ami ugy nez ki, mintha elne.
     *
     * Ezert nezi a teszt mindket iranyt.
     */
    public function test_the_demo_list_and_the_editor_settings_match(): void
    {
        $demos = array_keys(app(TemplateController::class)->demos());
        $editable = app(PlaygroundController::class)->slugs();

        sort($demos);
        sort($editable);

        $this->assertSame(
            $demos,
            $editable,
            'Every demo needs an entry in PlaygroundController::editors(), and every entry needs a demo. '
            .'Without it the "Try it yourself" link on the template card points at a 404.'
        );
    }

    /**
     * A demo cimeben ott van az index.html.
     *
     * Ez nem szepseghiba volt, hanem 403: az nginx nem olvas .htaccess-t,
     * ezert a public/demo/.htaccess DirectoryIndex sora a szerveren nem
     * ervenyesul, es a mappara mutato cim hibat ad. Ha valaki visszairja a
     * rovid cimet, ez a teszt szol - a lap addig helyben mukodne, es csak
     * elesben derulne ki.
     */
    public function test_demo_urls_point_at_a_file_not_a_directory(): void
    {
        foreach (app(PlaygroundController::class)->editable() as $slug => $demo) {
            $this->assertStringEndsWith(
                '/demo/'.$slug.'/index.html',
                $demo['url'],
                "The {$slug} demo link must name index.html — a bare directory URL is a 403 on nginx."
            );

            $this->assertFileExists(public_path('demo/'.$slug.'/index.html'));
        }

        // A sablonlapon is a fajlra mutato cim all.
        $this->get('/templates/signal')
            ->assertOk()
            ->assertSee('/demo/signal-burger/index.html', false);
    }

    /**
     * A szerkeszto a demo SAJAT CSS valtozoit allitja, es minden demo
     * maskepp nevezi oket. Egy atnevezett valtozo nem hibazik: a szinvalaszto
     * ott marad a lapon, csak nem tortenik tole semmi. Ezert nezzuk meg,
     * hogy amit allitani akarunk, az tenyleg letezik a stiluslapon.
     */
    public function test_every_swatch_targets_a_variable_the_demo_actually_uses(): void
    {
        foreach (app(PlaygroundController::class)->editable() as $slug => $demo) {
            // A stiluslap neve demonkent valtozik (style.css / styles.css),
            // ezert nem a nevere fogadunk: az osszeset beolvassuk. Igy egy
            // atnevezett fajl nem ejti el a vizsgalatot csendben.
            $sheets = glob(public_path('demo/'.$slug.'/*.css'));
            $this->assertNotEmpty($sheets, "{$slug} has no stylesheet at all.");
            $css = implode("
", array_map('file_get_contents', $sheets));

            foreach ($demo['swatches'] as $swatch) {
                foreach (array_keys($swatch['vars']) as $variable) {
                    $this->assertStringContainsString(
                        $variable.':',
                        $css,
                        "{$slug} has no {$variable} in its stylesheet, so the '{$swatch['label']}' picker would do nothing."
                    );
                }

                $this->assertMatchesRegularExpression(
                    '/^#[0-9a-f]{6}$/i',
                    $swatch['default'],
                    "{$slug}: the colour input only accepts six-digit hex, so '{$swatch['default']}' would reset itself."
                );
            }
        }
    }

    /**
     * A szkript minden lathato mondatot a lapbol kap, hogy magyarul is
     * megszolaljon. Ha egy kulcs kimarad, a felulet nemul el azon a ponton -
     * hibauzenet nelkul.
     */
    public function test_the_editor_gets_every_label_it_needs(): void
    {
        $needed = ['background', 'hintEdit', 'hintPreview', 'saved', 'confirmReset', 'tooBig', 'notImage', 'quota'];

        $html = $this->get('/playground/signal-burger')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/data-pg-config="([^"]+)"/', $html);
        preg_match('/data-pg-config="([^"]+)"/', $html, $m);

        $config = json_decode(html_entity_decode($m[1], ENT_QUOTES), true);

        $this->assertIsArray($config, 'The editor config is not valid JSON, so the playground would not start at all.');
        $this->assertSame('signal-burger', $config['demo']);
        $this->assertNotEmpty($config['swatches']);

        foreach ($needed as $key) {
            $this->assertArrayHasKey($key, $config['labels'], "The editor has no wording for '{$key}'.");
            $this->assertNotSame('', $config['labels'][$key]);
        }
    }

    public function test_the_templates_page_advertises_the_playground(): void
    {
        $count = app(PlaygroundController::class)->count();

        $this->get('/templates')
            ->assertOk()
            ->assertSee(route('playground.index'))
            ->assertSee('Try one with your own words and photos.')
            // A kiirt szam a kontrollerbol jon; ha kezzel beirna valaki,
            // ez a sor bukna egy uj demo utan.
            ->assertSee((string) $count);
    }

    public function test_the_playground_is_in_the_sitemap(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();

        $response->assertSee(url('/playground'));

        foreach (app(PlaygroundController::class)->slugs() as $slug) {
            $response->assertSee(url('/playground/'.$slug));
        }
    }
}

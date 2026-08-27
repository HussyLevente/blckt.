<?php

namespace Tests\Feature;

use App\Http\Controllers\TemplateController;
use Tests\TestCase;

class SavedTest extends TestCase
{
    public function test_the_saved_page_renders_in_both_languages(): void
    {
        foreach (['en', 'hu'] as $locale) {
            $this->get('/saved?lang='.$locale)->assertOk();
        }
    }

    /**
     * A lap MINDEN sablont kiterit, es a bongeszo valogat kozuluk. Ha egy
     * sablon kimaradna a jelolesbol, azt nem lehetne elmenteni - a gomb
     * mukodne, a mentett lapon viszont sosem jelenne meg.
     */
    public function test_every_template_is_present_for_the_browser_to_filter(): void
    {
        $response = $this->get('/saved')->assertOk();

        foreach (app(TemplateController::class)->slugs() as $slug) {
            $response->assertSee('data-id="'.$slug.'"', false);
        }
    }

    /**
     * A lista latogatonkent mas, es semmi kozos tartalma nincs: sem a
     * sitemapben, sem a talalati listaban nincs helye.
     */
    public function test_the_saved_page_is_kept_out_of_search(): void
    {
        $this->get('/saved')->assertOk()->assertSee('name="robots" content="noindex', false);
        $this->get('/sitemap.xml')->assertOk()->assertDontSee(url('/saved'));
    }

    /**
     * A mentes gomb kiindulo allapota mindig "nem mentett": a kiszolgalo
     * nem tudhatja, mit mentett el valaki, es egy rossz kezdoallapot
     * villogast okozna a szkript indulasakor.
     */
    public function test_save_buttons_start_unpressed_and_carry_a_type(): void
    {
        $html = $this->get('/templates')->assertOk()->getContent();

        $this->assertStringContainsString('data-save-type="template"', $html);
        $this->assertStringNotContainsString('aria-pressed="true"', $html);
    }

    public function test_the_header_offers_the_saved_list_on_every_page(): void
    {
        foreach (['/', '/templates', '/services', '/saved'] as $path) {
            $this->get($path)->assertOk()
                ->assertSee('data-saved-link', false)
                ->assertSee('assets/js/saved.js', false);
        }
    }
}

<?php

namespace Tests\Feature;

use App\Console\Commands\BuildAssets;
use ReflectionMethod;
use Tests\TestCase;

/**
 * A sajat tomorito.
 *
 * Ez a fajl azert van, mert a tomorito egyszer mar csendben eltort egy
 * mukodo szkriptet: nem hibazott, nem szolt, csak levagta egy sor
 * maradekat - es a hiba kizarolag eles uzemben latszott volna, mert helyben
 * a nyers valtozatot szolgaljuk ki.
 *
 * Amit itt ellenorzunk, az mind olyan alak, ami egy naiv "keresd meg a
 * kommentjelet" logikat megvezet.
 */
class BuildAssetsTest extends TestCase
{
    private function minify(string $js): string
    {
        $method = new ReflectionMethod(BuildAssets::class, 'minifyJs');
        $method->setAccessible(true);

        return $method->invoke(app(BuildAssets::class), $js);
    }

    /**
     * Ez az a konkret alak, ami eltort.
     *
     * A nyito perjelet osztasnak nezve a lezaro elotti escape-elt perjel es
     * maga a lezaro ket egymas melletti perjelnek latszik, vagyis
     * sorkommentnek - a sor maradeka pedig eltunik.
     */
    public function test_a_regex_after_return_survives(): void
    {
        $out = $this->minify('function f(h) { return /^https?:\/\//i.test(h) && h.length > 0; }');

        $this->assertStringContainsString('/^https?:\/\//i.test(h)', $out);
        $this->assertStringContainsString('h.length > 0', $out);
    }

    public static function keywordProvider(): array
    {
        return [
            'typeof' => ['var a = typeof /a\/b/;'],
            'case' => ['switch (v) { case /a\/b/.source: break; }'],
            'throw' => ['if (bad) throw /a\/b/;'],
            'new' => ['var r = new RegExp(/a\/b/);'],
            'else' => ['if (a) { b(); } else /a\/b/.test(c);'],
        ];
    }

    /**
     * @dataProvider keywordProvider
     */
    public function test_a_regex_after_any_keyword_survives(string $source): void
    {
        $out = $this->minify($source);

        $this->assertStringContainsString('a\/b', $out, 'The minifier swallowed a regex that follows a keyword.');
    }

    /**
     * A masik irany: ami tenyleg osztas, az ne valjon regexsze. Ha igen, a
     * tomorito a kovetkezo perjelig mindent felfalna.
     */
    public function test_division_is_not_mistaken_for_a_regex(): void
    {
        $out = $this->minify('var r = ((n >> 16) & 255) / 255;'."\n".'var l = (max + min) / 2;'."\n".'keep();');

        $this->assertStringContainsString('/ 255;', $out);
        $this->assertStringContainsString('/ 2;', $out);
        $this->assertStringContainsString('keep();', $out);
    }

    /**
     * A "join" vege nem "in" - a kulcsszo-felismeres nem haraphat bele egy
     * azonositoba.
     */
    public function test_an_identifier_ending_in_a_keyword_still_divides(): void
    {
        $out = $this->minify('var x = margin / 2;'."\n".'var y = anew / 3;'."\n".'keep();');

        $this->assertStringContainsString('margin / 2;', $out);
        $this->assertStringContainsString('anew / 3;', $out);
        $this->assertStringContainsString('keep();', $out);
    }

    public function test_comment_markers_inside_strings_are_left_alone(): void
    {
        $out = $this->minify("var a = 'https://example.com';\nvar b = \"/* not a comment */\";\nkeep();");

        $this->assertStringContainsString("'https://example.com'", $out);
        $this->assertStringContainsString('/* not a comment */', $out);
        $this->assertStringContainsString('keep();', $out);
    }

    public function test_real_comments_are_still_removed(): void
    {
        $out = $this->minify("// gone\nvar a = 1; // also gone\n/* gone too */\nvar b = 2;");

        $this->assertStringNotContainsString('gone', $out);
        $this->assertStringContainsString('var a = 1;', $out);
        $this->assertStringContainsString('var b = 2;', $out);
    }

    /**
     * Minden legyartott szkript ott van, es egyik sem lett rovidebb annal,
     * mint ami hiheto. Nem szintaxis-ellenorzes - arra itt nincs eszkoz -,
     * de egy levagott fajlt elkap.
     *
     * A .min.js fajlok nincsenek verziozva, ezert egy friss klonon meg nem
     * leteznek. Olyankor nincs mit ellenorizni: a teszt kihagyja magat,
     * ahelyett hogy egy meg le sem futtatott buildert hibaztatna.
     */
    public function test_every_script_has_a_plausible_minified_twin(): void
    {
        $sources = array_filter(
            glob(public_path('assets/js/*.js')),
            fn (string $f) => ! str_contains(basename($f), '.min.')
        );

        if (glob(public_path('assets/js/*.min.js')) === []) {
            $this->markTestSkipped('Nothing built yet — run: php artisan assets:build');
        }

        foreach ($sources as $source) {
            $built = str_replace('.js', '.min.js', $source);

            $this->assertFileExists($built, basename($source).' has no built copy — run: php artisan assets:build');

            // A tomorites kommenteket es behuzast vesz el. Ha az eredmeny a
            // forras negyedenel is kisebb, ott valami elveszett.
            $this->assertGreaterThan(
                filesize($source) * 0.25,
                filesize($built),
                basename($built).' is suspiciously small — the minifier probably ate something.'
            );
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Statikus eszkozok tomoritese.
 *
 * A CSS-nel agresszivan vagunk (kommentek, felesleges terkoz, utolso
 * pontosvesszo). A JS-nel viszont SZANDEKOSAN megtartjuk a sorvegeket:
 * a sorok osszevonasa az automatikus pontosvesszo-beszurast (ASI) borithatja
 * fel, ami csendben tori el a szkriptet. A behuzas es a kommentek igy is
 * elfogynak, a maradekot pedig a gzip amugy is elintezi - a kockazat nem
 * eri meg azt a par szazalekot.
 */
class BuildAssets extends Command
{
    protected $signature = 'assets:build {--clean : Csak torli a legyartott fajlokat}';

    protected $description = 'Minified .min.css / .min.js valtozatok gyartasa';

    public function handle(): int
    {
        $css = glob(public_path('assets/css/*.css'));
        $js = glob(public_path('assets/js/*.js'));

        // A korabban legyartott fajlokat sose dolgozzuk fel ujra.
        $source = fn (array $files) => array_values(array_filter(
            $files,
            fn ($f) => ! str_contains(basename($f), '.min.')
        ));

        $css = $source($css);
        $js = $source($js);

        if ($this->option('clean')) {
            $removed = 0;
            foreach (array_merge(glob(public_path('assets/css/*.min.css')), glob(public_path('assets/js/*.min.js'))) as $f) {
                unlink($f);
                $removed++;
            }
            $this->info("Removed $removed built file(s).");

            return self::SUCCESS;
        }

        $savedTotal = 0;
        $rawTotal = 0;

        foreach ($css as $file) {
            $savedTotal += $this->write($file, '.min.css', $this->minifyCss(file_get_contents($file)));
            $rawTotal += filesize($file);
        }

        foreach ($js as $file) {
            $savedTotal += $this->write($file, '.min.js', $this->minifyJs(file_get_contents($file)));
            $rawTotal += filesize($file);
        }

        $this->newLine();
        $this->info(sprintf(
            'Built %d file(s): %s -> %s (%d%% smaller)',
            count($css) + count($js),
            $this->human($rawTotal),
            $this->human($rawTotal - $savedTotal),
            $rawTotal > 0 ? round($savedTotal / $rawTotal * 100) : 0
        ));

        return self::SUCCESS;
    }

    private function write(string $source, string $suffix, string $content): int
    {
        $target = preg_replace('#\.(css|js)$#', $suffix, $source);
        file_put_contents($target, $content);

        $saved = filesize($source) - strlen($content);
        $this->line(sprintf(
            '  %-26s %7s -> %7s',
            basename($source),
            $this->human(filesize($source)),
            $this->human(strlen($content))
        ));

        return $saved;
    }

    private function minifyCss(string $css): string
    {
        // Kommentek (a /*! jelzesu licenc-blokkot is visszuk, nincs ilyen)
        $css = preg_replace('#/\*.*?\*/#s', '', $css);

        // Terkoz osszevonasa es a strukturalis jelek koruli szellos resz
        $css = preg_replace('#\s+#', ' ', $css);
        $css = preg_replace('#\s*([{}:;,>~])\s*#', '$1', $css);

        // Az utolso pontosvesszo a blokk vegen felesleges
        $css = str_replace(';}', '}', $css);

        return trim($css);
    }

    private function minifyJs(string $js): string
    {
        $out = '';
        $len = strlen($js);
        $i = 0;

        // Kezi vegigolvasas, mert a kommentjel elofordulhat sztringben és
        // regex-literalban is - ezeket erintetlenul kell hagyni.
        while ($i < $len) {
            $c = $js[$i];
            $next = $i + 1 < $len ? $js[$i + 1] : '';

            // Sorkomment
            if ($c === '/' && $next === '/') {
                while ($i < $len && $js[$i] !== "\n") {
                    $i++;
                }

                continue;
            }

            // Blokk-komment
            if ($c === '/' && $next === '*') {
                $end = strpos($js, '*/', $i + 2);
                $i = $end === false ? $len : $end + 2;

                continue;
            }

            // Sztringek es sablon-literalok: karakterhuen atmasolva
            if ($c === '"' || $c === "'" || $c === '`') {
                $quote = $c;
                $out .= $c;
                $i++;
                while ($i < $len) {
                    $out .= $js[$i];
                    if ($js[$i] === chr(92)) {
                        $i++;
                        if ($i < $len) {
                            $out .= $js[$i];
                            $i++;
                        }

                        continue;
                    }
                    if ($js[$i] === $quote) {
                        $i++;
                        break;
                    }
                    $i++;
                }

                continue;
            }

            // Regex-literal: csak ott lehet, ahol ertek kezdodhet
            if ($c === '/' && $this->regexAllowed($out)) {
                $out .= $c;
                $i++;
                $inClass = false;
                while ($i < $len) {
                    $ch = $js[$i];
                    $out .= $ch;
                    if ($ch === chr(92)) {
                        $i++;
                        if ($i < $len) {
                            $out .= $js[$i];
                            $i++;
                        }

                        continue;
                    }
                    if ($ch === '[') {
                        $inClass = true;
                    } elseif ($ch === ']') {
                        $inClass = false;
                    } elseif ($ch === '/' && ! $inClass) {
                        $i++;
                        break;
                    }
                    $i++;
                }

                continue;
            }

            $out .= $c;
            $i++;
        }

        // Behuzas es ures sorok. A sorvegek maradnak - lasd az osztaly
        // fejlecenel: az ASI miatt nem vonjuk ossze a sorokat.
        $lines = [];
        foreach (explode("\n", $out) as $line) {
            $line = rtrim($line);
            $trimmed = ltrim($line);
            if ($trimmed !== '') {
                $lines[] = $trimmed;
            }
        }

        return implode("\n", $lines);
    }

    private function regexAllowed(string $before): bool
    {
        $trimmed = rtrim($before);
        if ($trimmed === '') {
            return true;
        }

        // Operator vagy nyito jel utan regex jon, azonosito/zaro utan osztas
        if (preg_match('#[(,=:\[!&|?{};+\-*%~^<>]$#', $trimmed)) {
            return true;
        }

        /*
         * Kulcsszo utan is regex all, nem osztas.
         *
         * E nelkul a "return /^https?:\/\//i.test(x)" alaku sor csendben
         * eltorik: a nyito perjelet osztasnak nezzuk, igy a lezaro elotti
         * escape-elt perjel es maga a lezaro ket egymas melletti perjelnek
         * latszik - vagyis sorkommentnek -, es a sor maradeka eltunik.
         *
         * A lookbehind azert kell, hogy a "join" vege ne szamitson "in"-nek.
         */
        return (bool) preg_match(
            '#(?<![\w$])(return|typeof|instanceof|new|delete|void|throw|case|do|else|yield|await)$#',
            $trimmed
        );
    }

    private function human(int $bytes): string
    {
        return $bytes >= 1024 ? round($bytes / 1024, 1).' KB' : $bytes.' B';
    }
}

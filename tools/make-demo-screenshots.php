<?php
/**
 * Turns the live demo sites in public/demo into the catalogue cover images
 * used on /templates and the homepage band.
 *
 * Drives an installed Chrome or Edge in headless mode, then converts the
 * capture to WebP through GD. Two flags matter and are not optional:
 *
 *   --force-prefers-reduced-motion   the demos fade their sections in on
 *                                    scroll; without this the capture
 *                                    catches them half-faded
 *   --virtual-time-budget            gives remote images and web fonts time
 *                                    to arrive before the shutter
 *
 * Re-run after changing a demo:  php tools/make-demo-screenshots.php
 */

// Melyik demo adja melyik sablon boritojat. Ahol ket demo van, a
// latvanyosabbik all itt - a masikat a sablonoldal A/B valtoja mutatja meg.
$covers = [
    'signal' => 'signal-burger',
    'aperture' => 'aperture-portfolio',
];

const WIDTH = 1280;
const HEIGHT = 800;   // 16:10, ugyanaz az arany, mint a .tpl-shot kereteben
const QUALITY = 82;

$root = realpath(__DIR__.'/..');
$demoDir = $root.'/public/demo';
$outDir = $root.'/public/assets/imgs/templates';

/** Telepitett bongeszo keresese. */
function findBrowser(): ?string
{
    $candidates = [
        'C:/Program Files/Google/Chrome/Application/chrome.exe',
        'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
        'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
        'C:/Program Files/Microsoft/Edge/Application/msedge.exe',
        '/usr/bin/google-chrome',
        '/usr/bin/chromium',
        '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
    ];

    foreach ($candidates as $path) {
        if (is_file($path)) {
            return $path;
        }
    }

    return null;
}

$browser = findBrowser();

if (! $browser) {
    fwrite(STDERR, "No Chrome or Edge found. Install one, or add its path to findBrowser().\n");
    exit(1);
}

if (! function_exists('imagewebp')) {
    fwrite(STDERR, "GD has no WebP support in this PHP build.\n");
    exit(1);
}

echo 'Using '.basename($browser)."\n\n";

$failed = 0;

foreach ($covers as $slug => $demo) {
    $source = $demoDir.'/'.$demo.'/index.html';

    if (! is_file($source)) {
        fwrite(STDERR, "  MISSING  public/demo/{$demo}/index.html\n");
        $failed++;

        continue;
    }

    $png = sys_get_temp_dir().'/blckt-shot-'.$slug.'.png';
    @unlink($png);

    // A file:// cim szokozeit kodolni kell, kulonben a bongeszo ket
    // argumentumnak latja az utvonalat.
    $url = 'file:///'.str_replace([DIRECTORY_SEPARATOR, ' '], ['/', '%20'], $source);

    // A cimet SZANDEKOSAN nem az escapeshellarg idezi. Windowson az a
    // fuggveny a % jelet szokozre csereli (a kornyezeti valtozok
    // behelyettesitese ellen), amitol a %20-bol " 20" lesz, a cim eltorik,
    // es a bongeszo egy ures lapot fotoz le - hibauzenet nelkul. A cimben
    // idezojel nem fordulhat elo, ezert a kezi idezes itt biztonsagos.
    $quotedUrl = '"'.$url.'"';

    $cmd = sprintf(
        '%s --headless=new --disable-gpu --no-sandbox --hide-scrollbars'
        .' --force-prefers-reduced-motion --virtual-time-budget=10000'
        .' --window-size=%d,%d --screenshot=%s %s',
        escapeshellarg($browser),
        WIDTH,
        HEIGHT,
        escapeshellarg($png),
        $quotedUrl
    );

    exec($cmd.' 2>&1', $out, $code);

    if (! is_file($png)) {
        fwrite(STDERR, "  FAILED   {$demo} (browser exit {$code})\n");
        $failed++;

        continue;
    }

    // Egy ures lap is ervenyes PNG, csak nagyon kicsi. Ha a bongeszo nem
    // erte el a fajlt, vagy a stilus nem toltodott be, ez fogja meg -
    // kulonben egy feher negyzet kerulne ki a katalogusba.
    if (filesize($png) < 60 * 1024) {
        fwrite(STDERR, sprintf(
            "  BLANK    %s (capture is only %.0f KB - the page did not render)\n",
            $demo,
            filesize($png) / 1024
        ));
        @unlink($png);
        $failed++;

        continue;
    }

    $image = imagecreatefrompng($png);
    imagepalettetotruecolor($image);

    $target = $outDir.'/'.$slug.'.webp';
    imagewebp($image, $target, QUALITY);
    imagedestroy($image);
    @unlink($png);

    printf(
        "  %-10s <- %-26s %5.0f KB\n",
        basename($target),
        $demo,
        filesize($target) / 1024
    );
}

echo "\n";

if ($failed) {
    fwrite(STDERR, "{$failed} cover(s) could not be generated.\n");
    exit(1);
}

echo "Covers written to public/assets/imgs/templates/.\n";
echo "Templates without a demo keep their wireframe from make-template-previews.php.\n";

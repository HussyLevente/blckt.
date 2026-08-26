<?php
/**
 * Generates clearly-schematic PLACEHOLDER previews for the website templates,
 * one per slug in TemplateController. Each gets its own recognisable layout so
 * the catalogue reads as six different sites rather than six copies.
 *
 * These are wireframes, not screenshots: at card size they show about as much
 * as a blurred screenshot would. Replace public/assets/imgs/templates/{slug}.svg
 * with a real capture once a template is actually built — nothing else has to
 * change, the page reads whatever file is there.
 *
 * Re-run with: php tools/make-template-previews.php
 */

const W = 1280;
const H = 800;

$OUT = __DIR__.'/../public/assets/imgs/templates';

$light = [
    'bg' => '#ffffff', 'sunken' => '#f1f1f1', 'line' => '#e4e4e4',
    'ink' => '#151515', 'mid' => '#a2a2a2', 'faint' => '#d8d8d8', 'band' => '#111111',
];
$dark = [
    'bg' => '#0d0d0d', 'sunken' => '#1a1a1a', 'line' => '#282828',
    'ink' => '#f4f4f4', 'mid' => '#6b6b6b', 'faint' => '#343434', 'band' => '#f4f4f4',
];

function r(float $x, float $y, float $w, float $h, string $fill, float $rad = 0, float $op = 1): string
{
    $a = sprintf('<rect x="%.1f" y="%.1f" width="%.1f" height="%.1f" fill="%s"', $x, $y, $w, $h, $fill);
    if ($rad > 0) {
        $a .= sprintf(' rx="%.1f"', $rad);
    }
    if ($op < 1) {
        $a .= sprintf(' opacity="%.2f"', $op);
    }

    return $a.'/>';
}

function c(float $cx, float $cy, float $rad, string $fill, float $op = 1): string
{
    return sprintf(
        '<circle cx="%.1f" cy="%.1f" r="%.1f" fill="%s"%s/>',
        $cx, $cy, $rad, $fill, $op < 1 ? sprintf(' opacity="%.2f"', $op) : ''
    );
}

function line(float $x1, float $y1, float $x2, float $y2, string $stroke, float $w = 1, float $op = 1): string
{
    return sprintf(
        '<line x1="%.1f" y1="%.1f" x2="%.1f" y2="%.1f" stroke="%s" stroke-width="%.1f"%s/>',
        $x1, $y1, $x2, $y2, $stroke, $w, $op < 1 ? sprintf(' opacity="%.2f"', $op) : ''
    );
}

/** Szovegsorok utanzata: valtozo hosszusagu savok. */
function lines(float $x, float $y, float $w, int $count, string $fill, float $h = 9, float $gap = 16, float $op = 0.55): string
{
    $out = '';
    $ratios = [1, 0.94, 0.82, 0.97, 0.7, 0.88, 0.6];
    for ($i = 0; $i < $count; $i++) {
        $out .= r($x, $y + $i * ($h + $gap), $w * $ratios[$i % count($ratios)], $h, $fill, $h / 2, $op);
    }

    return $out;
}

/** Fejlec: szomark balra, menupontok jobbra. */
function topbar(array $p, float $y = 0, float $h = 64, bool $cart = false, ?string $bg = null): string
{
    $out = r(0, $y, W, $h, $bg ?? $p['bg']);
    $out .= r(72, $y + $h / 2 - 7, 84, 14, $p['ink'], 3, 0.9);
    $x = W - 72;
    $items = [46, 58, 40, 62];
    foreach (array_reverse($items) as $wdt) {
        $x -= $wdt;
        $out .= r($x, $y + $h / 2 - 5, $wdt, 10, $p['mid'], 5, 0.75);
        $x -= 34;
    }
    if ($cart) {
        $out .= c(W - 72 - 8, $y + $h / 2, 9, $p['ink'], 0.75);
    }
    $out .= line(0, $y + $h, W, $y + $h, $p['line'], 2);

    return $out;
}

function svg(string $body, array $p): string
{
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.W.' '.H.'" width="'.W.'" height="'.H.'" role="img">'
        .r(0, 0, W, H, $p['bg'])
        .$body
        .'</svg>';
}

$files = [];

/* ── SIGNAL — egyoldalas inditolap, sotet, kozepre zart ───────────── */
$p = $dark;
$b = topbar($p, 0, 64);
$b .= r(W / 2 - 60, 132, 120, 10, $p['mid'], 5, 0.6);
foreach ([[300, 176], [420, 236], [220, 296]] as [$w, $y]) {
    $b .= r(W / 2 - $w / 2, $y, $w, 44, $p['ink'], 6, 0.92);
}
$b .= r(W / 2 - 94, 380, 188, 44, $p['ink'], 22, 0.9);
$b .= r(W / 2 - 220, 466, 440, 8, $p['faint'], 4);
for ($i = 0; $i < 3; $i++) {
    $x = 128 + $i * 352;
    $b .= r($x, 534, 304, 176, $p['sunken'], 14);
    $b .= c($x + 34, 574, 13, $p['mid'], 0.7);
    $b .= lines($x + 34, 606, 236, 3, $p['mid'], 8, 13, 0.5);
}
$b .= r(0, H - 46, W, 46, $p['sunken']);
$files['signal'] = svg($b, $p);

/* ── APERTURE — portfolio, vilagos, galeria-racs ──────────────────── */
$p = $light;
$b = topbar($p, 0, 64);
$b .= r(72, 122, 74, 9, $p['mid'], 4, 0.7);
$b .= r(72, 152, 388, 38, $p['ink'], 5, 0.9);
$b .= lines(72, 214, 420, 2, $p['mid'], 8, 14, 0.5);
$b .= r(W - 72 - 130, 156, 130, 34, $p['ink'], 17, 0.85);
$grid = [
    [72, 282, 552, 300], [648, 282, 264, 300], [936, 282, 272, 300],
    [72, 606, 264, 194], [360, 606, 264, 194], [648, 606, 560, 194],
];
$shades = [0.95, 0.68, 0.82, 0.74, 0.9, 0.62];
foreach ($grid as $i => [$x, $y, $w, $h]) {
    $b .= r($x, $y, $w, $h, $p['sunken'], 10);
    $b .= r($x, $y, $w, $h, $p['mid'], 10, 0.18 + $shades[$i] * 0.22);
    $b .= c($x + $w / 2, $y + $h / 2, min($w, $h) * 0.16, $p['bg'], 0.5);
}
$files['aperture'] = svg($b, $p);

/* ── ATRIUM — etterem, sotet, hero + etlap ────────────────────────── */
$p = $dark;
$b = r(0, 0, W, 372, $p['sunken']);
$b .= r(0, 0, W, 372, $p['mid'], 0, 0.1);
$b .= topbar($p, 0, 64, false, 'none');
$b .= r(W / 2 - 46, 148, 92, 9, $p['mid'], 4, 0.7);
$b .= r(W / 2 - 236, 182, 472, 46, $p['ink'], 6, 0.92);
$b .= r(W / 2 - 130, 246, 260, 12, $p['ink'], 6, 0.5);
$b .= r(W / 2 - 82, 292, 164, 40, $p['ink'], 20, 0.85);
$b .= r(72, 424, 120, 10, $p['mid'], 5, 0.7);
for ($col = 0; $col < 2; $col++) {
    $x = 72 + $col * 584;
    for ($i = 0; $i < 5; $i++) {
        $y = 464 + $i * 62;
        $b .= r($x, $y, 150 + ($i % 3) * 46, 12, $p['ink'], 6, 0.8);
        $b .= r($x, $y + 26, 236, 8, $p['mid'], 4, 0.45);
        $b .= sprintf(
            '<line x1="%.1f" y1="%.1f" x2="%.1f" y2="%.1f" stroke="%s" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="0 8" opacity="0.4"/>',
            $x + 330, $y + 6, $x + 438, $y + 6, $p['mid']
        );
        $b .= r($x + 464, $y, 48, 12, $p['ink'], 6, 0.75);
    }
}
$b .= line(W / 2, 424, W / 2, 760, $p['line'], 2);
$files['atrium'] = svg($b, $p);

/* ── POISE — szalon, vilagos, osztott hero + arlista ──────────────── */
$p = $light;
$b = topbar($p, 0, 64);
$b .= r(72, 128, 62, 9, $p['mid'], 4, 0.7);
$b .= r(72, 158, 356, 38, $p['ink'], 5, 0.9);
$b .= r(72, 206, 268, 38, $p['ink'], 5, 0.9);
$b .= lines(72, 270, 380, 2, $p['mid'], 8, 14, 0.5);
$b .= r(72, 340, 156, 40, $p['ink'], 20, 0.85);
$b .= r(660, 112, 548, 348, $p['sunken'], 16);
$b .= r(660, 112, 548, 348, $p['mid'], 16, 0.24);
$b .= c(934, 268, 62, $p['bg'], 0.45);
$b .= r(72, 512, 96, 9, $p['mid'], 4, 0.7);
for ($i = 0; $i < 4; $i++) {
    $y = 548 + $i * 58;
    $b .= r(72, $y, 190 + ($i % 2) * 60, 11, $p['ink'], 5, 0.78);
    $b .= r(560, $y, 62, 11, $p['mid'], 5, 0.6);
    $b .= line(72, $y + 38, 622, $y + 38, $p['line'], 2);
}
for ($i = 0; $i < 3; $i++) {
    $x = 706 + $i * 172;
    $b .= c($x + 60, 604, 60, $p['sunken']);
    $b .= c($x + 60, 604, 60, $p['mid'], 0.22);
    $b .= r($x + 18, 690, 84, 9, $p['mid'], 4, 0.55);
    $b .= r($x + 34, 712, 52, 7, $p['faint'], 4);
}
$files['poise'] = svg($b, $p);

/* ── FOUNDRY — szakipar, vilagos, sotet fejlecsav ─────────────────── */
$p = $light;
$b = r(0, 0, W, 76, $p['band']);
$b .= r(72, 30, 92, 16, '#ffffff', 3, 0.95);
$x = W - 72;
foreach ([52, 44, 64, 48] as $wdt) {
    $x -= $wdt;
    $b .= r($x, 33, $wdt, 10, '#ffffff', 5, 0.55);
    $x -= 32;
}
$b .= r(0, 76, W, 268, $p['sunken']);
$b .= r(0, 76, W, 268, $p['mid'], 0, 0.16);
$b .= r(72, 138, 470, 42, $p['band'], 3, 0.92);
$b .= r(72, 194, 330, 42, $p['band'], 3, 0.92);
$b .= r(72, 264, 172, 42, $p['band'], 0, 0.88);
$b .= r(256, 264, 148, 42, $p['band'], 0, 0.2);
for ($i = 0; $i < 3; $i++) {
    $x = 72 + $i * 380;
    // A kartya hattere sotetebb a papirnal: feher-a-feheren csak a felso
    // vonal latszana, es a lap also fele uresnek tunne.
    $b .= r($x, 396, 336, 172, $p['sunken'], 4);
    $b .= r($x, 396, 336, 4, $p['band'], 0, 0.85);
    $b .= r($x + 28, 428, 34, 34, $p['band'], 2, 0.9);
    $b .= r($x + 28, 482, 172, 12, $p['ink'], 3, 0.8);
    $b .= lines($x + 28, 510, 260, 2, $p['mid'], 7, 12, 0.5);
}
$b .= r(72, 610, 118, 9, $p['mid'], 4, 0.7);
for ($i = 0; $i < 4; $i++) {
    $x = 72 + $i * 288;
    $b .= r($x, 642, 264, 158, $p['sunken'], 4);
    $b .= r($x, 642, 264, 158, $p['mid'], 4, 0.24 + ($i % 3) * 0.13);
}
$files['foundry'] = svg($b, $p);

/* ── CARGO — webaruhaz, vilagos, termekracs ───────────────────────── */
$p = $light;
$b = r(0, 0, W, 30, $p['band']);
$b .= r(W / 2 - 118, 11, 236, 8, '#ffffff', 4, 0.7);
$b .= topbar($p, 30, 66, true);
$b .= r(72, 128, W - 144, 158, $p['sunken'], 12);
$b .= r(72, 128, W - 144, 158, $p['mid'], 12, 0.18);
$b .= r(120, 176, 288, 26, $p['ink'], 4, 0.85);
$b .= r(120, 216, 196, 26, $p['ink'], 4, 0.85);
$b .= r(72, 322, 84, 9, $p['mid'], 4, 0.7);
$b .= r(W - 72 - 118, 316, 118, 22, $p['sunken'], 11);
for ($row = 0; $row < 2; $row++) {
    for ($col = 0; $col < 4; $col++) {
        $x = 72 + $col * 290;
        $y = 358 + $row * 226;
        $b .= r($x, $y, 266, 158, $p['sunken'], 8);
        $b .= r($x, $y, 266, 158, $p['mid'], 8, 0.12 + (($row * 4 + $col) % 4) * 0.06);
        $b .= c($x + 133, $y + 79, 34, $p['bg'], 0.55);
        $b .= r($x, $y + 174, 148, 10, $p['ink'], 5, 0.75);
        $b .= r($x, $y + 194, 60, 9, $p['mid'], 4, 0.55);
    }
}
$files['cargo'] = svg($b, $p);

foreach ($files as $slug => $markup) {
    $path = $OUT.'/'.$slug.'.svg';
    file_put_contents($path, $markup);
    printf("%-12s %6.1f KB\n", $slug, filesize($path) / 1024);
}

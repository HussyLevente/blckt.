<?php
/**
 * Generates clearly-labelled PLACEHOLDER "old site" screenshots so the
 * before/after comparison component can be seen working before the real
 * client screenshots are dropped in. Replace or delete the generated files —
 * deleting them hides the comparison section automatically.
 */

$regular = 'C:/Windows/Fonts/arial.ttf';
$bold = 'C:/Windows/Fonts/arialbd.ttf';
$serif = 'C:/Windows/Fonts/times.ttf';

$projects = [
    'paradise' => 'Paradise',
    'palesso' => 'Palesso',
    'kepszakadas' => "K\u{00e9}pszakad\u{00e1}s",
    'juiced' => 'Juiced',
];

$W = 1600;
$H = 1000;

foreach ($projects as $slug => $name) {
    $im = imagecreatetruecolor($W, $H);
    imageantialias($im, true);

    $c = fn ($r, $g, $b) => imagecolorallocate($im, $r, $g, $b);

    $page      = $c(233, 233, 230);
    $chrome    = $c(214, 214, 210);
    $white     = $c(252, 252, 250);
    $navBlue   = $c(41, 74, 128);
    $navBlue2  = $c(58, 99, 163);
    $linkBlue  = $c(28, 62, 138);
    $ink       = $c(64, 64, 62);
    $inkSoft   = $c(140, 140, 136);
    $rule      = $c(198, 198, 194);
    $bar       = $c(186, 186, 182);
    $barSoft   = $c(206, 206, 202);
    $btn       = $c(196, 132, 46);
    $badge     = $c(120, 120, 116);

    imagefilledrectangle($im, 0, 0, $W, $H, $page);

    // Browser chrome
    imagefilledrectangle($im, 0, 0, $W, 54, $chrome);
    foreach ([[40, 0xB0], [72, 0xB8], [104, 0xC0]] as [$x, $v]) {
        imagefilledellipse($im, $x, 27, 16, 16, $c($v, $v, $v - 6));
    }
    imagefilledrectangle($im, 150, 13, $W - 40, 41, $white);
    imagerectangle($im, 150, 13, $W - 40, 41, $rule);
    imagettftext($im, 13, 0, 168, 32, $inkSoft, $regular, 'http://www.' . strtolower($slug) . '.example/index.html');

    // Header band
    imagefilledrectangle($im, 0, 54, $W, 150, $navBlue);
    imagettftext($im, 34, 0, 60, 118, $white, $serif, $name);
    imagettftext($im, 12, 0, 62, 142, $c(178, 198, 226), $regular, 'W E L C O M E   T O   O U R   H O M E P A G E');

    // Nav bar
    imagefilledrectangle($im, 0, 150, $W, 192, $navBlue2);
    $items = ['Home', 'About Us', 'Products', 'Gallery', 'News', 'Links', 'Contact'];
    $x = 60;
    foreach ($items as $item) {
        imagettftext($im, 14, 0, $x, 178, $white, $regular, $item);
        $x += 30 + (int) (strlen($item) * 9.5);
        imagefilledrectangle($im, $x - 18, 160, $x - 17, 182, $c(120, 150, 194));
    }

    // Hero
    imagefilledrectangle($im, 60, 224, 1080, 520, $white);
    imagerectangle($im, 60, 224, 1080, 520, $rule);
    for ($i = 0; $i < 296; $i++) {
        $t = $i / 296;
        imageline($im, 61, 225 + $i, 1079, 225 + $i, $c(
            (int) (176 - 46 * $t), (int) (196 - 44 * $t), (int) (214 - 28 * $t)
        ));
    }
    imagettftext($im, 11, 0, 76, 250, $c(240, 244, 248), $regular, 'hero_banner_final_v3.jpg');
    imagettftext($im, 26, 0, 96, 400, $white, $bold, 'Quality service since 2009');
    imagefilledrectangle($im, 96, 428, 300, 470, $btn);
    imagettftext($im, 14, 0, 128, 455, $white, $bold, 'CLICK HERE >>');

    // Sidebar
    imagefilledrectangle($im, 1112, 224, 1540, 520, $white);
    imagerectangle($im, 1112, 224, 1540, 520, $rule);
    imagefilledrectangle($im, 1112, 224, 1540, 262, $navBlue2);
    imagettftext($im, 13, 0, 1130, 250, $white, $bold, 'LATEST NEWS');
    for ($i = 0; $i < 6; $i++) {
        $y = 288 + $i * 36;
        imagettftext($im, 12, 0, 1130, $y, $linkBlue, $regular, '> News item headline ' . ($i + 1));
        imageline($im, 1130, $y + 4, 1130 + 178, $y + 4, $linkBlue);
    }

    // Three dense columns
    for ($col = 0; $col < 3; $col++) {
        $cx = 60 + $col * 500;
        imagettftext($im, 16, 0, $cx, 586, $ink, $bold, ['Our Services', 'About The Company', 'Testimonials'][$col]);
        imageline($im, $cx, 600, $cx + 440, 600, $rule);
        for ($line = 0; $line < 9; $line++) {
            $y = 626 + $line * 22;
            $w = 440 - ($line % 3) * 70;
            imagefilledrectangle($im, $cx, $y, $cx + $w, $y + 8, $line % 4 === 3 ? $barSoft : $bar);
        }
        imagettftext($im, 12, 0, $cx, 866, $linkBlue, $regular, 'read more...');
        imageline($im, $cx, 870, $cx + 84, 870, $linkBlue);
    }

    // Footer
    imagefilledrectangle($im, 0, 916, $W, $H, $navBlue);
    imagettftext($im, 12, 0, 60, 954, $c(190, 206, 228), $regular, "\u{00a9} 2009-2024 {$name}. All rights reserved.  |  Sitemap  |  Privacy  |  Webmaster");
    imagettftext($im, 11, 0, 60, 980, $c(150, 172, 202), $regular, 'Best viewed at 1024x768 resolution');

    // Placeholder badge - so nobody mistakes this for a real screenshot
    imagefilledrectangle($im, $W - 498, $H - 168, $W - 60, $H - 126, $badge);
    imagettftext($im, 13, 0, $W - 480, $H - 141, $white, $bold, 'PLACEHOLDER - swap in the real BEFORE shot');

    $out = "public/assets/imgs/websites/{$slug}/{$slug}_before.png";
    
    imagepng($im, $out, 8);
    imagedestroy($im);
    echo "wrote {$out}\n";
}

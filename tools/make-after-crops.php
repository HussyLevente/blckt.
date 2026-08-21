<?php
/**
 * The *_whole1.jpg gallery shots are full-page captures (1920x9000+). The
 * before/after comparison needs both halves framed identically, so crop the
 * above-the-fold region of each build to the same 16:10 frame the "before"
 * placeholders use. Regenerate after replacing any *_whole1.jpg.
 */
$slugs = ['paradise', 'palesso', 'kepszakadas', 'juiced'];
$RATIO = 1600 / 1000;
$OUT_W = 1600;
$OUT_H = 1000;

foreach ($slugs as $slug) {
    $src = "public/assets/imgs/websites/{$slug}/{$slug}_whole1.jpg";
    if (!file_exists($src)) { echo "skip {$slug}\n"; continue; }

    $img = imagecreatefromjpeg($src);
    $w = imagesx($img);
    $h = imagesy($img);

    $cropH = min($h, (int) round($w / $RATIO));

    $out = imagecreatetruecolor($OUT_W, $OUT_H);
    imagecopyresampled($out, $img, 0, 0, 0, 0, $OUT_W, $OUT_H, $w, $cropH);

    $dest = "public/assets/imgs/websites/{$slug}/{$slug}_after.jpg";
    imagejpeg($out, $dest, 88);
    imagedestroy($out);
    imagedestroy($img);
    echo "wrote {$dest} (cropped {$w}x{$cropH} of {$w}x{$h})\n";
}

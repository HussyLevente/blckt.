<?php

namespace App\Support;

/**
 * Kepmeretek a lemezrol.
 *
 * A width/height attributumok nem diszek: ezek nelkul a bongeszo nem tudja,
 * mennyi helyet foglaljon le a kepnek, ezert a szoveg megugrik, amikor a kep
 * megerkezik (layout shift). A meretet a fajlbol olvassuk ki, hogy ne kelljen
 * kezzel karbantartani - a getimagesize csak a fejlecet olvassa, nem a
 * teljes fajlt, es keresenkent egyszer futunk le fajlonkent.
 */
class Media
{
    /** @var array<string, array{0:int,1:int}|null> */
    private static array $cache = [];

    /**
     * @return array{0:int,1:int}|null  [szelesseg, magassag]
     */
    public static function dimensions(?string $relative): ?array
    {
        if (! $relative) {
            return null;
        }

        $key = ltrim($relative, '/');

        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        $path = public_path($key);

        // A getimagesize hibat dob nem kepre; a @ helyett elore ellenorzunk.
        if (! is_file($path)) {
            return self::$cache[$key] = null;
        }

        $size = @getimagesize($path);

        return self::$cache[$key] = ($size && $size[0] > 0 && $size[1] > 0)
            ? [$size[0], $size[1]]
            : null;
    }

    /**
     * Kesz attributum-parost ad vissza a sablonnak, vagy ures sztringet,
     * ha a meret nem allapithato meg.
     */
    public static function sizeAttrs(?string $relative): string
    {
        $d = self::dimensions($relative);

        return $d ? sprintf('width="%d" height="%d"', $d[0], $d[1]) : '';
    }
}

<?php

namespace App\Support;

/**
 * Eszkoz-URL-ek.
 *
 * Ket dolgot intez:
 *   1. Ha letezik a tomoritett valtozat (assets:build), eles uzemben azt
 *      adja vissza. Fejlesztes kozben (APP_DEBUG=true) szandekosan az
 *      eredetit - kulonben minden CSS-modositas utan ujra kellene forditani,
 *      es konnyen a regi fajlt hinne az ember elonek.
 *   2. Verzio-parameter a fajl modositasi idejebol. Enelkul a hosszu
 *      cache-eles veszelyes lenne: a visszatero latogato a regi CSS-t
 *      kapna. Ezzel viszont batran allithato egy eves lejarat.
 */
class Asset
{
    /** @var array<string, string> */
    private static array $cache = [];

    public static function url(string $relative): string
    {
        $key = ltrim($relative, '/');

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $path = $key;

        if (! config('app.debug')) {
            $minified = preg_replace('#\.(css|js)$#', '.min.$1', $key);

            if ($minified !== $key && is_file(public_path($minified))) {
                $path = $minified;
            }
        }

        $full = public_path($path);
        $version = is_file($full) ? filemtime($full) : null;

        return self::$cache[$key] = asset($path).($version ? '?v='.$version : '');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['en', 'hu'];

    public const DEFAULT_LOCALE = 'en';

    /**
     * Sorrend: ?lang= parameter, majd a munkamenet, vegul a bongeszo nyelve.
     *
     * A query parameter azert all elol, mert a nyelvvaltas egyebkent csak a
     * munkamenetben elne - a keresok minden oldalt egyetlen nyelven latnanak,
     * es nem lenne mire mutatnia a hreflang hivatkozasoknak. Igy viszont
     * mindket nyelvnek van sajat, bejarhato cime.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang');

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = $request->session()->get('locale');
        }

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = $request->getPreferredLanguage(self::SUPPORTED_LOCALES) ?? self::DEFAULT_LOCALE;
        }

        App::setLocale($locale);

        return $next($request);
    }
}

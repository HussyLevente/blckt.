<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Ket nyelvu sitemap.
     *
     * Minden cim mindket nyelven szerepel, es minden bejegyzes felsorolja a
     * sajat nyelvi valtozatait xhtml:link alternate-kent - ez az, amibol a
     * kereso megerti, hogy ugyanannak az oldalnak ket verzioja van, nem ket
     * kulon oldal.
     */
    public function index(WebsiteProjectController $websites, TemplateController $templates, PlaygroundController $playground): Response
    {
        $pages = [
            ['path' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['path' => '/websites', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => '/templates', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => '/playground', 'priority' => '0.7'],
            ['path' => '/services', 'priority' => '0.9'],
            ['path' => '/about', 'priority' => '0.6'],
            ['path' => '/contact', 'priority' => '0.7'],
            ['path' => '/impresszum', 'priority' => '0.2', 'changefreq' => 'yearly'],
            ['path' => '/adatvedelem', 'priority' => '0.2', 'changefreq' => 'yearly'],
            ['path' => '/aszf', 'priority' => '0.2', 'changefreq' => 'yearly'],
        ];

        foreach ($websites->slugs() as $slug) {
            $pages[] = ['path' => '/websites/'.$slug, 'priority' => '0.8'];
        }

        foreach ($templates->slugs() as $slug) {
            $pages[] = ['path' => '/templates/'.$slug, 'priority' => '0.8'];
        }

        // A demok maguk noindex-ek (kitalalt vallalkozasok), a
        // playground viszont sajat lap sajat tartalommal - az mehet be.
        foreach ($playground->slugs() as $slug) {
            $pages[] = ['path' => '/playground/'.$slug, 'priority' => '0.6'];
        }

        $lastmod = now()->toDateString();
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">',
        ];

        foreach ($pages as $page) {
            foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
                $lines[] = '  <url>';
                $lines[] = '    <loc>'.e($this->urlFor($page['path'], $locale)).'</loc>';

                foreach (SetLocale::SUPPORTED_LOCALES as $alt) {
                    $lines[] = '    <xhtml:link rel="alternate" hreflang="'.$alt.'" href="'.e($this->urlFor($page['path'], $alt)).'"/>';
                }

                $lines[] = '    <xhtml:link rel="alternate" hreflang="x-default" href="'.e($this->urlFor($page['path'], SetLocale::DEFAULT_LOCALE)).'"/>';
                $lines[] = '    <lastmod>'.$lastmod.'</lastmod>';
                $lines[] = '    <changefreq>'.($page['changefreq'] ?? 'monthly').'</changefreq>';
                $lines[] = '    <priority>'.$page['priority'].'</priority>';
                $lines[] = '  </url>';
            }
        }

        $lines[] = '</urlset>';

        return response(implode("\n", $lines), 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Az alapertelmezett nyelv a tiszta cimen el, a masodik ?lang= parameterrel.
     */
    private function urlFor(string $path, string $locale): string
    {
        $url = url($path);

        return $locale === SetLocale::DEFAULT_LOCALE ? $url : $url.'?lang='.$locale;
    }
}

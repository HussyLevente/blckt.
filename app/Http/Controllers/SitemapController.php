<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(ClothingProductController $clothing, WebsiteProjectController $websites): Response
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => url('/clothing'), 'priority' => '0.8'],
            ['loc' => route('clothing.collection'), 'priority' => '0.8'],
            ['loc' => url('/websites'), 'priority' => '0.9'],
            ['loc' => route('websites.redesigns'), 'priority' => '0.9'],
            ['loc' => route('services'), 'priority' => '0.8'],
            ['loc' => url('/about'), 'priority' => '0.6'],
            ['loc' => url('/contact'), 'priority' => '0.7'],
            ['loc' => route('legal.impresszum'), 'priority' => '0.2'],
            ['loc' => route('legal.adatvedelem'), 'priority' => '0.2'],
            ['loc' => route('legal.aszf'), 'priority' => '0.2', 'changefreq' => 'yearly'],
        ];

        foreach ($clothing->slugs() as $slug) {
            $urls[] = ['loc' => route('clothing.show', $slug), 'priority' => '0.6'];
        }

        foreach ($websites->slugs() as $slug) {
            $urls[] = ['loc' => route('websites.show', $slug), 'priority' => '0.7'];
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $lastmod = now()->toDateString();

        foreach ($urls as $entry) {
            $node = $xml->addChild('url');
            $node->addChild('loc', htmlspecialchars($entry['loc']));
            $node->addChild('lastmod', $lastmod);
            $node->addChild('changefreq', $entry['changefreq'] ?? 'monthly');
            $node->addChild('priority', $entry['priority']);
        }

        return response($xml->asXML(), 200, ['Content-Type' => 'application/xml']);
    }
}

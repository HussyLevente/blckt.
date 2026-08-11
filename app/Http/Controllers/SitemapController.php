<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(ClothingProductController $clothing, WebsiteProjectController $websites): Response
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0'],
            ['loc' => url('/clothing'), 'priority' => '0.8'],
            ['loc' => route('clothing.collection'), 'priority' => '0.8'],
            ['loc' => url('/websites'), 'priority' => '0.9'],
            ['loc' => url('/about'), 'priority' => '0.6'],
            ['loc' => url('/contact'), 'priority' => '0.7'],
        ];

        foreach ($clothing->slugs() as $slug) {
            $urls[] = ['loc' => route('clothing.show', $slug), 'priority' => '0.6'];
        }

        foreach ($websites->slugs() as $slug) {
            $urls[] = ['loc' => route('websites.show', $slug), 'priority' => '0.7'];
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        foreach ($urls as $entry) {
            $node = $xml->addChild('url');
            $node->addChild('loc', htmlspecialchars($entry['loc']));
            $node->addChild('priority', $entry['priority']);
        }

        return response($xml->asXML(), 200, ['Content-Type' => 'application/xml']);
    }
}

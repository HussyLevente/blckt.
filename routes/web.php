<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PlaygroundController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WebsiteProjectController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// A cimlap a ket legutobbi weboldalt es a sablonokat emeli ki, ezert
// mindket kontrollerbol kell hozza adat.
Route::get('/', fn (WebsiteProjectController $websites, TemplateController $templates) => view('home', [
    'featured' => $websites->featured(2),
    'liveCount' => $websites->liveCount(),
    'templates' => $templates->featured(),
    'templateFloor' => $templates->floor(),
    'templateCount' => $templates->count(),
    'templateDays' => $templates->fastest(),
]));

Route::get('/websites', [WebsiteProjectController::class, 'index'])->name('websites.index');
Route::get('/websites/{project}', [WebsiteProjectController::class, 'show'])->name('websites.show');

// A kulon before/after oldal megszunt - az osszehasonlitas a projektoldalakon
// belul el. A regi cim atiranyitaskent marad, hogy a mar indexelt URL-ek es a
// kimeno linkek ne 404-eljenek.
Route::permanentRedirect('/redesigns', '/websites');
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');

// A mentett lista a latogato bongeszojeben el, ezert a lap csak a
// kartyakat teriti ki - a valogatast a saved.js vegzi. A sitemapbol
// kimarad: latogatonkent mas, es nincs kozos tartalma.
Route::get('/saved', fn (TemplateController $templates) => view('saved', [
    'templates' => $templates->all(),
]))->name('saved');

// A playground a demokra ul ra, nem a sablonokra: ott a SAJAT szoveget es
// fotoit teheti bele a latogato. Minden a bongeszojeben marad - ez az
// utvonal csak a lapot adja ki, adatot nem vesz at es nem tarol.
Route::get('/playground', [PlaygroundController::class, 'index'])->name('playground.index');
Route::get('/playground/{demo}', [PlaygroundController::class, 'show'])->name('playground.show');
// Az arak oldala a sablonok also hatarat is kiirja, ezert mar nem
// eleg egy Route::view - az erteknek a kontrollerbol kell jonnie, hogy
// egy arvaltozas ne hagyjon hazug szamot ezen a lapon.
Route::get('/services', fn (TemplateController $templates) => view('services', [
    'templateFloor' => $templates->floor(),
]))->name('services');
Route::view('/about', 'about');
Route::view('/contact', 'contact');

Route::view('/impresszum', 'legal.impresszum')->name('legal.impresszum');
Route::view('/adatvedelem', 'legal.adatvedelem')->name('legal.adatvedelem');
Route::view('/aszf', 'legal.aszf')->name('legal.aszf');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');

Route::get('/lang/{locale}', function (\Illuminate\Http\Request $request, string $locale) {
    abort_unless(in_array($locale, ['en', 'hu'], true), 404);
    $request->session()->put('locale', $locale);

    return redirect()->back();
})->name('lang.switch');

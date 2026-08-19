<?php

use App\Http\Controllers\ClothingProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebsiteProjectController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::view('/', 'home');
Route::view('/clothing', 'clothing');
Route::get('/clothing/collection', [ClothingProductController::class, 'index'])->name('clothing.collection');
Route::get('/clothing/collection/{product}', [ClothingProductController::class, 'show'])->name('clothing.show');
Route::view('/websites', 'websites');
Route::get('/websites/{project}', [WebsiteProjectController::class, 'show'])->name('websites.show');
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

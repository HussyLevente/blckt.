<?php

use App\Http\Controllers\WebsiteProjectController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::view('/', 'home');
Route::view('/clothing', 'clothing');
Route::view('/websites', 'websites');
Route::get('/websites/{project}', [WebsiteProjectController::class, 'show'])->name('websites.show');
Route::view('/about', 'about');
Route::view('/contact', 'contact');

Route::get('/lang/{locale}', function (\Illuminate\Http\Request $request, string $locale) {
    abort_unless(in_array($locale, ['en', 'hu'], true), 404);
    $request->session()->put('locale', $locale);

    return redirect()->back();
})->name('lang.switch');

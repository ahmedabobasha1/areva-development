<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::redirect('/', '/'.config('areva.default_locale', 'en'));

Route::prefix('{locale}')
    ->whereIn('locale', config('areva.locales', ['en', 'ar']))
    ->middleware('locale')
    ->group(function () {
        Route::get('/', HomeController::class)->name('home');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/blog/{article}', [ArticleController::class, 'show'])->name('articles.show');
        Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    });

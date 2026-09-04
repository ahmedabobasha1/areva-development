<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/'.config('areva.default_locale', 'en'));

Route::prefix('{locale}')
    ->whereIn('locale', config('areva.locales', ['en', 'ar']))
    ->middleware('locale')
    ->group(function () {
        Route::get('/', HomeController::class)->name('home');
        Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/blog/{slug}', [ArticleController::class, 'show'])->name('articles.show');
        Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    });

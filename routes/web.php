<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

Route::prefix('{locale}')
    ->whereIn('locale', ['en', 'ar'])
    ->group(function () {
        Route::get('/', HomeController::class)->name('home');
        Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/blog/{slug}', [ArticleController::class, 'show'])->name('articles.show');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    });

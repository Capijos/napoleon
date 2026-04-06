<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/producto/{id}', [ProductController::class, 'show'])
    ->name('producto.show');

Route::get('/categoria/{slug}', [CategoryController::class, 'show'])
    ->name('categoria.show');

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

Route::get('/search/modal', [SearchController::class, 'modal'])
    ->name('search.modal');

Route::get('/login', function () {
    return view('home');
})->name('login');
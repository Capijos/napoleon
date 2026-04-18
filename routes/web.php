<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout/whatsapp', [CartController::class, 'sendToWhatsApp'])->name('checkout.whatsapp');

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
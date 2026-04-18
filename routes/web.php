<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use App\Models\Product;

Route::get('/', function () {
    $featuredShopifyIds = [
        8222709383283,
        8222739267699,
        8220100722803,
        7900244902003,
    ];

    $homeCartProducts = Product::whereIn('shopify_id', $featuredShopifyIds)
        ->get()
        ->mapWithKeys(function (Product $product) {
            $variant = $product->default_variant;

            return [(string) $product->shopify_id => [
                'product_id' => (string) $product->getKey(),
                'variant_id' => isset($variant['variant_id']) && $variant['variant_id'] !== '' ? (string) $variant['variant_id'] : null,
                'url' => route('producto.show', $product->getKey()),
                'available' => (bool) $product->is_available,
            ]];
        })
        ->all();

    return view('home', [
        'homeCartProducts' => $homeCartProducts,
    ]);
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

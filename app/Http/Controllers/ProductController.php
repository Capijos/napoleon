<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['category', 'promotions'])
            ->where('status', 'active')
            ->findOrFail($id);

        $now = Carbon::now();

        $finalPrice = (float) $product->price;
        $activePromotion = null;

        foreach ($product->promotions as $promotion) {
            $isWithinDate =
                (!$promotion->starts_at || $promotion->starts_at <= $now) &&
                (!$promotion->ends_at || $promotion->ends_at >= $now);

            if ($promotion->is_active && $isWithinDate) {
                $activePromotion = $promotion;

                if ($promotion->type === 'percentage') {
                    $finalPrice = $finalPrice - ($finalPrice * ((float) $promotion->value / 100));
                } elseif ($promotion->type === 'fixed') {
                    $finalPrice = $finalPrice - (float) $promotion->value;
                }

                break;
            }
        }

        $finalPrice = max($finalPrice, 0);

        $gallery = [];

        if (!empty($product->main_image)) {
            $gallery[] = $product->main_image;
        }

        if (is_array($product->gallery)) {
            foreach ($product->gallery as $image) {
                if ($image && !in_array($image, $gallery)) {
                    $gallery[] = $image;
                }
            }
        }

        $stockStatus = 'Agotado';
        if ($product->stock > 0) {
            $stockStatus = 'En Inventario';
        }

        $lowStockMessage = null;
        if ($product->stock > 0 && $product->stock <= 3) {
            $lowStockMessage = "¡Apúrate! Solo queda(n) {$product->stock} en inventario.";
        }

        $relatedProducts = Product::with('category')
            ->where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        $breadcrumbs = [
            [
                'label' => 'Inicio',
                'url' => url('/'),
            ],
            [
                'label' => $product->name,
                'url' => null,
            ],
        ];

        $specifications = [
            'SKU' => $product->sku,
            'Marca' => $product->brand,
            'Peso' => $product->weight ? $product->weight . ' g' : null,
            'Categoría' => optional($product->category)->name,
        ];

        $specifications = array_filter($specifications, fn ($value) => !is_null($value) && $value !== '');

        return view('product.show', [
            'product' => $product,
            'finalPrice' => $finalPrice,
            'activePromotion' => $activePromotion,
            'gallery' => $gallery,
            'stockStatus' => $stockStatus,
            'lowStockMessage' => $lowStockMessage,
            'relatedProducts' => $relatedProducts,
            'breadcrumbs' => $breadcrumbs,
            'specifications' => $specifications,
        ]);
    }
}
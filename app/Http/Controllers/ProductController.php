<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::where('status', 'active')
            ->findOrFail($id);

        $defaultVariant = $product->default_variant;
        $basePrice = (float) ($defaultVariant['price'] ?? $product->min_price ?? 0);
        $finalPrice = $basePrice;

        $gallery = [];

        if (!empty($product->main_image)) {
            $gallery[] = $product->main_image;
        }

        if (is_array($product->images)) {
            foreach ($product->images as $image) {
                $src = $image['src'] ?? null;

                if ($src && !in_array($src, $gallery, true)) {
                    $gallery[] = $src;
                }
            }
        }

        $availableVariants = collect($product->variants ?? [])
            ->filter(fn ($variant) => is_array($variant) && (($variant['available'] ?? false) === true))
            ->values();

        $stockCount = $availableVariants->count();
        $stockStatus = $stockCount > 0 ? 'En Inventario' : 'Agotado';

        $lowStockMessage = null;
        if ($stockCount > 0 && $stockCount <= 3) {
            $lowStockMessage = "¡Apúrate! Solo queda(n) {$stockCount} variante(s) disponible(s).";
        }

        $firstCategorySlug = $product->category_slugs[0] ?? null;
        $firstCategoryName = $product->category_names[0] ?? null;

        $relatedProductsQuery = Product::where('status', 'active')
            ->where('_id', '!=', $product->_id);

        if ($firstCategorySlug) {
            $relatedProductsQuery->where('category_slugs', $firstCategorySlug);
        }

        $relatedProducts = $relatedProductsQuery
            ->latest()
            ->take(4)
            ->get();

        $breadcrumbs = [
            [
                'label' => 'Inicio',
                'url' => url('/'),
            ],
        ];

        if ($firstCategoryName && $firstCategorySlug) {
            $breadcrumbs[] = [
                'label' => $firstCategoryName,
                'url' => route('categoria.show', $firstCategorySlug),
            ];
        }

        $breadcrumbs[] = [
            'label' => $product->name,
            'url' => null,
        ];

        $specifications = array_filter([
            'SKU' => $defaultVariant['sku'] ?? null,
            'Marca' => $product->brand,
            'Material' => $product->material,
            'Color' => $product->color,
            'Peso' => $product->weight ? $product->weight . ' g' : null,
            'Largo' => $product->length ? $product->length . ' cm' : null,
            'Grosor' => $product->thickness ? $product->thickness . ' mm' : null,
            'Categoría' => $firstCategoryName,
        ], fn ($value) => !is_null($value) && $value !== '');

        return view('product.show', [
            'product' => $product,
            'finalPrice' => $finalPrice,
            'basePrice' => $basePrice,
            'activePromotion' => null,
            'gallery' => $gallery,
            'stockStatus' => $stockStatus,
            'lowStockMessage' => $lowStockMessage,
            'relatedProducts' => $relatedProducts,
            'breadcrumbs' => $breadcrumbs,
            'specifications' => $specifications,
            'defaultVariant' => $defaultVariant,
            'availableVariants' => $availableVariants,
        ]);
    }
}
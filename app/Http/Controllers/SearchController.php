<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function modal(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $trending = [
            'Topos',
            'Pulso',
            'Candongas',
            'Oferta',
            'Dije',
            'Cadena',
            'Pulsera',
            'Anillo',
            'Piercing',
        ];

        $products = Product::query()
            ->where('status', 'active')
            ->latest()
            ->take($q !== '' ? 60 : 8)
            ->get()
            ->map(function ($product) {
                return $this->normalizeProduct($product);
            });

        if ($q !== '') {
            $needle = mb_strtolower($q);

            $products = $products->filter(function ($product) use ($needle) {
                $fields = [
                    $product->name ?? '',
                    $product->slug ?? '',
                    $product->short_description ?? '',
                    $product->description ?? '',
                    $product->brand ?? '',
                    $product->material ?? '',
                    $product->color ?? '',
                    implode(' ', $product->category_names ?? []),
                    implode(' ', $product->category_slugs ?? []),
                ];

                foreach ($product->variants ?? [] as $variant) {
                    if (is_array($variant)) {
                        $fields[] = $variant['sku'] ?? '';
                        $fields[] = $variant['title'] ?? '';
                        $fields[] = implode(' ', $variant['options'] ?? []);
                    }
                }

                $haystack = mb_strtolower(implode(' ', array_filter($fields)));

                return str_contains($haystack, $needle);
            })->take(8)->values();
        } else {
            $products = $products->take(8)->values();
        }

        return view('partials.search-results', [
            'products' => $products,
            'trending' => $trending,
            'query' => $q,
        ]);
    }

    /**
     * Normaliza campos que a veces vienen como string JSON.
     */
    private function normalizeProduct(Product $product): Product
    {
        $jsonFields = [
            'category_ids',
            'category_slugs',
            'category_names',
            'promotion_ids',
            'variants',
            'images',
            'badge_labels',
            'status_badges',
            'raw_product',
        ];

        foreach ($jsonFields as $field) {
            $value = $product->{$field} ?? null;

            if (is_string($value)) {
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $product->{$field} = $decoded;
                }
            }
        }

        if (!is_array($product->category_ids ?? null)) {
            $product->category_ids = [];
        }

        if (!is_array($product->category_slugs ?? null)) {
            $product->category_slugs = [];
        }

        if (!is_array($product->category_names ?? null)) {
            $product->category_names = [];
        }

        if (!is_array($product->variants ?? null)) {
            $product->variants = [];
        }

        if (!is_array($product->images ?? null)) {
            $product->images = [];
        }

        if (!is_array($product->badge_labels ?? null)) {
            $product->badge_labels = [];
        }

        if (!is_array($product->status_badges ?? null)) {
            $product->status_badges = [];
        }

        return $product;
    }
}
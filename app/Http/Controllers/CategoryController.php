<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $sortBy = $request->get('sort_by', 'created-descending');
        $perPage = 50;
        $page = max((int) $request->get('page', 1), 1);

        $products = Product::where('status', 'active')
            ->get()
            ->map(function ($product) {
                return $this->normalizeProduct($product);
            })
            ->filter(function ($product) use ($category) {
                $categorySlugs = $product->category_slugs ?? [];

                if (!is_array($categorySlugs)) {
                    return false;
                }

                return in_array($category->slug, $categorySlugs, true);
            })
            ->values();

        $products = $products->sort(function ($a, $b) use ($sortBy) {
            $priceA = collect($a->variants ?? [])->min('price') ?? 0;
            $priceB = collect($b->variants ?? [])->min('price') ?? 0;

            switch ($sortBy) {
                case 'title-ascending':
                    return strcmp((string) ($a->name ?? ''), (string) ($b->name ?? ''));

                case 'title-descending':
                    return strcmp((string) ($b->name ?? ''), (string) ($a->name ?? ''));

                case 'price-ascending':
                    return $priceA <=> $priceB;

                case 'price-descending':
                    return $priceB <=> $priceA;

                case 'created-ascending':
                    return strtotime((string) ($a->created_at ?? '')) <=> strtotime((string) ($b->created_at ?? ''));

                case 'created-descending':
                default:
                    return strtotime((string) ($b->created_at ?? '')) <=> strtotime((string) ($a->created_at ?? ''));
            }
        })->values();

        $paginated = new LengthAwarePaginator(
            $products->forPage($page, $perPage)->values(),
            $products->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        if ($request->ajax()) {
            return response()->json([
                'html' => view('category.partials.products', [
                    'products' => $paginated,
                ])->render(),
                'next_page_url' => $paginated->nextPageUrl(),
                'has_more' => $paginated->hasMorePages(),
                'products' => $paginated->items(),
            ]);
        }

        return view('category.show', [
            'category' => $category,
            'products' => $paginated,
            'sortBy' => $sortBy,
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
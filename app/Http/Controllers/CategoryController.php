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
        $page    = max((int) $request->get('page', 1), 1);

        $query = Product::where('status', 'active')
            ->where(function ($q) use ($category) {
                // Cubre tanto array nativo como string JSON guardado mal
                $q->where('category_slugs', $category->slug)
                  ->orWhere('category_slugs', 'like', '%"' . $category->slug . '"%');
            });

        // Sin ->select() por ahora — puede interferir con el cast del modelo
        // $query->select([...]);  ← comentado hasta confirmar que funciona

        $needsPriceSort = in_array($sortBy, ['price-ascending', 'price-descending']);

        if (!$needsPriceSort) {
            switch ($sortBy) {
                case 'title-ascending':
                    $query->orderBy('name', 'asc');
                    break;
                case 'title-descending':
                    $query->orderBy('name', 'desc');
                    break;
                case 'created-ascending':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created-descending':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        if ($needsPriceSort) {
            $products = $query->get()
                ->map(fn($p) => $this->normalizeProduct($p))
                ->sort(function ($a, $b) use ($sortBy) {
                    $priceA = collect($a->variants ?? [])->min('price') ?? 0;
                    $priceB = collect($b->variants ?? [])->min('price') ?? 0;
                    return $sortBy === 'price-ascending'
                        ? $priceA <=> $priceB
                        : $priceB <=> $priceA;
                })->values();

            $total = $products->count();
            $items = $products->forPage($page, $perPage)->values();
        } else {
            $total = (clone $query)->count();
            $items = $query
                ->skip(($page - 1) * $perPage)   // ← skip/limit explícito
                ->limit($perPage)                  //   más confiable que forPage()
                ->get()
                ->map(fn($p) => $this->normalizeProduct($p))
                ->values();
        }

        $paginated = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        if ($request->ajax()) {
            return response()->json([
                'html'          => view('category.partials.products', ['products' => $paginated])->render(),
                'next_page_url' => $paginated->nextPageUrl(),
                'has_more'      => $paginated->hasMorePages(),
                'products'      => $paginated->items(),
            ]);
        }

        return view('category.show', [
            'category' => $category,
            'products' => $paginated,
            'sortBy'   => $sortBy,
        ]);
    }

    private function normalizeProduct(Product $product): Product
    {
        $jsonFields = [
            'category_ids', 'category_slugs', 'category_names',
            'promotion_ids', 'variants', 'images',
            'badge_labels', 'status_badges',
        ];

        foreach ($jsonFields as $field) {
            $value = $product->{$field} ?? null;
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $product->{$field} = $decoded;
                }
            }
            if (!is_array($product->{$field} ?? null)) {
                $product->{$field} = [];
            }
        }

        return $product;
    }
}
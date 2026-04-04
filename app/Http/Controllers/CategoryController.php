<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $sortBy = $request->get('sort_by', 'created-descending');

        $query = Product::with('variants')
            ->where('category_id', $category->id)
            ->where('status', 'active');

        switch ($sortBy) {
            case 'manual':
                $query->orderBy('id', 'asc');
                break;

            case 'best-selling':
                // cambia esto si tienes una columna real como sales_count o sold_count
                $query->orderBy('id', 'desc');
                break;

            case 'title-ascending':
                $query->orderBy('name', 'asc');
                break;

            case 'title-descending':
                $query->orderBy('name', 'desc');
                break;

            case 'price-ascending':
                $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->select('products.*')
                    ->orderBy('product_variants.price', 'asc');
                break;

            case 'price-descending':
                $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->select('products.*')
                    ->orderBy('product_variants.price', 'desc');
                break;

            case 'created-ascending':
                $query->orderBy('created_at', 'asc');
                break;

            case 'created-descending':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(50)->appends([
            'sort_by' => $sortBy,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('category.partials.products', compact('products'))->render(),
                'next_page_url' => $products->nextPageUrl(),
                'has_more' => $products->hasMorePages(),
                'products' => $products->items(),
            ]);
        }

        return view('category.show', compact('category', 'products', 'sortBy'));
    }
}
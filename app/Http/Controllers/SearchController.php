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

        $products = collect();

        if ($q !== '') {
            $products = Product::query()
                ->where('status', 'active')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhere('short_description', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                })
                ->select([
                    'id',
                    'name',
                    'slug',
                    'price',
                    'compare_price',
                    'main_image',
                    'status',
                ])
                ->latest()
                ->take(8)
                ->get();
        } else {
            $products = Product::query()
                ->where('status', 'active')
                ->select([
                    'id',
                    'name',
                    'slug',
                    'price',
                    'compare_price',
                    'main_image',
                    'status',
                ])
                ->latest()
                ->take(8)
                ->get();
        }

        return view('partials.search-results', [
            'products' => $products,
            'trending' => $trending,
            'query' => $q,
        ]);
    }
}

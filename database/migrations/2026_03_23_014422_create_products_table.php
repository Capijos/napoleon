<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductsMongoSeeder extends Seeder
{
    public function run(): void
    {
        DB::collection('products')->insert([
            [
                '_id' => 'prod_1',
                'category_id' => 'cat_2', // referencia a la categoría
                'shopify_id' => 123456789,
                'shopify_handle' => 'iphone-14',
                'shopify_url' => 'https://example.com/iphone-14',
                'name' => 'iPhone 14',
                'slug' => 'iphone-14',
                'description' => 'Latest Apple iPhone 14',
                'short_description' => 'Apple iPhone 14 Smartphone',
                'brand' => 'Apple',
                'material' => null,
                'color' => 'Black',
                'weight' => 0.5,
                'length' => 15.0,
                'thickness' => 0.8,
                'main_image' => 'https://example.com/images/iphone14.jpg',
                'status' => 'active',
                'is_featured' => true,
                'meta_title' => 'iPhone 14 - Apple',
                'meta_description' => 'Buy the latest iPhone 14',
                'badge_labels' => ['new', 'bestseller'],
                'status_badges' => ['active'],
                'raw_product' => ['shopify' => ['variant_count' => 3]],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Puedes agregar más productos aquí
        ]);

        // Crear índice único en slug si lo deseas
        DB::collection('products')->raw(function ($collection) {
            $collection->createIndex(['slug' => 1], ['unique' => true]);
        });
    }
}
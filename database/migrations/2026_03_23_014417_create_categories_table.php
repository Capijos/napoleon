<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriesMongoSeeder extends Seeder
{
    public function run(): void
    {
        // Insertamos algunas categorías de ejemplo
        DB::collection('categories')->insert([
            [
                '_id' => 'cat_1',
                'parent_id' => null,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic products',
                'image' => null,
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                '_id' => 'cat_2',
                'parent_id' => 'cat_1',
                'name' => 'Mobile Phones',
                'slug' => 'mobile-phones',
                'description' => 'Smartphones and mobile devices',
                'image' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
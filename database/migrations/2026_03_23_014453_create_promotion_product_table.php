<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionProductMongoSeeder extends Seeder
{
    public function run(): void
    {
        // Agregar IDs de promociones al documento del producto
        DB::collection('products')->where('_id', 'product_1')->update([
            'promotion_ids' => ['promo_1', 'promo_2'], // array de IDs de promociones
            'updated_at' => Carbon::now(),
        ]);

        // Alternativamente, agregar IDs de productos al documento de promoción
        DB::collection('promotions')->where('_id', 'promo_1')->update([
            'product_ids' => ['product_1', 'product_2'], // array de IDs de productos
            'updated_at' => Carbon::now(),
        ]);
    }
}
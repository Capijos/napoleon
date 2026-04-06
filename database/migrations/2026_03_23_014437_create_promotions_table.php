<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionsMongoSeeder extends Seeder
{
    public function run(): void
    {
        DB::collection('promotions')->insert([
            [
                '_id' => 'promo_1',
                'name' => 'Summer Sale',
                'slug' => 'summer-sale',
                'description' => 'Discount for summer products',
                'type' => 'percentage', // 'percentage' o 'fixed'
                'value' => 15.0,
                'coupon_code' => 'SUMMER15',
                'minimum_amount' => 100.0,
                'usage_limit' => 100,
                'used_count' => 0,
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addMonth(),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Puedes agregar más promociones aquí
        ]);

        // Índices únicos en slug y coupon_code
        DB::collection('promotions')->raw(function ($collection) {
            $collection->createIndex(['slug' => 1], ['unique' => true]);
            $collection->createIndex(['coupon_code' => 1], ['unique' => true, 'sparse' => true]);
        });
    }
}
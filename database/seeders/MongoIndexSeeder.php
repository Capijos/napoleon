<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MongoIndexSeeder extends Seeder
{
    public function run(): void
    {
        // PRODUCTS
        DB::connection('mongodb')->getMongoDB()
            ->products
            ->createIndex(['slug' => 1], ['unique' => true]);

        DB::connection('mongodb')->getMongoDB()
            ->products
            ->createIndex(['shopify_id' => 1], ['unique' => true]);

        DB::connection('mongodb')->getMongoDB()
            ->products
            ->createIndex(['status' => 1]);

        DB::connection('mongodb')->getMongoDB()
            ->products
            ->createIndex(['category_ids' => 1]);

        // CATEGORIES
        DB::connection('mongodb')->getMongoDB()
            ->categories
            ->createIndex(['slug' => 1], ['unique' => true]);

        DB::connection('mongodb')->getMongoDB()
            ->categories
            ->createIndex(['path' => 1]);

        // USERS
        DB::connection('mongodb')->getMongoDB()
            ->users
            ->createIndex(['email' => 1], ['unique' => true]);

        // CARTS
        DB::connection('mongodb')->getMongoDB()
            ->carts
            ->createIndex(
                ['user_id' => 1, 'status' => 1],
                ['unique' => true, 'partialFilterExpression' => ['status' => 'active']]
            );

        // ORDERS
        DB::connection('mongodb')->getMongoDB()
            ->orders
            ->createIndex(['order_number' => 1], ['unique' => true]);

        // PROMOTIONS
        DB::connection('mongodb')->getMongoDB()
            ->promotions
            ->createIndex(['coupon_code' => 1], ['unique' => true]);
    }
}
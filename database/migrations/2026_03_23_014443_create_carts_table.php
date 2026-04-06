<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartsMongoSeeder extends Seeder
{
    public function run(): void
    {
        DB::collection('carts')->insert([
            [
                '_id' => 'cart_1', // Puedes usar ObjectId automáticamente si omites este campo
                'user_id' => 'user_1', // referencia al usuario (no FK)
                'status' => 'active', // posible valores: active, converted, abandoned
                'subtotal' => 0.00,
                'discount' => 0.00,
                'shipping_cost' => 0.00,
                'tax' => 0.00,
                'total' => 0.00,
                'currency' => 'PEN',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Puedes agregar más documentos aquí
        ]);
    }
}
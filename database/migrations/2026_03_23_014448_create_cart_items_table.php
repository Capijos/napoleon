<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartItemsMongoSeeder extends Seeder
{
    public function run(): void
    {
        DB::collection('cart_items')->insert([
            [
                '_id' => 'item_1', // opcional, MongoDB genera ObjectId automáticamente
                'cart_id' => 'cart_1', // referencia al cart
                'product_id' => 'product_1', // referencia al producto
                'product_name' => 'Nombre del producto',
                'product_sku' => 'SKU123',
                'product_image' => 'imagen.jpg',
                'quantity' => 1,
                'unit_price' => 100.00,
                'subtotal' => 100.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Puedes agregar más documentos aquí
        ]);
    }
}
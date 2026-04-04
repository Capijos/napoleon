<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // USERS
        // =========================
        $user1 = DB::table('users')->insertGetId([
            'first_name' => 'Jose',
            'last_name' => 'Tito',
            'email' => 'jose@test.com',
            'password' => Hash::make('123456'),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user2 = DB::table('users')->insertGetId([
            'first_name' => 'Maria',
            'last_name' => 'Lopez',
            'email' => 'maria@test.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user3 = DB::table('users')->insertGetId([
            'first_name' => 'Carlos',
            'last_name' => 'Perez',
            'email' => 'carlos@test.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // =========================
        // CATEGORY
        // =========================
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Pulseras',
            'slug' => 'pulseras',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // =========================
        // PRODUCTS + VARIANTS + IMAGES
        // =========================
        $productIds = [];
        $variantMap = [];

        for ($i = 1; $i <= 10; $i++) {
            $productId = DB::table('products')->insertGetId([
                'category_id' => $categoryId,
                'shopify_id' => 1000000000000 + $i,
                'shopify_handle' => "producto-$i",
                'shopify_url' => "/products/producto-$i",

                'name' => "Producto $i",
                'slug' => "producto-$i",

                'description' => "Descripción completa del Producto $i",
                'short_description' => "Resumen corto del Producto $i",

                'brand' => 'Napoleone',
                'material' => 'Oro 18K',
                'color' => 'Oro Amarillo',

                'weight' => rand(1, 10),
                'length' => rand(15, 25),
                'thickness' => rand(1, 3),

                'main_image' => "https://via.placeholder.com/600x600?text=Producto+$i",

                'status' => 'active',
                'is_featured' => $i <= 3,

                'meta_title' => "Producto $i",
                'meta_description' => "Meta descripción del Producto $i",

                'badge_labels' => json_encode(['Santafe', 'It +3']),
                'status_badges' => json_encode(['Nuevo']),
                'raw_product' => json_encode([
                    'dummy' => true,
                    'source' => 'seeder',
                    'product_number' => $i,
                ]),

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $productIds[] = $productId;

            // Variante principal del producto
            $variantId = DB::table('product_variants')->insertGetId([
                'product_id' => $productId,
                'shopify_variant_id' => 2000000000000 + $i,
                'title' => "Variante Producto $i",
                'sku' => "SKU$i",
                'price' => rand(100, 500) * 1000,
                'compare_price' => null,
                'stock' => 10,
                'available' => true,
                'option1' => 'Oro Amarillo',
                'option2' => (string) rand(1, 5),
                'option3' => (string) rand(40, 60),
                'requires_shipping' => true,
                'taxable' => false,
                'inventory_management' => 'shopify',
                'barcode' => null,
                'options' => json_encode([
                    'Oro Amarillo',
                    (string) rand(1, 5),
                    (string) rand(40, 60),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $variantMap[$productId] = $variantId;

            // Imagen principal
            DB::table('product_images')->insert([
                'product_id' => $productId,
                'shopify_media_id' => 3000000000000 + $i,
                'src' => "https://via.placeholder.com/600x600?text=Producto+$i",
                'alt' => "Imagen Producto $i",
                'width' => 600,
                'height' => 600,
                'position' => 1,
                'aspect_ratio' => 1.0000,
                'media_type' => 'image',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // CART (user1 con 2 productos)
        // =========================
        $cartId = DB::table('carts')->insertGetId([
            'user_id' => $user1,
            'status' => 'active',
            'subtotal' => 0,
            'total' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p1 = $productIds[0];
        $p2 = $productIds[1];

        $variant1 = DB::table('product_variants')->where('product_id', $p1)->first();
        $variant2 = DB::table('product_variants')->where('product_id', $p2)->first();

        DB::table('cart_items')->insert([
            [
                'cart_id' => $cartId,
                'product_id' => $p1,
                'product_name' => 'Producto 1',
                'quantity' => 1,
                'unit_price' => $variant1->price,
                'subtotal' => $variant1->price * 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cart_id' => $cartId,
                'product_id' => $p2,
                'product_name' => 'Producto 2',
                'quantity' => 2,
                'unit_price' => $variant2->price,
                'subtotal' => $variant2->price * 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $cartSubtotal = ($variant1->price * 1) + ($variant2->price * 2);

        DB::table('carts')
            ->where('id', $cartId)
            ->update([
                'subtotal' => $cartSubtotal,
                'total' => $cartSubtotal,
                'updated_at' => now(),
            ]);

        // =========================
        // ORDER (user1)
        // =========================
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $user1,
            'order_number' => 'ORD-' . strtoupper(Str::random(6)),
            'status' => 'delivered',
            'payment_status' => 'paid',
            'shipping_status' => 'delivered',
            'subtotal' => $cartSubtotal,
            'total' => $cartSubtotal,
            'customer_full_name' => 'Jose Tito',
            'customer_email' => 'jose@test.com',
            'shipping_recipient_name' => 'Jose Tito',
            'placed_at' => now(),
            'paid_at' => now(),
            'delivered_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('order_items')->insert([
            [
                'order_id' => $orderId,
                'product_id' => $p1,
                'product_name' => 'Producto 1',
                'quantity' => 1,
                'unit_price' => $variant1->price,
                'subtotal' => $variant1->price * 1,
                'total' => $variant1->price * 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $orderId,
                'product_id' => $p2,
                'product_name' => 'Producto 2',
                'quantity' => 2,
                'unit_price' => $variant2->price,
                'subtotal' => $variant2->price * 2,
                'total' => $variant2->price * 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

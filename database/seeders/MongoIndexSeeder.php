<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MongoIndexSeeder extends Seeder
{
    public function run(): void
    {
        $db = DB::connection('mongodb')->getMongoDB();

        // ─── PRODUCTS ────────────────────────────────────────────────
        $db->products->createIndex(['slug' => 1], ['unique' => true]);
        $db->products->createIndex(['shopify_id' => 1], ['unique' => true]);
        $db->products->createIndex(['status' => 1]);
        $db->products->createIndex(['category_ids' => 1]);

        // Índices compuestos para CategoryController
        $db->products->createIndex(['status' => 1, 'category_slugs' => 1, 'created_at' => -1]);
        $db->products->createIndex(['status' => 1, 'category_slugs' => 1, 'name' => 1]);

        // Búsqueda por texto
        $db->products->createIndex(['name' => 'text', 'brand' => 'text', 'description' => 'text']);

        // Filtros frecuentes
        $db->products->createIndex(['is_featured' => 1, 'status' => 1]);
        $db->products->createIndex(['promotion_ids' => 1]);
        $db->products->createIndex(['status' => 1, 'created_at' => -1]);

        // ─── CATEGORIES ──────────────────────────────────────────────
        $db->categories->createIndex(['slug' => 1], ['unique' => true]);
        $db->categories->createIndex(['path' => 1]);
        $db->categories->createIndex(['parent_id' => 1]);
        $db->categories->createIndex(['is_active' => 1, 'sort_order' => 1]);
        $db->categories->createIndex(['is_active' => 1, 'slug' => 1]); // usado en show()

        // ─── USERS ───────────────────────────────────────────────────
        $db->users->createIndex(['email' => 1], ['unique' => true]);

        // ─── CARTS ───────────────────────────────────────────────────
        $db->carts->createIndex(
            ['user_id' => 1, 'status' => 1],
            ['unique' => true, 'partialFilterExpression' => ['status' => 'active']]
        );
        $db->carts->createIndex(['session_id' => 1]);
        $db->carts->createIndex(['updated_at' => 1]); // para limpiar carritos viejos

        // ─── ORDERS ──────────────────────────────────────────────────
        $db->orders->createIndex(['order_number' => 1], ['unique' => true]);
        $db->orders->createIndex(['user_id' => 1, 'created_at' => -1]);
        $db->orders->createIndex(['status' => 1, 'created_at' => -1]);

        // ─── PROMOTIONS ──────────────────────────────────────────────
        $db->promotions->createIndex(['coupon_code' => 1], ['unique' => true]);
        $db->promotions->createIndex(['is_active' => 1, 'starts_at' => 1, 'ends_at' => 1]);

        $this->command->info('✅ Índices MongoDB creados correctamente.');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Crea la colección 'product_variants' en MongoDB
        DB::collection('product_variants')->insert([]);
    }

    public function down(): void
    {
        // Elimina la colección
        DB::collection('product_variants')->drop();
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Crear la colección 'category_product'
        DB::collection('category_product')->insert([]);
    }

    public function down(): void
    {
        // Eliminar la colección
        DB::collection('category_product')->drop();
    }
};
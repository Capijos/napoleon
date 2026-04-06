<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MongoDB no necesita blueprint ni foreign keys
        DB::collection('order_items')->insert([]); // solo para inicializar la colección
    }

    public function down(): void
    {
        DB::collection('order_items')->drop();
    }
};
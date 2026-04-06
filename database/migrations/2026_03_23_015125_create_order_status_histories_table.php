<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Crear la colección 'order_status_histories' en MongoDB
        DB::collection('order_status_histories')->insert([]); // Inicializa la colección
    }

    public function down(): void
    {
        DB::collection('order_status_histories')->drop();
    }
};
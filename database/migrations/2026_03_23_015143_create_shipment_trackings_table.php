<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Inicializa la colección 'shipment_trackings' en MongoDB
        DB::collection('shipment_trackings')->insert([]);
    }

    public function down(): void
    {
        // Elimina la colección
        DB::collection('shipment_trackings')->drop();
    }
};
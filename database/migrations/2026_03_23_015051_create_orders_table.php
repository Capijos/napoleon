<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MongoDB crea la colección automáticamente al insertar documentos,
        // pero podemos inicializar la colección así:
        DB::collection('orders')->insert([]); // vacío solo para crearla
    }

    public function down(): void
    {
        // Drop collection
        DB::collection('orders')->drop();
    }
};
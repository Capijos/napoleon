<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // En MongoDB no usamos Schema, pero podemos definir una "estructura lógica" si quieres
        // o simplemente dejamos que Laravel/MongoDB cree la colección al insertar datos.
        // Ejemplo con Jenssegers\Mongodb:
        
        DB::collection('addresses')->insert([
            // Esto es solo para inicializar la colección; en producción insertas documentos reales.
        ]);
    }

    public function down(): void
    {
        // Drop collection
        DB::collection('addresses')->drop();
    }
};
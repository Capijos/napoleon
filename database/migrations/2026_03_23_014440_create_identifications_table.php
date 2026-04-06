<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IdentificationsMongoSeeder extends Seeder
{
    public function run(): void
    {
        DB::collection('identifications')->insert([
            [
                '_id' => 'id_1',
                'first_name' => 'Juan',
                'last_name' => 'Perez',
                'document_type' => 'DNI',
                'document_number' => '12345678',
                'phone' => '555-1234',
                'email' => 'juan@example.com',
                'address' => 'Calle Falsa 123',
                'city' => 'Ciudad',
                'state' => 'Provincia',
                'country' => 'Pais',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Puedes añadir más documentos aquí
        ]);

        // Índice único en document_number
        DB::collection('identifications')->raw(function ($collection) {
            $collection->createIndex(['document_number' => 1], ['unique' => true, 'sparse' => true]);
        });
    }
}
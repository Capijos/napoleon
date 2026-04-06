<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CacheMongoSeeder extends Seeder
{
    public function run(): void
    {
        // Ejemplo de cache
        DB::collection('cache')->insert([
            'key' => 'example_cache_key',
            'value' => 'Este es el valor cacheado',
            'expiration' => time() + 3600, // timestamp de expiración
        ]);

        // Ejemplo de cache lock
        DB::collection('cache_locks')->insert([
            'key' => 'example_cache_key',
            'owner' => 'server_1',
            'expiration' => time() + 60, // timestamp de expiración del lock
        ]);
    }
}
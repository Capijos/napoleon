<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersMongoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un usuario de ejemplo
        User::create([
            'first_name' => 'José',
            'last_name' => 'Capilla',
            'email' => 'capijose@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+34123456789',
            'document_type' => 'DNI',
            'document_number' => '12345678',
            'avatar' => null,
            'is_admin' => true,
            'is_active' => true,
            'remember_token' => \Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Si quieres, puedes crear más usuarios con un factory
        User::factory()->count(10)->create();
    }
}
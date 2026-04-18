<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersMongoSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'capijose@example.com'],
            [
                'first_name' => 'Jose',
                'last_name' => 'Capilla',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+34123456789',
                'document_type' => 'dni',
                'document_number' => '12345678',
                'avatar' => null,
                'is_admin' => true,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]
        );
    }
}

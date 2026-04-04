<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('label')->nullable();
            $table->string('recipient_name');
            $table->string('phone', 30)->nullable();

            $table->string('country')->default('Perú');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();

            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('reference')->nullable();
            $table->string('postal_code')->nullable();

            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
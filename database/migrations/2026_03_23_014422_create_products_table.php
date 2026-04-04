<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->unsignedBigInteger('shopify_id')->nullable()->unique();
            $table->string('shopify_handle')->nullable()->index();
            $table->string('shopify_url')->nullable();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->text('short_description')->nullable();

            $table->string('brand')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();

            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('thickness', 10, 2)->nullable();

            $table->string('main_image')->nullable();

            $table->enum('status', ['draft', 'active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            $table->json('badge_labels')->nullable();
            $table->json('status_badges')->nullable();
            $table->json('raw_product')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

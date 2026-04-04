<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('shopify_media_id')->nullable()->index();

            $table->text('src');
            $table->text('alt')->nullable();

            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('position')->nullable();

            $table->decimal('aspect_ratio', 10, 4)->nullable();
            $table->string('media_type')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
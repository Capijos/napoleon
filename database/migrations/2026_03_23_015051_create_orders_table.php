<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('address_id')
                ->nullable()
                ->constrained('addresses')
                ->nullOnDelete();

            $table->string('order_number')->unique();

            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
            ])->default('pending');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
            ])->default('pending');

            $table->enum('shipping_status', [
                'pending',
                'preparing',
                'in_transit',
                'delivered',
                'returned',
            ])->default('pending');

            $table->string('currency', 10)->default('PEN');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->text('notes')->nullable();

            $table->string('customer_full_name');
            $table->string('customer_email');
            $table->string('customer_phone', 30)->nullable();

            $table->string('shipping_recipient_name');
            $table->string('shipping_phone', 30)->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_district')->nullable();
            $table->string('shipping_address_line_1')->nullable();
            $table->string('shipping_address_line_2')->nullable();
            $table->string('shipping_reference')->nullable();
            $table->string('shipping_postal_code')->nullable();

            $table->timestamp('placed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
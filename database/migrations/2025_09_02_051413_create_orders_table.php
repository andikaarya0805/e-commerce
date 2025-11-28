<?php

// database/migrations/create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_city');
            $table->text('customer_address');
            $table->text('notes')->nullable();
            $table->enum('payment_method', ['bank_transfer', 'cod', 'e_wallet'])->default('bank_transfer');
            $table->enum('shipping_method', ['free', 'express', 'same_day'])->default('free');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'created_at']);
            $table->index('customer_email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Store product name at time of order
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Price at time of order (with discount applied)
            $table->decimal('subtotal', 15, 2);
            $table->json('attributes')->nullable(); // Store product attributes/variations
            $table->timestamps();

            $table->index(['order_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('checkout_number', 40);
            $table->string('order_number', 50)->unique('idx_orders_order_number');
            $table->foreignUuid('buyer_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('store_id')->constrained('stores')->restrictOnDelete();
            $table->string('status', 24)->default('pending'); // pending, paid, processing, shipped, delivered, cancelled, refunded
            $table->json('shipping_address');
            $table->decimal('subtotal_amount', 14, 2)->default(0);
            $table->decimal('shipping_amount', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('service_fee', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('buyer_note')->nullable();
            $table->json('applied_promotions')->nullable();
            $table->string('courier', 80)->nullable();
            $table->string('service_name', 80)->nullable();
            $table->string('tracking_number', 120)->nullable();
            $table->string('shipment_status', 24)->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->json('tracking_events')->nullable();
            $table->json('status_history')->nullable();
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // High performance indexes
            $table->index(['buyer_user_id', 'status', 'placed_at'], 'idx_orders_buyer_status');
            $table->index(['store_id', 'status', 'placed_at'], 'idx_orders_store_status');
            $table->index('checkout_number', 'idx_orders_checkout');
            $table->index('tracking_number', 'idx_orders_tracking');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignUuid('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name_snapshot', 180);
            $table->json('variant_snapshot')->nullable();
            $table->string('sku_snapshot', 100)->nullable();
            $table->decimal('unit_price', 14, 2);
            $table->integer('quantity');
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id', 'idx_order_items_order');
            $table->index('product_id', 'idx_order_items_product');
            $table->index('variant_id', 'idx_order_items_variant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

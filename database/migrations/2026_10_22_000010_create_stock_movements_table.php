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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignUuid('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->string('movement_type', 24); // in, out, adjustment, order_sale, pos_sale, return
            $table->integer('quantity_delta');
            $table->integer('quantity_after');
            $table->string('reference_type', 40)->nullable(); // order, pos_sale, adjustment
            $table->uuid('reference_id')->nullable();
            $table->string('adjustment_number', 50)->nullable();
            $table->text('note')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            // High performance indexes for inventory tracking and audit
            $table->index(['store_id', 'created_at'], 'idx_stock_mov_store_created');
            $table->index(['variant_id', 'created_at'], 'idx_stock_mov_variant_created');
            $table->index(['reference_type', 'reference_id'], 'idx_stock_mov_ref');
            $table->index('adjustment_number', 'idx_stock_mov_adjustment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};

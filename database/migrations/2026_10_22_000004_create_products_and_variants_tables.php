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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignUuid('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name', 180);
            $table->string('slug', 220)->unique('idx_products_slug');
            $table->text('description')->nullable();
            $table->string('condition', 20)->default('baru'); // baru, bekas
            $table->string('status', 20)->default('active'); // draft, active, archived
            $table->integer('weight_gram')->default(0);
            $table->json('images')->nullable();
            $table->timestamps();

            // High performance indexes
            $table->index(['store_id', 'status'], 'idx_products_store_status');
            $table->index(['category_id', 'status'], 'idx_products_cat_status');
            $table->index(['status', 'created_at'], 'idx_products_status_created');
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku', 100)->nullable();
            $table->string('variant_name', 120);
            $table->json('attributes')->nullable();
            $table->decimal('price', 14, 2);
            $table->decimal('compare_at_price', 14, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // High performance indexes for POS barcode scanning and stock alerts
            $table->index(['product_id', 'is_active'], 'idx_variants_product_active');
            $table->index('sku', 'idx_variants_sku');
            $table->index('price', 'idx_variants_price');
            $table->index(['stock_quantity', 'low_stock_threshold'], 'idx_variants_low_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};

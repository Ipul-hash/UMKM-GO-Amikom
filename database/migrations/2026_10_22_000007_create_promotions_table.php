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
        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('code', 60)->unique('idx_promotions_code');
            $table->string('discount_type', 20); // percentage, fixed
            $table->decimal('discount_value', 14, 2);
            $table->decimal('max_discount_amount', 14, 2)->nullable();
            $table->decimal('minimum_purchase', 14, 2)->default(0);
            $table->json('product_ids')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('per_user_limit')->default(1);
            $table->integer('used_count')->default(0);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->string('status', 20)->default('active'); // active, expired, disabled
            $table->timestamp('created_at')->useCurrent();

            // High performance indexes
            $table->index(['store_id', 'status'], 'idx_promotions_store_status');
            $table->index(['status', 'starts_at', 'ends_at'], 'idx_promotions_validity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};

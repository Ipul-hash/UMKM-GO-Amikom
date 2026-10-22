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
        Schema::create('user_activity', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('type', 10); // search, view
            $table->string('search_query', 250)->nullable();
            $table->foreignUuid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            // High performance indexes
            $table->index(['user_id', 'created_at'], 'idx_activity_user_created');
            $table->index(['type', 'created_at'], 'idx_activity_type_created');
            $table->index('product_id', 'idx_activity_product');
            $table->index('search_query', 'idx_activity_search_query');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity');
    }
};

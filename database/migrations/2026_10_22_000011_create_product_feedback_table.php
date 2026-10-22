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
        Schema::create('product_feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 10)->default('review'); // review, question, report
            $table->foreignUuid('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
            $table->smallInteger('rating')->nullable();
            $table->string('reason', 80)->nullable();
            $table->text('content')->nullable();
            $table->text('reply')->nullable();
            $table->foreignUuid('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('replied_at')->nullable();
            $table->string('status', 20)->default('published'); // published, hidden, flagged
            $table->timestamps();

            // High performance indexes
            $table->index(['product_id', 'type', 'status'], 'idx_feedback_product_type_status');
            $table->index('user_id', 'idx_feedback_user');
            $table->index('order_item_id', 'idx_feedback_order_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_feedback');
    }
};

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
        Schema::create('pos_shifts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('register_name', 100);
            $table->foreignUuid('cashier_user_id')->constrained('users')->restrictOnDelete();
            $table->decimal('opening_cash', 14, 2)->default(0);
            $table->decimal('closing_cash', 14, 2)->nullable();
            $table->decimal('expected_cash', 14, 2)->nullable();
            $table->string('status', 20)->default('open'); // open, closed, cancelled
            $table->text('note')->nullable();
            $table->timestamp('opened_at')->useCurrent();
            $table->dateTime('closed_at')->nullable();

            // High performance indexes
            $table->index(['store_id', 'status'], 'idx_pos_shifts_store_status');
            $table->index(['cashier_user_id', 'status'], 'idx_pos_shifts_cashier_status');
            $table->index('opened_at', 'idx_pos_shifts_opened');
        });

        Schema::create('pos_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignUuid('shift_id')->constrained('pos_shifts')->restrictOnDelete();
            $table->foreignUuid('cashier_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('customer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('receipt_number', 60)->unique('idx_pos_sales_receipt');
            $table->json('items');
            $table->json('payments');
            $table->decimal('subtotal_amount', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('status', 20)->default('completed'); // completed, cancelled, refunded
            $table->text('note')->nullable();
            $table->timestamp('sold_at')->useCurrent();

            // High performance indexes
            $table->index(['store_id', 'sold_at'], 'idx_pos_sales_store_sold');
            $table->index('shift_id', 'idx_pos_sales_shift');
            $table->index('cashier_user_id', 'idx_pos_sales_cashier');
            $table->index('customer_user_id', 'idx_pos_sales_customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_sales');
        Schema::dropIfExists('pos_shifts');
    }
};

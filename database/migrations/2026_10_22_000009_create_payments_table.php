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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('payment_number', 50)->unique('idx_payments_number');
            $table->string('kind', 10)->default('payment'); // payment, refund
            $table->string('checkout_number', 40)->nullable();
            $table->foreignUuid('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignUuid('parent_payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->string('method', 30); // qris, bank_transfer, ewallet, cod, cash
            $table->string('provider', 80)->nullable();
            $table->string('provider_reference', 180)->nullable();
            $table->decimal('amount', 14, 2);
            $table->string('status', 24)->default('pending'); // pending, paid, failed, expired, refunded
            $table->text('reason')->nullable();
            $table->foreignUuid('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->json('provider_payload')->nullable();
            $table->timestamps();

            // High performance indexes for payment gateway reconciliation & status checks
            $table->index(['order_id', 'status'], 'idx_payments_order');
            $table->index('checkout_number', 'idx_payments_checkout');
            $table->index('provider_reference', 'idx_payments_provider_ref');
            $table->index(['status', 'expires_at'], 'idx_payments_status_expires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

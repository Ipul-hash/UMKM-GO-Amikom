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
        Schema::create('financial_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->dateTime('entry_date')->useCurrent();
            $table->string('direction', 10); // income, expense
            $table->string('entry_type', 40);
            $table->string('category', 100);
            $table->decimal('amount', 14, 2);
            $table->text('description')->nullable();
            $table->text('attachment_url')->nullable();
            $table->string('source_table', 60)->nullable();
            $table->uuid('source_id')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            // High performance indexes for financial bookkeeping & reports
            $table->index(['store_id', 'entry_date'], 'idx_fin_store_date');
            $table->index(['store_id', 'direction', 'entry_date'], 'idx_fin_store_dir_date');
            $table->index(['source_table', 'source_id'], 'idx_fin_source');
            $table->index('created_by', 'idx_fin_created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_entries');
    }
};

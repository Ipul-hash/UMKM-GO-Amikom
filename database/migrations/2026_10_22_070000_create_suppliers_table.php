<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('supply_category', 100);
            $table->string('pic_name', 120);
            $table->string('phone', 30);
            $table->string('email', 150)->nullable();
            $table->string('city', 100)->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index(['store_id', 'status'], 'idx_suppliers_store_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

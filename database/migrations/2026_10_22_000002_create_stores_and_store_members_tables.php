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
        Schema::create('stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 180)->unique('idx_stores_slug');
            $table->text('description')->nullable();
            $table->text('logo_url')->nullable();
            $table->text('banner_url')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('business_type', 80)->nullable();
            $table->string('status', 24)->default('pending'); // pending, active, suspended, closed
            $table->text('address_line')->nullable();
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('postal_code', 12)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('opening_hours')->nullable();
            $table->json('verification_documents')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();

            // Indexes for geolocation proximity, status & region filtering
            $table->index('owner_user_id', 'idx_stores_owner');
            $table->index('status', 'idx_stores_status');
            $table->index(['latitude', 'longitude'], 'idx_stores_geo');
            $table->index(['city', 'district'], 'idx_stores_location');
        });

        Schema::create('store_members', function (Blueprint $table) {
            $table->foreignUuid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 20); // seller_owner, seller_staff, cashier
            $table->boolean('is_active')->default(true);
            $table->dateTime('joined_at')->useCurrent();

            $table->primary(['store_id', 'user_id']);
            $table->index('user_id', 'idx_store_members_user');
            $table->index(['store_id', 'role', 'is_active'], 'idx_store_members_store_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_members');
        Schema::dropIfExists('stores');
    }
};

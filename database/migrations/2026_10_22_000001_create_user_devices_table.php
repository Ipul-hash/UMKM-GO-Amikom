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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('refresh_token_hash', 255)->nullable();
            $table->string('push_token', 512)->nullable();
            $table->string('platform', 20); // android, ios, web, desktop
            $table->string('device_name', 120)->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('revoked_at')->nullable();
            $table->dateTime('last_seen_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Indexes for fast token verification and active session checks
            $table->index(['user_id', 'revoked_at'], 'idx_user_devices_user_revoked');
            $table->index('refresh_token_hash', 'idx_user_devices_refresh_token');
            $table->index('platform', 'idx_user_devices_platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};

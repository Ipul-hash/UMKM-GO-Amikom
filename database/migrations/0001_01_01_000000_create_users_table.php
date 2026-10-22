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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name', 120);
            $table->string('email', 255)->unique('idx_users_email');
            $table->string('phone', 30)->nullable()->index('idx_users_phone');
            $table->text('password_hash');
            $table->text('avatar_url')->nullable();
            $table->string('role', 20)->default('customer');
            $table->string('status', 20)->default('active');
            $table->json('oauth_accounts')->nullable();
            $table->json('addresses')->nullable();
            $table->json('notification_prefs')->nullable();
            $table->string('reset_token_hash', 255)->nullable()->index('idx_users_reset_token');
            $table->dateTime('reset_token_expires_at')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->dateTime('phone_verified_at')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // High performance composite index
            $table->index(['role', 'status'], 'idx_users_role_status');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

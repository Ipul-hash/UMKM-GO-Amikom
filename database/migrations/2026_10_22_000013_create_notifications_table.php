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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 40);
            $table->string('title', 180);
            $table->text('body');
            $table->text('deep_link')->nullable();
            $table->json('payload')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Index for querying unread and latest user notifications
            $table->index(['user_id', 'read_at', 'created_at'], 'idx_notifications_user_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

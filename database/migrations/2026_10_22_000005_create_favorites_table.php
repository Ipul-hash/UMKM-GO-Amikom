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
        Schema::create('favorites', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('target_type', 10); // product, store
            $table->uuid('target_id');
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['user_id', 'target_type', 'target_id']);
            $table->index(['target_type', 'target_id'], 'idx_favorites_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};

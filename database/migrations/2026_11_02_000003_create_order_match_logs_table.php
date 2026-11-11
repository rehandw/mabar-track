<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_match_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('action')->comment('DECREMENT_MATCH, SET_PLAYING, RESET_MATCH, COMPLETED, CANCELLED');
            $table->unsignedInteger('matches_before');
            $table->unsignedInteger('matches_after');
            $table->string('actor_name')->default('Streamer');
            $table->string('remarks')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_match_logs');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('in_game_id');
            $table->string('in_game_nickname')->nullable();
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->unsignedInteger('total_matches')->default(1);
            $table->unsignedInteger('remaining_matches')->default(1);
            $table->string('status')->default('queued')->index()->comment('queued, playing, completed, cancelled');
            $table->string('payment_status')->default('verified')->index()->comment('verified, pending, refunded');
            $table->string('saweria_transaction_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->integer('queue_position')->default(0);
            $table->timestamp('started_playing_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


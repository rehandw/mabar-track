<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->decimal('fast_track_price_per_match', 12, 2)->nullable()->after('price_per_match');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_fast_track')->default(false)->index()->after('payment_status');
        });

        Schema::table('streamer_settings', function (Blueprint $table) {
            $table->boolean('enable_fast_track')->default(true)->after('is_queue_open');
        });
    }

    public function down(): void
    {
        Schema::table('streamer_settings', function (Blueprint $table) {
            $table->dropColumn('enable_fast_track');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_fast_track');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('fast_track_price_per_match');
        });
    }
};


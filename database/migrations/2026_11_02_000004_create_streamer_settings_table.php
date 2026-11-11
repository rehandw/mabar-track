<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('streamer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('streamer_name')->default('Pro Streamer ID');
            $table->string('channel_url')->nullable();
            $table->boolean('is_queue_open')->default(true);
            $table->string('overlay_token')->unique();
            $table->string('announcement')->nullable()->default('Open jasa mabar! Donasi Saweria otomatis masuk antrean.');
            $table->string('saweria_stream_key')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('streamer_settings');
    }
};


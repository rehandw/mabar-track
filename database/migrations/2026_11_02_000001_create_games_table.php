<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('MOBA');
            $table->decimal('price_per_match', 12, 2)->default(10000.00);
            $table->string('id_format_hint')->nullable()->comment('Contoh: ID(Server) atau RiotID#TAG');
            $table->string('badge_color')->default('cyan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};


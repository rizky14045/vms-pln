<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_number')->unique();
            $table->tinyInteger('status')->default(1); // 1 = tersedia/aktif, 0 = sedang dipakai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_cards');
    }
};

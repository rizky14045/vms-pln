<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_card_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_card_id')
                ->constrained('visitor_cards')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            // nullable + nullOnDelete supaya history tetap ada walau data registrasi/user aslinya dihapus
            $table->foreignId('registered_person_id')
                ->nullable()
                ->constrained('registered_persons')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->dateTime('borrowed_at');
            $table->dateTime('returned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_card_histories');
    }
};

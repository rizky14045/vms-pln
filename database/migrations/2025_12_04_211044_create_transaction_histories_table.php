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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->date('tr_date');
            $table->time('tr_time');
            $table->string('card_no')->nullable();
            $table->string('transaction')->nullable();
            $table->string('tr_code')->nullable();
            $table->string('door_name')->nullable();
            $table->string('card_name')->nullable();
            $table->string('department')->nullable();
            $table->string('staff_no')->nullable();
            $table->string('nric')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};

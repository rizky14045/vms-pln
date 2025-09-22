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
        Schema::table('registered_persons', function (Blueprint $table) {
            $table->string('purpose_of_visit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registered_persons', function (Blueprint $table) {
            $table->dropColumn('purpose_of_visit');
        });
    }
};

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
            $table->string('pic_name')->nullable()->after('purpose_of_visit');
            $table->string('pic_phone')->nullable()->after('pic_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registered_persons', function (Blueprint $table) {
            //
        });
    }
};

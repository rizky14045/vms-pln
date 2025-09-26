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
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_card_number')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->boolean('is_employee')->default(true);
            $table->boolean('is_registered')->default(false);
            $table->timestamp('join_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_card_number');
            $table->dropColumn('identity_number');
            $table->dropColumn('phone');
            $table->dropColumn('company');
            $table->dropColumn('is_employee');
            $table->dropColumn('is_registered');
            $table->dropColumn('join_date');
        });
    }
};

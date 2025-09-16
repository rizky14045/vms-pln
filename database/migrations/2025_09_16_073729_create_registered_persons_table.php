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
        Schema::create('registered_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('id_card_num')->nullable();
            $table->string('id_number')->nullable();
            $table->integer('face_permission')->nullable();
            $table->integer('id_card_permission')->nullable();
            $table->integer('face_card_permission')->nullable();
            $table->integer('id_permission')->nullable();
            $table->string('tag')->nullable();
            $table->string('phone')->nullable();
            $table->string('password_fr')->nullable();
            $table->integer('password_permission')->nullable();
            $table->text('person_image')->nullable();
            $table->boolean('is_employee');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registered_persons');
    }
};

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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categories_id');
            $table->enum('tipo', ['multiple', 'boolean'])->default('multiple');
            $table->text('enunciado');
            $table->unsignedBigInteger('creada_por_user_id');
            $table->boolean('activa')->default(true);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');          
            $table->foreign('creada_por_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

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
        Schema::create('question_options', function (Blueprint $table) {
            $table->id(); // id_opcion
            $table->unsignedBigInteger('pregunta_id');
            $table->text('texto');
            $table->boolean('es_correcta')->default(false);
            $table->integer('orden')->default(1);
            $table->timestamps();

            // Foreign Key
            $table->foreign('pregunta_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};

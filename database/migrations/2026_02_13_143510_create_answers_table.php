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
        Schema::create('answers', function (Blueprint $table) {
            $table->id(); // id_respuesta
            $table->unsignedBigInteger('id_partida');
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->unsignedBigInteger('opcion_id')->nullable();
            $table->text('respuesta_texto')->nullable();
            $table->boolean('es_correcta')->default(false);
            $table->integer('tiempo_ms');
            $table->integer('puntos_obtenidos')->default(0);
            $table->timestamp('respondida_at');
            
            // Foreign Keys
            $table->foreign('id_partida')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pregunta_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('opcion_id')->references('id')->on('question_options')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};

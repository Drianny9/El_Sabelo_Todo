<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games_question', function (Blueprint $table) {
            $table->id();

            //Columnas - IMPORTANTE: deben ser unsignedBigInteger para coincidir con las claves primarias
            $table->unsignedBigInteger('id_partida');
            $table->unsignedBigInteger('pregunta_id');
            $table->integer('orden_pregunta')->default(1);
            $table->integer('tiempo_limite_seg')->default(30);
            $table->integer('puntos_acierto')->default(100);
            $table->integer('puntos_bonus_rapidez')->default(50);

            $table->timestamps();

            //Foreign Keys - DESPUÉS de definir todas las columnas
            $table->foreign('id_partida')->references('id')->on('games')->onDelete('cascade');

            $table->foreign('pregunta_id')->references('id')->on('questions')->onDelete('cascade');

            //Indices para mejorar el rendimiento
            $table->index('id_partida');
            $table->index('pregunta_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games_question');
    }
};

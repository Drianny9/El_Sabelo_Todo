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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();            //Code para unirse a la sala
            $table->string('code')->unique(); 
            
            //Jugador 1 (creador de la sala)
            $table->foreignId('player_1_id')->constrained('users');
            
            //Jugador 2 (puede ser nulo hasta que alguien se una)
            $table->foreignId('player_2_id')->nullable()->constrained('users');
            
            //Almacena los IDs de las preguntas para la partida
            $table->json('questions_ids');
            
            //Puntuaciones de cada jugador
            $table->integer('score_p1')->default(0);
            $table->integer('score_p2')->default(0);
            
            //Estado de la sala para controlar el flujo del juego
            $table->enum('status', ['open', 'playing', 'finished'])->default('open');
                        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

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
        Schema::create('games', function (Blueprint $table) {
            $table->id(); // id_partida
            $table->enum('tipo', ['solo', 'multijugador'])->default('solo');
            $table->enum('estado', ['en_curso', 'finalizada', 'abandonada'])->default('en_curso');
            $table->string('pin_codigo')->nullable()->unique();
            $table->unsignedBigInteger('creada_por_user_id');
            $table->timestamp('finalizada_at')->nullable();
            $table->timestamps();

            //Foreign Key
            $table->foreign('creada_por_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};

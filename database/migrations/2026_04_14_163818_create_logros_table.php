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
        Schema::create('logros', function (Blueprint $table) {
            $table->id(); //ID autoincremental
            $table->string('codigo')->unique(); //p.ej. 'FIRST_WIN'
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable(); //Para guardar el path de la imagen o una clase de iconos
            $table->integer('puntos')->default(0); //Puntos que otorga el logro

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logros');
    }
};

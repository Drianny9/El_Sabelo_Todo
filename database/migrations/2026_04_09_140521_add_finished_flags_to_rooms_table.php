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
        Schema::table('rooms', function (Blueprint $table) {
            //añadimos estas dos columnas booleanas para saber exactamente quién ha terminado la partida 1vs1
            //esto lo hacemos porque por defecto el score es 0 y así evitamos bugs si alguien saca 0 puntos
            $table->boolean('p1_finished')->default(false);
            $table->boolean('p2_finished')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            //eliminamos las columnas si damos marcha atrás a la migración con rollback
            $table->dropColumn(['p1_finished', 'p2_finished']);
        });
    }
};

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
        Schema::create('logro_user', function (Blueprint $table) {
            //Añadimos el ondelete por si el usuario elimina su cuenta se eliminen todos los logros tambien
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('logro_id')->constrained('logros')->onDelete('cascade');


            $table->timestamps();

            $table->unique(['user_id', 'logro_id']); //Para asegurar que un usuario no pueda ganar el mismo logro dos veces
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logro_user');
    }
};

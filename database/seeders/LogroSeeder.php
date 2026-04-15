<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logros = [
            ['codigo' => 'PRIMERA_PARTIDA', 'nombre' => 'Primeros pasos', 'descripcion' => 'Juega tu primera partida', 'icono' => ' ', 'puntos' => 10],
            ['codigo' => 'WINNER_10', 'nombre' => 'Racha Victoriosa', 'descripcion' => 'Gana 10 partidas', 'icono' => ' ', 'puntos' => 50]
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Logro;
use Carbon\Carbon;

class LogroSeeder extends Seeder
{
    public function run(): void
    {
        //Definimos los logros disponibles en el sistema con su código único, nombre, descripción e icono
        //Usamos la libreria Carbon para trabajar con fechas y horas
        $logros = [
            ['codigo' => 'PRIMERA_PARTIDA', 'nombre' => 'Primeros Pasos',    'descripcion' => 'Completa tu primera partida individual.',   'icono' => 'pi pi-play-circle',    'puntos' => 10,  'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['codigo' => 'PUNTUACION_50',   'nombre' => 'Medio Centenar',    'descripcion' => 'Acumula 50 puntos en total.',                'icono' => 'pi pi-chart-bar',      'puntos' => 20,  'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['codigo' => 'PUNTUACION_100',  'nombre' => 'Centenario',        'descripcion' => 'Acumula 100 puntos en total.',               'icono' => 'pi pi-trophy',         'puntos' => 50,  'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['codigo' => 'PUNTUACION_500',  'nombre' => 'Leyenda',           'descripcion' => 'Acumula 500 puntos en total.',               'icono' => 'pi pi-star-fill',      'puntos' => 100, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['codigo' => 'PRIMERA_1VS1',    'nombre' => 'Duelo Iniciado',    'descripcion' => 'Completa tu primera partida 1vs1.',          'icono' => 'pi pi-users',          'puntos' => 15,  'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['codigo' => 'GANADOR_1VS1',    'nombre' => 'Victorioso',        'descripcion' => 'Gana una partida 1vs1.',                    'icono' => 'pi pi-crown',          'puntos' => 30,  'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['codigo' => 'NOTA_PERFECTA',   'nombre' => 'Nota Perfecta',     'descripcion' => 'Consigue 10/10 en una partida individual.',  'icono' => 'pi pi-check-circle',   'puntos' => 75,  'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        //Insertamos todos los logros en la base de datos de una vez
        Logro::insert($logros);
    }
}

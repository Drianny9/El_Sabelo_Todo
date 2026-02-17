<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tablas antes de insertar
        DB::table('question_options')->delete();
        DB::table('questions')->delete();

        $now = now();
        $userId = 1;

        // Obtener IDs de categorías
        $categorias = DB::table('categories')->pluck('id', 'name');

        // Array de preguntas (SIN id)
        $preguntasData = [
            // CIENCIA
            [
                'categories_id' => $categorias['Ciencia'] ?? 1,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuál es el elemento químico más abundante en el universo?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Hidrógeno', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Oxígeno', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Carbono', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Helio', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Ciencia'] ?? 1,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuántos huesos tiene el cuerpo humano adulto?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => '206', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => '186', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => '226', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => '196', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Ciencia'] ?? 1,
                'tipo' => 'boolean',
                'enunciado' => '¿La velocidad de la luz es aproximadamente 300.000 km/s?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Verdadero', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Falso', 'es_correcta' => false, 'orden' => 2],
                ]
            ],
            [
                'categories_id' => $categorias['Ciencia'] ?? 1,
                'tipo' => 'multiple',
                'enunciado' => '¿Qué planeta es conocido como el planeta rojo?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Marte', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Venus', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Júpiter', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Saturno', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Ciencia'] ?? 1,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuál es la fórmula química del agua?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'H2O', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'CO2', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'O2', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'H2O2', 'es_correcta' => false, 'orden' => 4],
                ]
            ],

            // HISTORIA
            [
                'categories_id' => $categorias['Historia'] ?? 2,
                'tipo' => 'multiple',
                'enunciado' => '¿En qué año comenzó la Segunda Guerra Mundial?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => '1939', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => '1914', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => '1945', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => '1941', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Historia'] ?? 2,
                'tipo' => 'multiple',
                'enunciado' => '¿Quién fue el primer presidente de Estados Unidos?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'George Washington', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Abraham Lincoln', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Thomas Jefferson', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'John Adams', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Historia'] ?? 2,
                'tipo' => 'boolean',
                'enunciado' => '¿Cristóbal Colón descubrió América en 1492?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Verdadero', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Falso', 'es_correcta' => false, 'orden' => 2],
                ]
            ],
            [
                'categories_id' => $categorias['Historia'] ?? 2,
                'tipo' => 'multiple',
                'enunciado' => '¿Qué imperio construyó el Machu Picchu?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Imperio Inca', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Imperio Azteca', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Imperio Maya', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Imperio Romano', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Historia'] ?? 2,
                'tipo' => 'multiple',
                'enunciado' => '¿Quién pintó la Mona Lisa?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Leonardo da Vinci', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Miguel Ángel', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Rafael', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Pablo Picasso', 'es_correcta' => false, 'orden' => 4],
                ]
            ],

            // GEOGRAFÍA
            [
                'categories_id' => $categorias['Geografía'] ?? 3,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuál es la capital de Francia?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'París', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Londres', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Berlín', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Madrid', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Geografía'] ?? 3,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuál es el río más largo del mundo?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Río Amazonas', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Río Nilo', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Río Yangtsé', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Río Misisipi', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Geografía'] ?? 3,
                'tipo' => 'boolean',
                'enunciado' => '¿El Monte Everest es la montaña más alta del mundo?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Verdadero', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Falso', 'es_correcta' => false, 'orden' => 2],
                ]
            ],
            [
                'categories_id' => $categorias['Geografía'] ?? 3,
                'tipo' => 'multiple',
                'enunciado' => '¿En qué continente se encuentra Egipto?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'África', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Asia', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Europa', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'América', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Geografía'] ?? 3,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuál es el país más grande del mundo por superficie?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Rusia', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Canadá', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'China', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Estados Unidos', 'es_correcta' => false, 'orden' => 4],
                ]
            ],

            // DEPORTES
            [
                'categories_id' => $categorias['Deportes'] ?? 4,
                'tipo' => 'multiple',
                'enunciado' => '¿Cada cuántos años se celebran los Juegos Olímpicos de verano?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => '4 años', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => '2 años', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => '5 años', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => '3 años', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Deportes'] ?? 4,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuántos jugadores tiene un equipo de fútbol en el campo?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => '11', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => '10', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => '9', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => '12', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Deportes'] ?? 4,
                'tipo' => 'boolean',
                'enunciado' => '¿El baloncesto fue inventado en Estados Unidos?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Verdadero', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Falso', 'es_correcta' => false, 'orden' => 2],
                ]
            ],
            [
                'categories_id' => $categorias['Deportes'] ?? 4,
                'tipo' => 'multiple',
                'enunciado' => '¿Qué país ganó la Copa del Mundo de Fútbol 2018?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Francia', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Croacia', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Brasil', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Alemania', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Deportes'] ?? 4,
                'tipo' => 'multiple',
                'enunciado' => '¿Cuántos Grand Slam hay en tenis?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => '4', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => '3', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => '5', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => '6', 'es_correcta' => false, 'orden' => 4],
                ]
            ],

            // ARTE
            [
                'categories_id' => $categorias['Arte'] ?? 5,
                'tipo' => 'multiple',
                'enunciado' => '¿Quién pintó "La noche estrellada"?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Vincent van Gogh', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Pablo Picasso', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Claude Monet', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Salvador Dalí', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Arte'] ?? 5,
                'tipo' => 'multiple',
                'enunciado' => '¿En qué museo se encuentra "La Gioconda"?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Museo del Louvre', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Museo del Prado', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'British Museum', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'MoMA', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Arte'] ?? 5,
                'tipo' => 'boolean',
                'enunciado' => '¿Pablo Picasso fue el creador del cubismo?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Verdadero', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Falso', 'es_correcta' => false, 'orden' => 2],
                ]
            ],
            [
                'categories_id' => $categorias['Arte'] ?? 5,
                'tipo' => 'multiple',
                'enunciado' => '¿Quién esculpió "El David"?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Miguel Ángel', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Donatello', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Bernini', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Rodin', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Arte'] ?? 5,
                'tipo' => 'multiple',
                'enunciado' => '¿Qué movimiento artístico fundó Andy Warhol?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Pop Art', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Surrealismo', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Expresionismo', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Dadaísmo', 'es_correcta' => false, 'orden' => 4],
                ]
            ],

            // ENTRETENIMIENTO
            [
                'categories_id' => $categorias['Entretenimiento'] ?? 6,
                'tipo' => 'multiple',
                'enunciado' => '¿Qué saga incluye a Luke Skywalker?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Star Wars', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Star Trek', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'El Señor de los Anillos', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Harry Potter', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Entretenimiento'] ?? 6,
                'tipo' => 'multiple',
                'enunciado' => '¿Quién interpretó a Jack Dawson en "Titanic"?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Leonardo DiCaprio', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Brad Pitt', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Tom Cruise', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Johnny Depp', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Entretenimiento'] ?? 6,
                'tipo' => 'boolean',
                'enunciado' => '¿"Game of Thrones" está basada en los libros de George R.R. Martin?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'Verdadero', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'Falso', 'es_correcta' => false, 'orden' => 2],
                ]
            ],
            [
                'categories_id' => $categorias['Entretenimiento'] ?? 6,
                'tipo' => 'multiple',
                'enunciado' => '¿En qué año se estrenó la primera película de "Harry Potter"?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => '2001', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => '1999', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => '2003', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => '2005', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
            [
                'categories_id' => $categorias['Entretenimiento'] ?? 6,
                'tipo' => 'multiple',
                'enunciado' => '¿Qué banda lanzó el álbum "Abbey Road"?',
                'creada_por_user_id' => $userId,
                'activa' => true,
                'opciones' => [
                    ['texto' => 'The Beatles', 'es_correcta' => true, 'orden' => 1],
                    ['texto' => 'The Rolling Stones', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'Queen', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'Led Zeppelin', 'es_correcta' => false, 'orden' => 4],
                ]
            ],
        ];

        // Insertar preguntas y opciones
        foreach ($preguntasData as $preguntaData) {
            $opciones = $preguntaData['opciones'];
            unset($preguntaData['opciones']);

            // Insertar pregunta y obtener el ID autogenerado
            $preguntaId = DB::table('questions')->insertGetId(array_merge($preguntaData, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));

            // Insertar opciones usando el ID de la pregunta recién creada
            foreach ($opciones as $opcion) {
                DB::table('question_options')->insert([
                    'pregunta_id' => $preguntaId,
                    'texto' => $opcion['texto'],
                    'es_correcta' => $opcion['es_correcta'],
                    'orden' => $opcion['orden'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('✅ Se crearon 30 preguntas con sus opciones');
    }
}

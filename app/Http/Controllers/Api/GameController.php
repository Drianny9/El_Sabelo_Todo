<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Logro;
use Illuminate\Http\Request;
use App\Http\Resources\QuestionResource;

class GameController extends Controller
{
    //Funcion para obtener preguntas random
    public function getRandomQuestions(){
        //Suponemos que queremos uns partida de 10 preguntas
        $preguntas = Question::with('options', 'category', 'createdByUser') //Cargamos las respuestas
                    ->inRandomOrder() //Desordena la tabla
                    ->limit(10) //Coge las 10 primeras de la lista desordenadas
                    ->get();

        return QuestionResource::collection($preguntas);
        //return response()->json($preguntas);
    }

    public function saveScore(Request $request){
        $request->validate([
            'puntuacion' => 'required|integer|min:0'
        ]);

        $user = auth()->user();
        if(!$user){
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user->puntuacion += $request->puntuacion;
        $user->save();

        //Comprobamos qué logros NUEVOS ha desbloqueado el usuario con esta partida
        $nuevosLogros = $this->evaluarLogros($user, $request->puntuacion);

        return response()->json([
            'message' => 'Puntuación guardada correctamente',
            'puntuacion' => $user->puntuacion,
            'nuevos_logros' => $nuevosLogros //Array con info completa de logros recién desbloqueados
        ]);
    }

    //Evalúa las condiciones de cada logro y los asigna si el usuario las cumple
    //Devuelve SOLO los logros que son nuevos (el usuario no los tenía antes de esta partida)
    private function evaluarLogros($user, int $puntuacionPartida): array
    {
        //1. Guardamos los IDs de logros que el usuario YA tenía antes de esta partida
        $logrosPrevios = $user->logros()->pluck('logros.id')->toArray();

        $totalPuntos = $user->puntuacion;
        $logros = Logro::all()->keyBy('codigo');
        $logrosAConceder = []; //IDs de logros que merece por condiciones

        //LOGRO: Primera partida individual
        if ($logros->has('PRIMERA_PARTIDA')) {
            $logrosAConceder[] = $logros['PRIMERA_PARTIDA']->id;
        }

        //LOGRO: 50 puntos acumulados en total
        if ($totalPuntos >= 50 && $logros->has('PUNTUACION_50')) {
            $logrosAConceder[] = $logros['PUNTUACION_50']->id;
        }

        //LOGRO: 100 puntos acumulados en total
        if ($totalPuntos >= 100 && $logros->has('PUNTUACION_100')) {
            $logrosAConceder[] = $logros['PUNTUACION_100']->id;
        }

        //LOGRO: 500 puntos acumulados en total
        if ($totalPuntos >= 500 && $logros->has('PUNTUACION_500')) {
            $logrosAConceder[] = $logros['PUNTUACION_500']->id;
        }

        //LOGRO: Nota perfecta (10 aciertos en una sola partida)
        if ($puntuacionPartida >= 10 && $logros->has('NOTA_PERFECTA')) {
            $logrosAConceder[] = $logros['NOTA_PERFECTA']->id;
        }

        //2. Asignamos todos los logros de una vez (no crea duplicados)
        $user->logros()->syncWithoutDetaching($logrosAConceder);

        //3. Filtramos para quedarnos SOLO con los que son nuevos (no estaban en logrosPrevios)
        $idsNuevos = array_diff($logrosAConceder, $logrosPrevios);

        //4. Obtenemos la info completa de los logros nuevos
        $nuevosLogros = Logro::whereIn('id', $idsNuevos)->get(['nombre', 'icono', 'puntos'])->toArray();

        //5. Sumamos los puntos bonus de cada logro nuevo al total del usuario
        $puntosBonus = array_sum(array_column($nuevosLogros, 'puntos'));
        if ($puntosBonus > 0) {
            $user->puntuacion += $puntosBonus;
            $user->save();
        }

        return $nuevosLogros;
    }
}

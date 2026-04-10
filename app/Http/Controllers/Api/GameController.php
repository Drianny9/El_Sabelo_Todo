<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Question;
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
        if($user){
            $user->puntuacion += $request->puntuacion;
            $user->save();
            return response()->json(['message' => 'Puntuación guardada correctamente', 'puntuacion' => $user->puntuacion]);
        }

        return response()->json(['message' => 'Usuario no autenticado'], 401);
    }
}

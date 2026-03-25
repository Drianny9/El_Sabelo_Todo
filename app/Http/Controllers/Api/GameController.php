<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Question;
use Illuminate\Http\Request;

class GameController extends Controller
{
    //Funcion para obtener preguntas random
    public function getRandomQuestions(){
        //Suponemos que queremos uns partida de 10 preguntas
        $preguntas = Question::with('options') //Cargamos las respuestas
                    ->inRandomOrder() //Desordena la tabla
                    ->limit(10) //Coge las 10 primeras de la lista desordenadas
                    ->get();

        return response()->json($preguntas);
    }


}

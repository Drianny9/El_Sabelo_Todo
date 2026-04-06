<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\QuestionResource;

class RoomController extends Controller
{
    //Crea una nueva sala de juego
    public function create(Request $request)
    {
        //Seleccionamos 10 IDs de preguntas de forma aleatoria
        $questionIds = Question::inRandomOrder()->limit(10)->pluck('id');

        //Generamos un código único para la sala
        do {
            $code = Str::random(6);
        } while (Room::where('code', $code)->exists());

        //Creamos la sala en la base de datos
        $room = Room::create([
            'code' => $code,
            'player_1_id' => Auth::id(),
            'questions_ids' => $questionIds,
        ]);

        //Devolvemos el código de la sala para que el jugador pueda compartirlo
        return response()->json(['code' => $room->code], 201);
    }

    //Permite a un segundo jugador unirse a una sala existente
    public function join(Request $request)
    {
        $request->validate(['code' => 'required|string|exists:rooms,code']);

        $room = Room::where('code', $request->code)->first();

        //Verificamos si la sala ya está llena
        if ($room->player_2_id) {
            return response()->json(['message' => 'La sala ya está llena.'], 403);
        }

        //Verificamos que el jugador no intente unirse a su propia sala
        if ($room->player_1_id === Auth::id()) {
            return response()->json(['message' => 'No puedes unirte a tu propia sala.'], 403);
        }

        //Asignamos al segundo jugador y cambiamos el estado de la sala
        $room->player_2_id = Auth::id();
        $room->status = 'playing';
        $room->save();

        return response()->json(['message' => 'Te has unido a la sala con éxito.']);
    }

    //Obtiene las preguntas para una sala específica
    public function getQuestions(Room $room)
    {
        //Verificamos que el usuario que hace la petición sea parte de la sala
        if (Auth::id() !== $room->player_1_id && Auth::id() !== $room->player_2_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        //Obtenemos las preguntas usando los IDs almacenados
        $questions = Question::with('options')->whereIn('id', $room->questions_ids)->get();

        return QuestionResource::collection($questions);
    }

    //Obtiene el estado actual de la sala (puntuaciones y estado)
    public function getStatus(Room $room)
    {
        //Verificamos que el usuario que hace la petición sea parte de la sala
        if (Auth::id() !== $room->player_1_id && Auth::id() !== $room->player_2_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($room);
    }

    //Recibe la puntuación de un jugador y finaliza la partida si ambos han jugado
    public function submit(Request $request, Room $room)
    {
        $request->validate(['score' => 'required|integer']);

        $user = Auth::user();
        $score = $request->score;

        //Identificamos si el jugador es P1 o P2 y guardamos su puntuación
        if ($user->id === $room->player_1_id) {
            $room->score_p1 = $score;
        } elseif ($user->id === $room->player_2_id) {
            $room->score_p2 = $score;
        } else {
            return response()->json(['message' => 'No eres parte de esta sala.'], 403);
        }

        $room->save();

        //Si ambos jugadores han enviado su puntuación, marcamos la partida como finalizada
        if ($room->score_p1 !== null && $room->score_p2 !== null) {
            $room->status = 'finished';
            $room->save();
            return response()->json(['message' => 'Partida finalizada. Ambos jugadores han enviado sus resultados.']);
        }

        return response()->json(['message' => 'Puntuación guardada. Esperando al otro jugador.']);
    }
}

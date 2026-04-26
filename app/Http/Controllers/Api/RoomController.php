<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Question;
use App\Models\Logro;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\QuestionResource;
use App\Models\User;

class RoomController extends Controller
{
    //Crea una nueva sala de juego
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:20',
            'is_public' => 'boolean'
        ]);

        //Seleccionamos 10 IDs de preguntas de forma aleatoria
        $questionIds = Question::where('activa', 1)->inRandomOrder()->limit(10)->pluck('id');

        //Generamos un código único para la sala
        do {
            $code = Str::random(6);
        } while (Room::where('code', $code)->exists());

        $roomName = $request->name ?: 'Sala de ' . Auth::user()->name;

        //Creamos la sala en la base de datos
        $room = Room::create([
            'code' => $code,
            'name' => $roomName,
            'is_public' => $request->has('is_public') ? $request->is_public : true,
            'player_1_id' => Auth::id(),
            'questions_ids' => $questionIds,
        ]);

        //Devolvemos el código de la sala para que el jugador pueda compartirlo
        return response()->json(['code' => $room->code], 201);
    }

    //Permite a un segundo jugador unirse a una sala existente
    public function join(Request $request) //Informacion que la app le envia al servidor
    {
        $request->validate(['code' => 'required|string']); 

        //Comprobamos la primera fila donde coincide el código exacto O el nombre exacto de una sala abierta
        $room = Room::where('status', 'open')
                    ->where(function($query) use ($request) {
                        $query->where('code', $request->code)
                              ->orWhere('name', $request->code);
                    })->first();

        if (!$room) {
            return response()->json(['message' => 'No se ha encontrado ninguna sala disponible con ese código o nombre.'], 404);
        }
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

        return response()->json([
            'message' => 'Te has unido a la sala con éxito.',
            'code' => $room->code
        ]);
    }

    //Obtiene todas las salas abiertas para mostrarlas en el lobby
    public function getRooms()
    {
        //recuperamos las salas que tengan estado open, sean recientes (últimas 2h) y el creador no haya finalizado aún
        $rooms = Room::with('player1:id,name')
                    ->where('status', 'open')
                    ->where('is_public', true)
                    ->where('p1_finished', false) // Si el creador ya terminó, ocultarla de la pantalla
                    ->where('created_at', '>=', now()->subHours(2)) // Ocultarlas si quedaron abandonadas
                    ->where('player_1_id', '!=', Auth::id())
                    ->get();
        return response()->json($rooms);
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

        // Cargamos los nombres de los jugadores para devolverlos en la respuesta
        $player1 = User::find($room->player_1_id);
        $player2 = User::find($room->player_2_id);

        $roomData = $room->toArray();
        $roomData['player_1_name'] = $player1 ? $player1->name : 'Jugador 1';
        $roomData['player_2_name'] = $player2 ? $player2->name : 'Jugador 2';

        //devolvemos el estado real de cada jugador para no bloquear la pantalla de resultados
        $roomData['p1_finished'] = $room->p1_finished;
        $roomData['p2_finished'] = $room->p2_finished;

        //Si la partida está terminada, incluimos los logros recientes del usuario actual
        //para que la vista de resultados pueda mostrar la notificación
        if ($room->status === 'finished') {
            $user = Auth::user();
            $roomData['nuevos_logros'] = $this->obtenerLogrosRecientes($user);
        }

        return response()->json($roomData);
    }

    //Recibe la puntuación de un jugador y finaliza la partida si ambos han jugado
    public function submit(Request $request, Room $room)
    {
        $request->validate(['score' => 'required|integer']);

        $user = Auth::user();
        $score = $request->score;

        //Identificamos si el jugador es P1 o P2 y guardamos su puntuación y marcamos que terminó
        if ($user->id === $room->player_1_id) {
            $room->score_p1 = $score;
            $room->p1_finished = true; //marcamos como finalizado
        } elseif ($user->id === $room->player_2_id) {
            $room->score_p2 = $score;
            $room->p2_finished = true; //marcamos como finalizado
        } else {
            return response()->json(['message' => 'No eres parte de esta sala.'], 403);
        }

        // Sumar puntuación al total del usuario
        $userModel = User::find($user->id);
        if ($userModel) {
            $userModel->puntuacion += $score;
            $userModel->save();
        }

        // Comprobamos si ambos jugadores han terminado usando las nuevas flags
        if ($room->p1_finished && $room->p2_finished) {
            $room->status = 'finished';
        }

        // Guardamos todos los cambios en la BD
        $room->save();

        //Cuando la sala queda 'finished', evaluamos los logros de la partida 1vs1 para este jugador
        if ($room->status === 'finished') {
            $nuevosLogros = $this->evaluarLogros1vs1($user, $score, $room);
            return response()->json([
                'message' => 'Partida finalizada. Ambos jugadores han enviado sus resultados.',
                'nuevos_logros' => $nuevosLogros
            ]);
        }

        return response()->json(['message' => 'Puntuación guardada. Esperando al otro jugador.']);
    }

    //Evalua los logros relacionados con el modo de juego 1vs1
    //Devuelve SOLO los logros que el usuario NO tenía antes de esta partida
    private function evaluarLogros1vs1($user, int $puntuacion, Room $room): array
    {
        //1. Guardamos los IDs de logros que el usuario YA tenía
        $logrosPrevios = $user->logros()->pluck('logros.id')->toArray();

        $logros = Logro::all()->keyBy('codigo');
        $logrosAConceder = [];

        //LOGRO: Primera partida 1vs1 completada
        if ($logros->has('PRIMERA_1VS1')) {
            $logrosAConceder[] = $logros['PRIMERA_1VS1']->id;
        }

        //LOGRO: Ganar una partida 1vs1 comprobando si este usuario marcó más puntos que el rival
        $esP1 = $user->id === $room->player_1_id;
        $puntuacionRival = $esP1 ? $room->score_p2 : $room->score_p1;

        if ($puntuacion > $puntuacionRival && $logros->has('GANADOR_1VS1')) {
            $logrosAConceder[] = $logros['GANADOR_1VS1']->id;
        }

        //2. Asignamos todos de una vez
        $user->logros()->syncWithoutDetaching($logrosAConceder);

        //3. Filtramos solo los nuevos
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

    //Obtiene los logros que el usuario ha desbloqueado recientemente (últimos 60 segundos)
    //Se usa en getStatus para que el polling pueda mostrar la notificación de logros al otro jugador
    private function obtenerLogrosRecientes($user): array
    {
        return $user->logros()
            ->wherePivot('created_at', '>=', now()->subMinutes(1))
            ->get(['logros.nombre', 'logros.icono', 'logros.puntos'])
            ->toArray();
    }
}

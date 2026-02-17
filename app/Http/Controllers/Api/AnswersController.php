<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRespuestaRequest;
use App\Http\Resources\RespuestaResource;
use App\Models\Answers;
use App\Models\QuestionOption;
use Illuminate\Support\Carbon;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     * Obtiene todas las respuestas con sus relaciones
     */
    public function index()
    {
        // Cargar todas las respuestas con sus relaciones:
        // - user: el usuario que respondió
        // - question: la pregunta que se respondió
        // - opcion: la opción seleccionada
        // - partida: la partida a la que pertenece
        $answers = Answers::with(['user', 'question', 'opcion', 'partida'])
            ->orderBy('respondida_at', 'desc') // Ordenar por fecha de respuesta, las más recientes primero
            ->get();

        return RespuestaResource::collection($answers);
    }

    /**
     * Store a newly created resource in storage.
     * Crea una nueva respuesta y calcula automáticamente si es correcta y los puntos
     */
    public function store(StoreRespuestaRequest $request)
    {
        // Crear nueva respuesta
        $answer = new Answers();
        $answer->id_partida = $request->id_partida; // ID de la partida
        $answer->users_id = $request->user()->id; // ID del usuario autenticado que responde
        $answer->pregunta_id = $request->pregunta_id; // ID de la pregunta respondida
        $answer->opcion_id = $request->opcion_id; // ID de la opción seleccionada (puede ser null)
        $answer->respuesta_texto = $request->respuesta_texto; // Respuesta en texto (opcional)
        $answer->tiempo_ms = $request->tiempo_ms; // Tiempo que tardó en responder en milisegundos
        $answer->respondida_at = Carbon::now(); // Timestamp de cuando se respondió

        // Verificar si la respuesta es correcta comparando con la opción seleccionada
        if ($request->opcion_id) {
            // Buscar la opción seleccionada para ver si es correcta
            $opcion = QuestionOption::find($request->opcion_id);
            $answer->es_correcta = $opcion ? $opcion->es_correcta : false;
        } else {
            // Si no hay opción seleccionada, la respuesta es incorrecta
            $answer->es_correcta = false;
        }

        // Puntos básicos: 100 si es correcta, 0 si no
        // Aquí se podría implementar lógica más compleja (bonus por rapidez, etc.)
        $answer->puntos_obtenidos = $answer->es_correcta ? 100 : 0;

        $answer->save();

        // Retornar la respuesta creada con sus relaciones
        return new RespuestaResource($answer->load(['user', 'question', 'opcion']));
    }

    /**
     * Display the specified resource.
     * Muestra una respuesta específica por su ID
     */
    public function show(Answers $answer)
    {
        // Laravel inyecta automáticamente el modelo usando Route Model Binding
        $answer->load(['user', 'question', 'opcion', 'partida']);
        return new RespuestaResource($answer);
    }

    /**
     * Update the specified resource in storage.
     * Actualiza una respuesta y recalcula si es correcta y los puntos
     */
    public function update(Answers $answer, StoreRespuestaRequest $request)
    {
        // Actualizar los campos de la respuesta
        $answer->opcion_id = $request->opcion_id;
        $answer->respuesta_texto = $request->respuesta_texto;
        $answer->tiempo_ms = $request->tiempo_ms;

        // Recalcular si es correcta basándose en la nueva opción seleccionada
        if ($request->opcion_id) {
            $opcion = QuestionOption::find($request->opcion_id);
            $answer->es_correcta = $opcion ? $opcion->es_correcta : false;
        }

        // Recalcular puntos básicos: 100 si es correcta, 0 si no
        $answer->puntos_obtenidos = $answer->es_correcta ? 100 : 0;

        $answer->save();

        return new RespuestaResource($answer->load(['user', 'question', 'opcion']));
    }

    /**
     * Remove the specified resource from storage.
     * Elimina una respuesta
     */
    public function destroy(Answers $answer)
    {
        // Eliminar la respuesta
        $answer->delete();

        // Retornar respuesta 204 No Content (eliminación exitosa)
        return response()->noContent();
    }
}

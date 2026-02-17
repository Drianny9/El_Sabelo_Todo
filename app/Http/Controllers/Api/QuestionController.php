<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     * Obtiene todas las preguntas con sus relaciones (categoría, usuario creador y opciones)
     */
    public function index()
    {
        // Cargar todas las preguntas con sus relaciones usando 'with' para evitar el problema N+1
        // Esto hace que en una sola consulta se traigan todas las relaciones necesarias
        $questions = Question::with(['category', 'createdByUser', 'options'])
            ->orderBy('created_at', 'desc') // Ordenar por fecha de creación, las más recientes primero
            ->get();

        // Convertir la colección de preguntas a formato JSON usando el Resource
        return QuestionResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     * Crea una nueva pregunta con sus opciones de respuesta
     */
    public function store(StoreQuestionRequest $request)
    {
        // Crear nueva pregunta con los datos del request
        $question = new Question();
        $question->categories_id = $request->categories_id; // ID de la categoría
        $question->tipo = $request->tipo; // Tipo: 'multiple' o 'boolean'
        $question->enunciado = $request->enunciado; // El texto de la pregunta
        $question->creada_por_user_id = $request->user()->id; // ID del usuario autenticado que crea la pregunta
        // Si no viene el campo activa en el request, por defecto será true
        $question->activa = $request->has('activa') ? $request->activa : true;
        $question->save();

        // Crear las opciones de respuesta asociadas a la pregunta
        // Se espera un array 'opciones' en el request con cada opción
        if ($request->has('opciones')) {
            foreach ($request->opciones as $index => $opcion) {
                // Usar la relación 'options()' para crear las opciones relacionadas
                // Si no viene el orden en el request, usar el índice del array + 1
                $orden = isset($opcion['orden']) ? $opcion['orden'] : ($index + 1);
                
                $question->options()->create([
                    'texto' => $opcion['texto'], // Texto de la opción
                    'es_correcta' => $opcion['es_correcta'], // Si es la respuesta correcta (true/false)
                    'orden' => $orden
                ]);
            }
        }

        // Retornar la pregunta creada con todas sus relaciones cargadas
        return new QuestionResource($question->load(['category', 'options', 'createdByUser']));
    }

    /**
     * Display the specified resource.
     * Muestra una pregunta específica por su ID
     */
    public function show(Question $question)
    {
        // Laravel inyecta automáticamente el modelo Question usando Route Model Binding
        // Solo necesitamos cargar sus relaciones
        $question->load(['category', 'options', 'createdByUser']);
        return new QuestionResource($question);
    }

    /**
     * Update the specified resource in storage.
     * Actualiza una pregunta existente y sus opciones
     */
    public function update(Question $question, StoreQuestionRequest $request)
    {
        // Actualizar los campos de la pregunta
        $question->categories_id = $request->categories_id;
        $question->tipo = $request->tipo;
        $question->enunciado = $request->enunciado;
        // Si no viene el campo activa, mantener el valor que ya tiene
        $question->activa = $request->has('activa') ? $request->activa : $question->activa;
        $question->save();

        // Actualizar opciones: eliminar las existentes y crear las nuevas
        // Esto es más simple que intentar actualizar una por una
        if ($request->has('opciones')) {
            // Eliminar todas las opciones anteriores de esta pregunta
            $question->options()->delete();
            
            // Crear las nuevas opciones
            foreach ($request->opciones as $index => $opcion) {
                // Si no viene el orden en el request, usar el índice del array + 1
                $orden = isset($opcion['orden']) ? $opcion['orden'] : ($index + 1);
                
                $question->options()->create([
                    'texto' => $opcion['texto'],
                    'es_correcta' => $opcion['es_correcta'],
                    'orden' => $orden
                ]);
            }
        }

        // Retornar la pregunta actualizada con sus relaciones
        return new QuestionResource($question->load(['category', 'options', 'createdByUser']));
    }

    /**
     * Remove the specified resource from storage.
     * Elimina una pregunta (y sus opciones en cascada)
     */
    public function destroy(Question $question)
    {
        // Eliminar la pregunta
        // Las opciones se eliminan automáticamente si hay configurado onDelete('cascade') en la migración
        $question->delete();

        // Retornar respuesta 204 No Content (eliminación exitosa sin contenido)
        return response()->noContent();
    }
}

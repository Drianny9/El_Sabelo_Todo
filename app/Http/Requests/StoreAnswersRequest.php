<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRespuestaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_partida' => 'required|exists:games,id',
            'pregunta_id' => 'required|exists:questions,id',
            'opcion_id' => 'nullable|exists:question_options,id',
            'respuesta_texto' => 'nullable|string',
            'tiempo_ms' => 'required|integer|min:0'
        ];
    }

    public function messages()
    {
        return [
            'id_partida.required' => 'La partida es obligatoria',
            'id_partida.exists' => 'La partida seleccionada no existe',
            'pregunta_id.required' => 'La pregunta es obligatoria',
            'pregunta_id.exists' => 'La pregunta seleccionada no existe',
            'opcion_id.exists' => 'La opción seleccionada no existe',
            'tiempo_ms.required' => 'El tiempo es obligatorio',
            'tiempo_ms.integer' => 'El tiempo debe ser un número entero',
            'tiempo_ms.min' => 'El tiempo no puede ser negativo'
        ];
    }
}

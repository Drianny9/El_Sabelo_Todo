<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'categories_id' => 'required|exists:categories,id',
            'tipo' => 'required|in:multiple,boolean',
            'enunciado' => 'required|string|min:10',
            'activa' => 'sometimes|boolean',
            'opciones' => 'required|array|min:2|max:4',
            'opciones.*.texto' => 'required|string',
            'opciones.*.es_correcta' => 'required|boolean',
            'opciones.*.orden' => 'sometimes|integer'
        ];
    }

    public function messages()
    {
        return [
            'categories_id.required' => 'La categoría es obligatoria',
            'categories_id.exists' => 'La categoría seleccionada no existe',
            'tipo.required' => 'El tipo de pregunta es obligatorio',
            'tipo.in' => 'El tipo debe ser: multiple o boolean',
            'enunciado.required' => 'El enunciado es obligatorio',
            'enunciado.min' => 'El enunciado debe tener al menos 10 caracteres',
            'opciones.required' => 'Debes agregar opciones de respuesta',
            'opciones.min' => 'Debes agregar al menos 2 opciones',
            'opciones.max' => 'Puedes agregar máximo 4 opciones'
        ];
    }
}

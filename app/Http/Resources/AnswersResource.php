<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RespuestaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'id_partida' => $this->id_partida,
            'users_id' => $this->users_id,
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ];
            }),
            'pregunta_id' => $this->pregunta_id,
            'pregunta' => new QuestionResource($this->whenLoaded('question')),
            'opcion_id' => $this->opcion_id,
            'opcion' => new QuestionOptionResource($this->whenLoaded('opcion')),
            'respuesta_texto' => $this->respuesta_texto,
            'es_correcta' => $this->es_correcta,
            'tiempo_ms' => $this->tiempo_ms,
            'puntos_obtenidos' => $this->puntos_obtenidos,
            'respondida_at' => $this->respondida_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'categories_id' => $this->categories_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tipo' => $this->tipo,
            'enunciado' => $this->enunciado,
            'creada_por_user_id' => $this->creada_por_user_id,
            //Usamos whenLoaded para evitar el problema N+1. Si la relación no se ha cargado, no la devuelve.
            'created_by' => $this->whenLoaded('createdByUser', function () {
                return [
                    'id' => $this->createdByUser->id,
                    'name' => $this->createdByUser->name,
                    'email' => $this->createdByUser->email
                ];
            }),
            'activa' => $this->activa,
            'opciones' => QuestionOptionResource::collection($this->whenLoaded('options')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pregunta_id' => $this->pregunta_id,
            'texto' => $this->texto,
            'es_correcta' => $this->es_correcta,
            'orden' => $this->orden
        ];
    }
}

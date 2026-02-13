<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'estado',
        'pin_codigo',
        'creada_por_user_id',
        'finalizada_at'
    ];

    protected $casts = [
        'finalizada_at' => 'datetime'
    ];

    // Relación con el usuario creador
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'creada_por_user_id');
    }

    // Relación con preguntas
    public function preguntas()
    {
        return $this->belongsToMany(Question::class, 'partida_pregunta', 'id_partida', 'pregunta_id')
            ->withPivot('orden_pregunta', 'tiempo_limite_seg', 'puntos_acierto', 'puntos_bonus_rapidez')
            ->withTimestamps();
    }

    // Relación con respuestas
    public function respuestas()
    {
        return $this->hasMany(Answers::class, 'id_partida');
    }
}

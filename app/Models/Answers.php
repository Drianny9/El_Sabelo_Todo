<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_partida',
        'users_id',
        'pregunta_id',
        'opcion_id',
        'respuesta_texto',
        'es_correcta',
        'tiempo_ms',
        'puntos_obtenidos',
        'respondida_at'
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
        'respondida_at' => 'datetime'
    ];

    // Relación con partida
    public function partida()
    {
        return $this->belongsTo(Games::class, 'id_partida');
    }

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Relación con pregunta
    public function question()
    {
        return $this->belongsTo(Question::class, 'pregunta_id');
    }

    // Relación con opción seleccionada
    public function opcion()
    {
        return $this->belongsTo(QuestionOption::class, 'opcion_id');
    }
}

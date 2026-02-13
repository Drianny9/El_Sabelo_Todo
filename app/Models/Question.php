<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'categories_id',
        'tipo',
        'enunciado',
        'creada_por_user_id',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    // Relación con categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    // Relación con el usuario creador
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'creada_por_user_id');
    }

    // Relación con opciones
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'pregunta_id');
    }

    // Relación con partidas
    public function partidas()
    {
        return $this->belongsToMany(Games::class, 'partida_pregunta', 'pregunta_id', 'id_partida')
            ->withPivot('orden_pregunta', 'tiempo_limite_seg', 'puntos_acierto', 'puntos_bonus_rapidez')
            ->withTimestamps();
    }

    // Relación con respuestas
    public function respuestas()
    {
        return $this->hasMany(Answers::class, 'pregunta_id');
    }
}

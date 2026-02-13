<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregunta_id',
        'texto',
        'es_correcta',
        'orden'
    ];

    protected $casts = [
        'es_correcta' => 'boolean'
    ];

    // Relación con pregunta
    public function question()
    {
        return $this->belongsTo(Question::class, 'pregunta_id');
    }

    // Relación con respuestas
    public function respuestas()
    {
        return $this->hasMany(Answers::class, 'opcion_id');
    }
}

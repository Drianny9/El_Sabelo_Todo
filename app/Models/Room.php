<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    //Asignación masiva de campos
    protected $fillable = [
        'code',
        'name',
        'is_public',
        'player_1_id',
        'player_2_id',
        'questions_ids',
        'score_p1',
        'score_p2',
        'p1_finished',
        'p2_finished',
        'status',
    ];

    //Casteo de atributos para que Laravel los trate como tipos de datos específicos
    protected $casts = [
        'questions_ids' => 'array', //Convierte el JSON de la BD a un array de PHP automáticamente
    ];

    //Relación con el usuario que creó la sala (Jugador 1)
    public function player1()
    {
        return $this->belongsTo(User::class, 'player_1_id'); //El segundo parametro lo usamos para que encuentre el jugador 1 explicitamente, ya que al tener 2 users eloquent cogeria el primero que encontrara
    }

    //Relación con el usuario que se unió a la sala (Jugador 2)
    public function player2()
    {
        return $this->belongsTo(User::class, 'player_2_id');
    }
}

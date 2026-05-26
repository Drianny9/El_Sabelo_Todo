<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Room extends Model
{
    use HasFactory, InteractsWithMedia;

    //Imagen por defecto cuando el user no suba ninguna
    private const DEFAULT_ROOM_IMAGE_URL = '/images/Home/Avatar_solitario.webp';
    private const DEFAULT_ROOM_IMAGE_PATH = 'images/Home/Avatar_solitario.webp';

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

    //Cuando se devuelve una sala como JSON incluye image
    protected $appends = ['image'];

    //Si la sala tiene imagen devuelve su URL, si no deja imagen por defecto
    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl('images/rooms') ?: self::DEFAULT_ROOM_IMAGE_URL;
    }

    //Definimos la coleccion de imagenes para salas
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images/rooms')
            // Solo permite una imagen por sala.
            // Si se sube otra en el futuro, reemplaza la anterior.
            ->singleFile()

            // URL por defecto cuando no hay imagen subida.
            ->useFallbackUrl(self::DEFAULT_ROOM_IMAGE_URL)

            // Ruta física por defecto para Spatie.
            ->useFallbackPath(public_path(self::DEFAULT_ROOM_IMAGE_PATH));
    }

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

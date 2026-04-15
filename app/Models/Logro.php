<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'icono',
        'puntos'
    ];

    /**
     * Relación con los usuarios que han conseguido este logro.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps(); //Manejamos created_at y updated_at
    }
}

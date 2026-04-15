<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Logro;

class LogroController extends Controller
{
    // Devuelve el catálogo completo de logros disponibles
    public function index()
    {
        return response()->json(Logro::all());
    }

    // Devuelve solo los logros que ha desbloqueado el usuario actual
    public function misLogros()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        // Devolvemos el usuario completo cargando sus logros mediante Eager Loading
        // También incluimos la puntuación actual que tiene el usuario en la BD
        return response()->json([
            'puntuacion' => $user->puntuacion,
            'logros' => $user->logros
        ]);
    }
}

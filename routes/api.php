<?php

use App\Http\Controllers\Api\AnswersController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\LogroController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Lo que tengamos dentro del Route group solo lo podran usar los usuarios logueados
Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::post('/game/save-score', [GameController::class, 'saveScore']);

    Route::apiResource('users', UserController::class);
    Route::post('users/updateimg', [UserController::class,'updateimg']);


    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('answers', AnswersController::class);

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);
    
    Route::get('/user', [ProfileController::class, 'user']);
    Route::get('/user/signin', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

    // Rutas para los Logros
    Route::get('/logros', [LogroController::class, 'index']);
    Route::get('/logros/mis-logros', [LogroController::class, 'misLogros']);

    //Rutas para el juego 1vs1
    Route::get('/rooms', [RoomController::class, 'getRooms']); //endpoint para listar las salas abiertas en el lobby
    Route::post('/rooms', [RoomController::class, 'create']);
    Route::post('/rooms/join', [RoomController::class, 'join']);
    Route::get('/rooms/{room:code}/questions', [RoomController::class, 'getQuestions']);
    Route::post('/rooms/{room:code}/submit', [RoomController::class, 'submit']);
    Route::get('/rooms/{room:code}/status', [RoomController::class, 'getStatus']);

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});

//Si esta fuera del grupo cualquiera puede usarlo
Route::get('category-list', [CategoryController::class, 'getList']);
Route::apiResource('posts', PostController::class);

Route::get('/game/questions', [GameController::class, 'getRandomQuestions']);

Route::get('ranking', [UserController::class, 'getRanking']);
Route::get('social/users', [UserController::class, 'getSocialUsers']);


// Route::get('/posts', [PostController::class, 'index']);
// Route::get('/posts/{post}', [PostController::class, 'show']);
// Route::delete('/posts/{post}', [PostController::class, 'destroy']);



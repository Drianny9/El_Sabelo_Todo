<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */

    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $users = User::when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%' . request('search_title') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%' . request('search_global') . '%');
                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(500);

        return UserResource::collection($users);
    }

    // userswithtasks removed

    // usersfromgroup removed

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $role = Role::find($request->role_id);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->surname1 = $request->surname1;
        $user->surname2 = $request->surname2;

        $user->password = Hash::make($request->password);

        if ($user->save()) {
            if ($role) {
                $user->assignRole($role);
            }
            return new UserResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function show(User $user)
    {
        $user->load(['roles', 'logros']);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $role = Role::find($request->role_id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->surname1 = $request->surname1;
        $user->surname2 = $request->surname2;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password) ?? $user->password;
        }
        if ($user->save()) {
            if ($role) {
                $user->syncRoles($role);
            }

            return new UserResource($user);
        }
    }


    public function updateimg(Request $request)
    {
        $user = User::find($request->id);

        if ($request->hasFile('picture')) {
            $user->media()->delete();
            $media = $user->addMediaFromRequest('picture')->preservingOriginal()->toMediaCollection('images-users');
        }
        $user =  User::with('media')->find($request->id);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('user-delete');
        $user->delete();

        return response()->noContent();
    }

    // En RankingController.php o UserController.php
    public function getRanking()
    {
        // Traemos ID, para identificar el usuario actual en el front
        return User::select('id', 'name', 'alias', 'puntuacion')
            ->orderBy('puntuacion', 'desc')
            ->limit(50) // Aumentamos un poco el límite para el ranking global
            ->get();
    }

    /**
     * Devuelve una lista aleatoria de usuarios para la sección Social de la home.
     * Solo expone datos públicos (alias, puntuacion, avatar). Sin auth requerida.
     */
    public function getSocialUsers()
    {
        $currentId = Auth::id(); //null si no hay sesión

        $users = User::select('id', 'alias', 'puntuacion')
            ->when($currentId, fn($q) => $q->where('id', '!=', $currentId))
            ->inRandomOrder()
            ->limit(20)
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'alias'      => $u->alias ?? 'Jugador',
                'puntuacion' => $u->puntuacion ?? 0,
                'avatar'     => $u->getFirstMediaUrl('images/users') ?: null,
            ]);

        return response()->json($users);
    }
}

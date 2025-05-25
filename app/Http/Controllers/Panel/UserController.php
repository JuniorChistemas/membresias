<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Local;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listUsers()
    {
        //Gate::authorize('viewAny', User::class);
        $users = User::with('local')->orderBy('id', 'asc')->paginate(10);
        return response()->json([
            'success' => true,
            'users' => UserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ],
        ]);
    }


    public function index()
    {
        Gate::authorize('viewAny', User::class, User::class);
        return Inertia::render('Panel/Users/indexUser');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locals = Local::where('status', true)->select('id', 'name')->get();
        $roles = Role::select('name')->get();
        return Inertia::render('Panel/Users/components/createUser', [
            'locals' => $locals,
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = true;
        if ($validated['photo']) {
            $validated['photo'] = $request->file('photo')->store('/users', 'public');
            $url = url('storage/' . $validated['photo']);
            $validated['photo'] = $url;
        }
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'photo' => $validated['photo'],
            'email' => $validated['email'],
            'local_id' => $validated['local_id'],
            'password' => $validated['password'],
            'status' => $validated['status'],
        ]);
        $user->assignRole($validated['role']);
        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'redirect_url' => route('panel.users.index'),
            'user' => new UserResource($user),
        ])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $validated['password'] = isset($validated['password']) ? Hash::make($validated['password']) : $user->password;
        $validated['local_id'] = $validated['local_id'] ?? $user->local_id;
        if (isset($validated['photo'])) {
            //delete photo user
            if ($user->photo) {
                $path = str_replace(url('storage/'), '', $user->photo);
                Storage::disk('public')->delete($path);
            }
            $validated['photo'] = $request->file('photo')->store('/users', 'public');
            $url = url('storage/' . $validated['photo']);
            $validated['photo'] = $url;
        }
        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'photo' => $validated['photo'] ?? $user->photo,
            'email' => $validated['email'],
            'local_id' => $validated['local_id'],
            'status' => $validated['status'],
        ]);
        $user->syncRoles($validated['role']);
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'user' => new UserResource($user),
        ]);
        return response()->json([
            'datos' => $validated,
        ]);
    }

    // public function updateUser(UpdateUserRequest $request, User $user)
    // {
    //     $validated = $request->validated();
    //     if (isset($validated['password'])) {
    //         $validated['password'] = Hash::make($validated['password']);
    //     }
    //     if (isset($validated['photo'])) {
    //         //delete photo user
    //         if ($user->photo) {
    //             $path = str_replace(url('storage/'), '', $user->photo);
    //             Storage::disk('public')->delete($path);
    //         }
    //         $validated['photo'] = $request->file('photo')->store('/users', 'public');
    //         $url = url('storage/' . $validated['photo']);
    //         $validated['photo'] = $url;
    //     }
    //     $user->update([
    //         'name' => $validated['name'],
    //         'username' => $validated['username'],
    //         'photo' => $validated['photo'] ?? $user->photo,
    //         'email' => $validated['email'],
    //         'local_id' => $validated['local_id'],
    //         'status' => $validated['status'],
    //     ]);
    //     if (isset($validated['role'])) {
    //         $user->syncRoles($validated['role']);
    //     }
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Usuario actualizado correctamente',
    //         'user' => new UserResource($user),
    //     ]);
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //delete photo user
        if ($user->photo) {
            $path = str_replace(url('storage/'), '', $user->photo);
            Storage::disk('public')->delete($path);
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente',
            'photo' => $path,
        ]);
    }
}

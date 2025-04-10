<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Enterprise;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Define as permissões de acesso do controller
     */
    public static function middleware(): Middleware
    {
        return new Middleware([
            'auth',
            'permission:user-list' => ['index'],
            'permission:user-create' => ['create', 'store'],
            'permission:user-view' => ['show'],
            'permission:user-edit' => ['edit', 'update'],
            'permission:user-delete' => ['destroy'],
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles', 'enterprise'])->get();

        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $enterprises = Enterprise::all();

        return Inertia::render('Users/Form', [
            'roles' => $roles,
            'enterprises' => $enterprises,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        try {
            \Log::info('Roles sendo enviadas para criação:', ['roles' => $data['roles'] ?? []]);
            $this->userService->createUser(
                $data,
                $data['phone_numbers'] ?? [],
                $data['roles'] ?? []
            );

            return redirect()->route('users.index')
                ->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao criar usuário:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'phoneNumbers', 'enterprise']);

        return Inertia::render('Users/Show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load(['roles', 'phoneNumbers']);
        $roles = Role::all();
        $enterprises = Enterprise::all();

        // Pega apenas os nomes das roles
        $userRoles = $user->roles->pluck('name')->toArray();

        \Log::info('Roles do usuário para edição:', ['user_id' => $user->id, 'roles' => $userRoles]);

        return Inertia::render('Users/Form', [
            'user' => $user,
            'roles' => $roles,
            'enterprises' => $enterprises,
            'userRoles' => $userRoles,
            'phoneNumbers' => $user->phoneNumbers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        try {
            \Log::info('Roles sendo enviadas para atualização:', [
                'user_id' => $user->id,
                'roles' => $data['roles'] ?? []
            ]);


            $this->userService->updateUser(
                $user,
                $data,
                $data['phone_numbers'] ?? [],
                $data['roles'] ?? []
            );

            return redirect()->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
dd($e);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);

            return redirect()->route('users.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}

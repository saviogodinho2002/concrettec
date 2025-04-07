<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:user-list', only:['index']),
            new Middleware('permission:user-create', only:['create', 'store']),
            new Middleware('permission:user-view', only:['show']),
            new Middleware('permission:user-edit', only:['edit', 'update']),
            new Middleware('permission:user-delete', only:['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('master')) {
            $users = User::all();
        } else {
            $users = $this->userService->getByEnterprise($user->enterprise_id);
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {

        $this->userService->create($request->validated());
        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso');
    }

    public function show(User $user)
    {
        $authUser = request()->user();


        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $authUser = request()->user();

        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {

        $this->userService->update($user, $request->validated());
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(User $user)
    {

        $this->userService->delete($user);
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso');
    }
}

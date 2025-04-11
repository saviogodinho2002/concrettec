<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            'auth',
            new Middleware('permission:roles_permissions-list', only: ['index']),
            new Middleware('permission:roles_permissions-create', only: ['create', 'store']),
            new Middleware('permission:roles_permissions-view', only: ['show']),
            new Middleware('permission:roles_permissions-edit', only: ['edit', 'update']),
            new Middleware('permission:roles_permissions-delete', only: ['destroy']),
        ];
    }
    public function index()
    {

        $roles = Role::with('permissions')->get();

        return inertia('RolesPermissions/Index', [
            'roles' => $roles
        ]);
    }

    public function update(Request $request, \Spatie\Permission\Models\Role $role)
    {

        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'required|string|exists:permissions,name'
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->back()->with('success', 'Permissões atualizadas com sucesso!');
    }
}

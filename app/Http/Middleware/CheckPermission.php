<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Usuário não autenticado');
        }

        if ($user->hasRole('master')) {
            return $next($request);
        }

        foreach ($user->roles as $role) {
            if ($role->hasPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Acesso não autorizado');
    }
} 
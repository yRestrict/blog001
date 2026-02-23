<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();

        // Se não estiver logado
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Se estiver banido
        if ($user->isBanned()) {
            abort(403, 'Sua conta foi banida.');
        }

        // Se o role do usuário não está na lista permitida
        if (!in_array($user->role->value, $roles)) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('admin.login')->with('fail', 'Sua conta foi banida.');
        }

        // Se o role do usuário não está na lista permitida
        if (!in_array($user->role->value, $roles)) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }
}
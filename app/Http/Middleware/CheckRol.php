<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRol
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        // Si no está logueado o no tiene rol permitido → al home
        if (!$user || !in_array($user->rol_id, $roles)) {
            return redirect()->route('/');
        }

        return $next($request);
    }
}

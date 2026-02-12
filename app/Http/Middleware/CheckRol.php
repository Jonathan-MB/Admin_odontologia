<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRol
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$rol): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->rol_id, $rol)) {
            return response()->json([
                'message' => 'No autorizado.'
            ], 403);
        }

        return $next($request);
    }
}

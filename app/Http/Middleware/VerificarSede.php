<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSede
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('sede')) {
            return redirect()->route('sedes.index');
        }

        return $next($request);
    }
}

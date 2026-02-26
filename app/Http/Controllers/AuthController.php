<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo no es válido.',
            'password.required' => 'La contraseña es obligatoria.'
        ]);

        if (!Auth::attempt($request->only('correo', 'password'))) {
            return back()
                ->withErrors([
                    'correo' => 'Correo o contraseña incorrecta'
                ])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect('sedes');
    }

    public function logout(Request $request)
    {

        Auth::logout();


        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect()->route('login');
    }
}

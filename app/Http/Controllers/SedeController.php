<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{

    public function index(Request $request)
    {
        $sedes = Sede::all();

        return view('sedes', compact('sedes'));
    }















    public function guardarSede(Request $request)
    {
        session([
            'sede' => [
                'id'        => $request->id,
                'nombre'    => $request->nombre,
                'nit'       => $request->nit,
                'direccion' => $request->direccion,
                'telefono'  => $request->telefono,
                'celular'  => $request->celular,

            ]
        ]);

        return response()->json(['ok' => true]);
    }
}

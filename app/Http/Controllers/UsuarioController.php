<?php

namespace App\Http\Controllers;

use App\Filters\UsuarioFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UsuarioCollection;
use App\Http\Resources\UsuarioResource;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{




    public function index(Request $request)
    {

        $usuarios   = Usuario::all();
        $rols       = Rol::all();
        
        return view('usuarios', compact('usuarios', 'rols'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioRequest $request)
    {
        return new UsuarioResource(Usuario::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return new UsuarioResource($usuario);
    }





public function edit(Usuario $usuario)
{
    $rols = Rol::all();
    return view('usuarioEditar', compact('usuario', 'rols'));
}


    /**
     * Update the specified resource in storage.
     */
    
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $usuario->fill($data);

        // No hubo cambios
        if (! $usuario->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $usuario->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $usuario->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

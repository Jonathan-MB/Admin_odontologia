<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{

    public function index(Request $request)
    {

        $usuarios   = Usuario::all();
        $rols       = Rol::all();

        return view('usuarios', compact('usuarios', 'rols'));
    }


    public function store(StoreUsuarioRequest $request)
    {
        Usuario::create($request->validated());
    return redirect()->back()->with('mensajeCreado', 'Usuario creado correctamente');
    }






    public function edit(Usuario $usuario)
    {
        $rols = Rol::all();
        return view('usuarioEditar', compact('usuario', 'rols'));
    }



    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        $data = $request->validated();

        if (empty($data['contrasena'])) {
            unset($data['contrasena']);
        }

        $usuario->fill($data);
        $usuario->save();

        return redirect()->route('usuarios.index')->with('mensajeActualizado', 'Usuario actualizado correctamente');
    }
}

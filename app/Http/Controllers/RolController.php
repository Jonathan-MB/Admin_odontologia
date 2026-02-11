<?php

namespace App\Http\Controllers;

use App\Filters\RolFilter;
use App\Http\Resources\RolCollection;
use App\Http\Resources\RolResource;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filter = new RolFilter();
        $queryItems = $filter->transform($request);
        $includeUsuario = $request->query('includeUsuarios');
        $rol = Rol::where($queryItems);
        if ($includeUsuario) {
            $rol = $rol->with('usuarios');
        }

        return new RolCollection($rol->paginate()->appends($request->query()));
    }



    /**
     * Display the specified resource.
     */
    public function show(Rol $rol)
    {
        $includeUsuarios = Request()->query('includeUsuarios');
        if ($includeUsuarios) {
            return new RolResource($rol->loadMissing('usuarios'));
        };
        return new RolResource($rol);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rol $rol)
    {
        return response()->json([
            'message' => 'No se permite editar roles'
        ], 403);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}
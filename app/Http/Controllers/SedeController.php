<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Filters\SedeFilter;
use App\Http\Requests\StoreSedeRequest;
use App\Http\Requests\UpdateSedeRequest;
use App\Http\Resources\EspecialistaCollection;
use App\Http\Resources\SedeCollection;
use App\Http\Resources\SedeResource;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{

    public function index(Request $request)
    {
        $sedes = Sede::all();

        return view('sedes', compact('sedes'));
    }



    public function store(StoreSedeRequest $request)
    {
        return new SedeResource(Sede::create($request->validated()));
    }



    public function show(Sede $sede)
    {
        $includeEspecialista = Request()->query('includeEspecialistas');
        if ($includeEspecialista) {
            return new SedeResource($sede->loadMissing('especialistas'));
        };
        return new SedeResource($sede);
    }



    public function update(UpdateSedeRequest $request, Sede $sede)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $sede->fill($data);

        // No hubo cambios
        if (! $sede->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $sede->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $sede->fresh()
        ], 200);
    }



    public function destroy(Sede $sede)
    {
        $sede->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
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

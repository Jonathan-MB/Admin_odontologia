<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Filters\EspecialistaFilter;
use App\Http\Requests\StoreEspecialistaRequest;
use App\Http\Requests\UpdateEspecialistaRequest;
use App\Http\Resources\EspecialistaCollection;
use App\Http\Resources\EspecialistaResource;
use App\Models\Especialista;
use App\Models\Sede;
use Illuminate\Http\Request;

class EspecialistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {

        $especialistas   = Especialista::all();
        $sedes       = Sede::all();

        return view('especialista', compact('especialistas', 'sedes'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEspecialistaRequest $request)
    {
        return new EspecialistaResource(Especialista::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialista $especialista)
    {
        return new EspecialistaResource($especialista);
    }

    
    public function edit(Especialista $especialista)
    {
        $sedes = Sede::all();
        return view('especialistaEditar', compact('especialista', 'sedes'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEspecialistaRequest $request, Especialista $especialista)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $especialista->fill($data);

        // No hubo cambios
        if (! $especialista->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $especialista->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $especialista->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialista $especialista)
    {
        $especialista->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

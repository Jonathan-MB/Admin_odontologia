<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Filters\GrupoFilter;
use App\Http\Requests\StoreGrupoRequest;
use App\Http\Requests\UpdateGrupoRequest;
use App\Http\Resources\DienteResource;
use App\Http\Resources\GrupoCollection;
use App\Http\Resources\GrupoResource;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $filter = new GrupoFilter();
        $queryItems= $filter->transform($request);
        $includeDientes = $request->query('includeDientes');
        $grupos = Grupo::where($queryItems);
        if ($includeDientes){
            $grupos = $grupos->with ('dientes');
        }

        return new GrupoCollection($grupos->paginate()->appends($request->query()));


    
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGrupoRequest $request)
    {
        return new GrupoResource(Grupo::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {

        $includeDientes = Request()->query('includeDientes');
        if($includeDientes){
        return new GrupoResource($grupo->loadMissing('dientes'));
        };
        return new GrupoResource($grupo);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGrupoRequest $request, Grupo $grupo)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $grupo->fill($data);

        // No hubo cambios
        if (! $grupo->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $grupo->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $grupo->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}
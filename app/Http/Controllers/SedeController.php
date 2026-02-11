<?php

namespace App\Http\Controllers;

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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filter = new SedeFilter();
        $queryItems= $filter->transform($request);
        $includeEspecialista = $request->query('includeEspecialistas');
        $sede = Sede::where($queryItems);
        if ($includeEspecialista){
            $sede = $sede->with ('especialistas');
        }

        return new SedeCollection($sede->paginate()->appends($request->query()));


    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSedeRequest $request)
    {
        return new SedeResource(Sede::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Sede $sede)
    {
        $includeEspecialista = Request()->query('includeEspecialistas');
        if($includeEspecialista){
        return new SedeResource($sede->loadMissing('especialistas'));
        };
        return new SedeResource($sede);
    }



    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sede $sede)
    {
        $sede->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}
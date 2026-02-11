<?php

namespace App\Http\Controllers;

use App\Filters\TipoDocumentoFilter;
use App\Http\Requests\StoreTipoDocumentoRequest;
use App\Http\Requests\UpdateTipoDocumentoRequest;
use App\Http\Resources\TipoDocumentoCollection;
use App\Http\Resources\TipoDocumentoResource;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new TipoDocumentoFilter();
        $queryItems= $filter->transform($request);
        $tipoDocumento = TipoDocumento::where($queryItems);

        return new TipoDocumentoCollection($tipoDocumento->paginate()->appends($request->query()));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoDocumentoRequest $request)
    {
        return new TipoDocumentoResource(TipoDocumento::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoDocumento $tipoDocumento)
    {
        return new TipoDocumentoResource($tipoDocumento);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoDocumentoRequest $request, TipoDocumento $tipoDocumento)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $tipoDocumento->fill($data);

        // No hubo cambios
        if (! $tipoDocumento->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $tipoDocumento->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $tipoDocumento->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoDocumento $tipoDocumento)
    {
        $tipoDocumento->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}
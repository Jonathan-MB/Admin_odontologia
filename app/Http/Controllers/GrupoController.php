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

    public function index(Request $request)
    {

        $filter = new GrupoFilter();
        $queryItems = $filter->transform($request);
        $includeDientes = $request->query('includeDientes');
        $grupos = Grupo::where($queryItems);
        if ($includeDientes) {
            $grupos = $grupos->with('dientes');
        }

        return new GrupoCollection($grupos->paginate()->appends($request->query()));
    }



    public function store(StoreGrupoRequest $request)
    {
        return new GrupoResource(Grupo::create($request->validated()));
    }



    public function show(Grupo $grupo)
    {

        $includeDientes = Request()->query('includeDientes');

        if ($includeDientes) {

            return new GrupoResource($grupo->loadMissing('dientes'));
        };

        return new GrupoResource($grupo);
    }



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



    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

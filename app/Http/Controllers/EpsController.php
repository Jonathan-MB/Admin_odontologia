<?php

namespace App\Http\Controllers;


use App\Filters\EpsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEpsRequest;
use App\Http\Requests\UpdateEpsRequest;
use App\Http\Resources\EpsCollection;
use App\Http\Resources\EpsResource;
use App\Models\Eps;
use Illuminate\Http\Request;

class EpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filter = new EpsFilter();
        $queryItems = $filter->transform($request);
        $includeClientes = $request->query('includeClientes');
        $eps = Eps::where($queryItems);
        if ($includeClientes) {
            $eps = $eps->with('clientes');
        }

        return new EpsCollection($eps->paginate()->appends($request->query()));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEpsRequest $request)
    {
        return new EpsResource(Eps::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Eps $eps)
    {
        return new EpsResource($eps);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEpsRequest $request, Eps $eps)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $eps->fill($data);

        // No hubo cambios
        if (! $eps->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $eps->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $eps->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Eps $eps)
    {
        $eps->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

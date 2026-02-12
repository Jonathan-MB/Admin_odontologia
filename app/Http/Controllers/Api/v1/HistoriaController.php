<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Filters\HistoriaFilter;
use App\Http\Requests\BulkStoreHistoriaRequest;
use App\Http\Requests\StoreHistoriaRequest;
use App\Http\Requests\UpdateHistoriaRequest;
use App\Http\Resources\HistoriaCollection;
use App\Http\Resources\HistoriaResource;
use App\Models\Historia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HistoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new HistoriaFilter();
        $queryItems = $filter->transform($request);

        $historia = Historia::query();

        // filtros dinámicos (cliente / especialista)
        if (!empty($queryItems)) {
            $historia->where($queryItems);
        }

        // filtro por fechas
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->query('from'))
                ->startOfDay()
                ->timezone('UTC');

            $to = Carbon::parse($request->query('to'))
                ->endOfDay()
                ->timezone('UTC');

            $historia->whereBetween('created_at', [$from, $to]);
        }

        return new HistoriaCollection(
            $historia
                ->paginate()
                ->appends($request->query())
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHistoriaRequest $request)
    {
        return new HistoriaResource(Historia::create($request->validated()));
    }


    public function bulkStore(BulkStoreHistoriaRequest $request)
    {
        Historia::insert($request->validated()['historias']);

        return response()->json([
            'message' => 'Historias creadas correctamente',
            'total'   => count($request->validated()['historias'])
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(Historia $historia)
    {
        return new HistoriaResource($historia);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHistoriaRequest $request, Historia $historia)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $historia->fill($data);

        // No hubo cambios
        if (! $historia->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $historia->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data' => new HistoriaResource($historia->fresh())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Historia $historia)
    {
        $historia->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

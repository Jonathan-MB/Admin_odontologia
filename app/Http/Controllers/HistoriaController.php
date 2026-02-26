<?php

namespace App\Http\Controllers;

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

    public function index(Request $request)
    {
        return view('historias');
    }



    public function store(StoreHistoriaRequest $request)
    {
        return new HistoriaResource(Historia::create($request->validated()));
    }


    public function bulkStore(BulkStoreHistoriaRequest $request)
    {
        $historias = collect($request->validated()['historias'])->map(fn($h) => array_merge($h, [
            'created_at' => now(),
            'updated_at' => now(),
        ]))->toArray();

        Historia::insert($historias);

        return response()->json([
            'message' => 'Historias creadas correctamente',
            'total'   => count($historias)
        ], 201);
    }

    public function show(Historia $historia)
    {
        return new HistoriaResource($historia);
    }



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



    public function destroy(Historia $historia)
    {
        $historia->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

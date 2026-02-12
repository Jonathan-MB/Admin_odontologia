<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Filters\FacturaFilter;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Http\Resources\FacturaCollection;
use App\Http\Resources\FacturaResource;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FacturaController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $filter = new FacturaFilter();
        $queryItems = $filter->transform($request);

        $factura = Factura::query();

        if (!empty($queryItems)) {
            $factura->where($queryItems);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->query('from'))
                ->startOfDay()
                ->timezone('UTC');

            $to = Carbon::parse($request->query('to'))
                ->endOfDay()
                ->timezone('UTC');

            $factura->whereBetween('created_at', [$from, $to]);
        }

        return new FacturaCollection(
            $factura
                ->paginate()
                ->appends($request->query())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacturaRequest $request)
    {
        return new FacturaResource(Factura::create($request->validated()));

    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        return new FacturaResource($factura);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacturaRequest $request, Factura $factura)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $factura->fill($data);

        // No hubo cambios
        if (! $factura->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $factura->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $factura->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        $factura->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}
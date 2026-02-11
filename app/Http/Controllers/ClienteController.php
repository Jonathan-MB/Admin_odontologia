<?php

namespace App\Http\Controllers;

use App\Filters\ClienteFilter;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteCollection;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use Illuminate\Http\Request;

class clienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new ClienteFilter();
        $queryItems = $filter->transform($request);
        $includeFacturas = $request->query('includeFacturas');
        $includeHistorias = $request->query('includeHistorias');

        $cliente = Cliente::where($queryItems);
        if ($includeFacturas) {
            $cliente = $cliente->with('facturas');
        }
        if ($includeHistorias) {
            $cliente = $cliente->with('historias');
        }

        return new ClienteCollection(
            $cliente
                ->paginate()
                ->appends($request->query())
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        return new ClienteResource(Cliente::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    Public function show(Cliente $cliente)
    {
        $includeHistorias = request()->query('includeHistorias');
        $includeFacturas  = request()->query('includeFacturas');

        if ($includeHistorias || $includeFacturas) {
            $relations = [];
            if ($includeHistorias) {
                $relations[] = 'historias';
            }
            if ($includeFacturas) {
                $relations[] = 'facturas';
            }
            return new ClienteResource(
                $cliente->loadMissing($relations)
            );
        }

        return new ClienteResource($cliente);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $cliente->fill($data);

        // No hubo cambios
        if (! $cliente->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $cliente->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $cliente->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cliente $cliente)
    {
        $cliente->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

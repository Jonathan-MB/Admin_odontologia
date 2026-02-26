<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Diente;
use App\Http\Resources\DienteCollection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Filters\DienteFilter;
use App\Http\Requests\StoreDienteRequest;
use App\Http\Requests\UpdateDienteRequest;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\DienteResource;

class DienteController extends Controller
{

    public function index(Request $request)
    {
        $filter = new DienteFilter();
        $queryItems = $filter->transform($request);
        $dientes = Diente::where($queryItems);

        return new DienteCollection($dientes->paginate()->appends($request->query()));
    }



    public function show(Diente $diente)
    {
        return new DienteResource($diente);
    }



    public function store(StoreDienteRequest $request)
    {
        return new DienteResource(Diente::create($request->validated()));
    }



    public function update(UpdateDienteRequest $request, Diente $diente)
    {

        $data = $request->validated();

        // PATCH sin data
        if (empty($data)) {
            return response()->json([
                'message' => 'Sin datos'
            ], 422);
        }

        // Cargar datos sin guardar
        $diente->fill($data);

        // No hubo cambios
        if (! $diente->isDirty()) {
            return response()->json([
                'message' => 'No se detectaron cambios'
            ], 422);
        }

        $diente->save();

        return response()->json([
            'message' => 'Actualizado Correctamente',
            'data'    => $diente->fresh()
        ], 200);
    }

    /**
     * Eliminar un diente
     */
    public function destroy(Diente $diente)
    {
        $diente->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

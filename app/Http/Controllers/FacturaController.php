<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Filters\FacturaFilter;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Http\Resources\FacturaCollection;
use App\Http\Resources\FacturaResource;
use App\Models\Cliente;
use App\Models\Especialista;
use App\Models\Factura;
use App\Models\Sede;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

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
    public function show(Cliente $cliente)
    {

        $cliente->load([
            'facturas' => fn($q) => $q->orderBy('created_at', 'desc')->with('especialista'),
            'tipoDocumento', // ← agregar
            'eps',           // ← agregar
        ]);

        $especialistas = Especialista::all();

        return view('factura', compact('cliente', 'especialistas'));
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


public function buscar(Request $request)
{
    $numeroDocumento = $request->numeroDocumento;

    $cliente = Cliente::where('numero_documento', $numeroDocumento)->first();

    if (!$cliente) {
        return back()->with('error', 'Cliente no encontrado');
    }

    return redirect()->route('facturaCliente', $cliente->id);
}


    public function guardar(Request $request)
    {
        // 1. Traer sede por sesión y obtener consecutivo
        $sede = Sede::find((int) session('sede.id'));
        logger('sede: ' . json_encode($sede));
        logger('session sede.id: ' . session('sede.id'));
        $nuevoNoFactura = $sede->no_factura + 1;
        $sede->update(['no_factura' => $nuevoNoFactura]);

        // 2. Crear factura con el consecutivo
        Factura::create([
            'cliente_id'      => $request->clienteId,
            'especialista_id' => $request->especialistaId,
            'nombre'          => $request->nombre,
            'abono'           => $request->abono,
            'saldo'           => $request->saldoFinal,
            'no_factura'      => $nuevoNoFactura, // ← consecutivo de la sede
        ]);

        // 3. Actualizar cliente
        $cliente = Cliente::find($request->clienteId);
        $cliente->update([
            'saldo'      => $request->saldoFinal,
            'fecha_cita' => $request->fechaCita,
        ]);

        // 4. Retornar el noFactura al JS
        return response()->json([
            'success'   => true,
            'noFactura' => $nuevoNoFactura, // ← el JS lo usa para mostrar en la factura
        ]);
    }
}

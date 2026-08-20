<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Cliente;
use App\Models\Especialista;
use App\Models\Factura;
use App\Models\MetodoPago;
use App\Models\Sede;
use Illuminate\Http\Request;

class FacturaController extends Controller
{







    public function show(Cliente $cliente)
    {

        $cliente->load([
            'facturas' => fn($q) => $q->orderBy('created_at', 'desc')->with(['especialista', 'metodoPago']),
            'tipoDocumento',
            'eps',
        ]);
        $especialistas = Especialista::all();
        $sedes = Sede::all();
        $metodoPagos = MetodoPago::all();

        return view('factura', compact('cliente', 'especialistas', 'sedes', 'metodoPagos'));
    }









    public function buscar(Request $request)
    {
        $busqueda = trim($request->numeroDocumento);

        if ($busqueda === '') {
            return back()->with('error', 'Escribe un documento o un nombre');
        }

        // Primero siempre por documento exacto, igual que siempre.
        // Va antes que todo para no romper pasaportes ni cedulas de
        // extranjeria, que pueden llevar letras.
        $cliente = Cliente::where('numero_documento', $busqueda)->first();

        if ($cliente) {
            return redirect()->route('facturaCliente', $cliente->id);
        }

        // Si son solo numeros no tiene sentido buscarlo como nombre
        if (ctype_digit($busqueda)) {
            return back()->with('error', 'Cliente no encontrado');
        }

        // Con letras: se busca por nombre y apellidos
        $clientes = Cliente::buscarPorNombre($busqueda)->get();

        if ($clientes->isEmpty()) {
            return back()->with('error', 'Cliente no encontrado');
        }

        if ($clientes->count() === 1) {
            return redirect()->route('facturaCliente', $clientes->first()->id);
        }

        $destino = 'facturaCliente';

        return view('resultadosBusqueda', compact('clientes', 'busqueda', 'destino'));
    }


    public function guardar(Request $request)
    {
        //  Traer sede por sesión y obtener consecutivo
        $sede = Sede::find((int) session('sede.id'));
        logger('sede: ' . json_encode($sede));
        logger('session sede.id: ' . session('sede.id'));
        $nuevoNoFactura = $sede->no_factura + 1;
        $sede->update(['no_factura' => $nuevoNoFactura]);

        //  Crear factura con el consecutivo
        Factura::create([
            'cliente_id'      => $request->clienteId,
            'sede_id'      => $request->sedeId,
            'especialista_id' => $request->especialistaId,
            'metodo_pago_id'  => $request->metodoPagoId,
            'nombre'          => $request->nombre,
            'abono'           => $request->abono,
            'saldo'           => $request->saldoFinal,
            'no_factura'      => $nuevoNoFactura,
        ]);

        //  Actualizar cliente
        $cliente = Cliente::find($request->clienteId);
        $cliente->update([
            'saldo'      => $request->saldoFinal,
            'fecha_cita' => $request->fechaCita,
        ]);

        //  Registrar la cita en su propia tabla, con el especialista
        //  que atendio. clientes.fecha_cita sigue guardandose arriba.
        if ($request->fechaCita) {
            $cliente->agendarProximaCita(
                $request->fechaCita,
                $request->sedeId,
                $request->especialistaId
            );
        }

        //  Retornar el noFactura al JS
        return response()->json([
            'success'   => true,
            'noFactura' => $nuevoNoFactura,
        ]);
    }

   public function totalDia(Request $request)
{
    $fecha  = $request->input('fecha', now()->toDateString());
    $sedeId = session('sede.id');

    $sedes = Sede::with([
        'especialistas.facturas' => function ($q) use ($fecha, $sedeId) {
            $q->whereDate('created_at', $fecha)
                ->where('sede_id', $sedeId);
        }
    ])->get();

    // Agregar especialistas de otras sedes que facturaron aquí
    $especialistasForaneos = Especialista::whereHas('facturas', function ($q) use ($fecha, $sedeId) {
        $q->whereDate('created_at', $fecha)
            ->where('sede_id', $sedeId);
    })
    ->where('sede_id', '!=', $sedeId) // solo los que NO son de esta sede
    ->with(['facturas' => function ($q) use ($fecha, $sedeId) {
        $q->whereDate('created_at', $fecha)
            ->where('sede_id', $sedeId);
    }])
    ->get();

    // Inyectarlos en la sede activa
    $sedes = $sedes->map(function ($sede) use ($sedeId, $especialistasForaneos) {
        if ($sede->id == $sedeId) {
            $sede->especialistas = $sede->especialistas->merge($especialistasForaneos);
        }
        return $sede;
    });

    // Totales por metodo de pago de la sede activa.
    // Las facturas anteriores a esta funcion tienen metodo_pago_id nulo
    // y se agrupan bajo "Sin registrar".
    $nombresMetodoPago = MetodoPago::pluck('nombre', 'id');

    $totalesMetodoPago = Factura::whereDate('created_at', $fecha)
        ->where('sede_id', $sedeId)
        ->selectRaw('metodo_pago_id, SUM(abono) as total')
        ->groupBy('metodo_pago_id')
        ->get()
        ->map(fn($fila) => [
            'nombre' => $nombresMetodoPago->get($fila->metodo_pago_id, 'Sin registrar'),
            'total'  => $fila->total,
        ])
        ->sortByDesc('total')
        ->values();

    return view('especialistasTotal', compact('sedes', 'fecha', 'totalesMetodoPago'));
}
}

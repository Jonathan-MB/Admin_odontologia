<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Diente;
use App\Models\Eps;
use App\Models\Especialista;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class clienteController extends Controller
{


    public function create()
    {
        $tipoDocumentos = TipoDocumento::all();
        $eps = Eps::all();

        return view('crearCliente', compact('tipoDocumentos', 'eps'));
    }



    public function store(StoreClienteRequest $request)
    {
        $cliente = Cliente::create($request->validated());

        return redirect()->route('clientes.show', $cliente->id)->with('mensajeCreado', 'Cliente creado correctamente');
    }



    public function show(Cliente $cliente)
    {
        $cliente->load(['historias.especialista', 'historias.diente', 'facturas']);
        $dientes = Diente::all();
        $especialistas = Especialista::all();
        $ultimasHistorias = $cliente->historias
            ->groupBy('diente_id')
            ->map(fn($historias) => $historias->sortByDesc('created_at')->first());


        return view('historias', compact('cliente', 'dientes', 'especialistas', 'ultimasHistorias'));
    }


    public function edit(Cliente $cliente)
    {
        $tipoDocumentos = TipoDocumento::all();
        $eps = Eps::all();

        return view('editarCliente', compact('cliente', 'tipoDocumentos', 'eps'));
    }



    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $data = $request->validated();

        if (empty($data)) {
            return response()->json(['message' => 'Sin datos'], 422);
        }

        $cliente->fill($data);

        if (!$cliente->isDirty()) {
            return response()->json(['message' => 'No se detectaron cambios'], 422);
        }

        $cliente->save();


        return redirect()->route('clientes.show', $cliente->id)
            ->with('mensajeActualizado', 'Cliente Actualizado correctamente');
    }

    public function agendarCita(Request $request, Cliente $cliente)
    {
        $request->validate([
            'fecha_cita' => ['nullable', 'date'],
            'sede_id'    => ['required', 'integer', 'exists:sedes,id'],
        ]);

        $cliente->update([
            'fecha_cita'   => $request->fecha_cita ?: null, 
            'sede_cita_id' => $request->sede_id,
        ]);



        return redirect()->back()->with('mensaje', 'Cita actualizada correctamente');
    }

    public function destroy(cliente $cliente)
    {
        $cliente->delete();

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

        return redirect()->route('clientes.show', $cliente->id);
    }


    public function verificarDocumento(Request $request, $numero)
    {
        $query = Cliente::where('numero_documento', $numero);

        if ($request->clienteId) {
            $query->where('id', '!=', $request->clienteId);
        }

        return response()->json(['existe' => $query->exists()]);
    }

    public function citas(Request $request, $sedeId)
    {
        $fecha = $request->filled('fecha') ? $request->fecha : now()->toDateString();

        $clientes = Cliente::where('sede_id', $sedeId)
            ->whereDate('fecha_cita', $fecha)
            ->orderBy('fecha_cita')
            ->get();

        return view('citas', compact('clientes', 'fecha'));
    }
}

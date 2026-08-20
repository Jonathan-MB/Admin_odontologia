<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cita;
use Carbon\Carbon;
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
            return redirect()->route('clientes.show', $cliente->id)
                ->with('mensajeAlerta' , 'Sin datos');
        }

        $cliente->fill($data);

        if (!$cliente->isDirty()) {
            return redirect()->route('clientes.show', $cliente->id)
                ->with('mensajeAlerta' ,'No se detectaron cambios');
        }

        $cliente->save();

        // Si se agendo desde la ficha, reflejarlo tambien en la tabla citas
        if (array_key_exists('fecha_cita', $data)) {
            if ($data['fecha_cita']) {
                $cliente->agendarProximaCita(
                    $data['fecha_cita'],
                    $data['sede_id'] ?? $cliente->sede_id
                );
            } else {
                $cliente->cancelarProximaCita();
            }
        }


        return redirect()->route('clientes.show', $cliente->id)
            ->with('mensajeActualizado', 'Cliente Actualizado correctamente');
    }

    public function agendarCita(Request $request, Cliente $cliente)
    {
        $request->validate([
            'fecha_cita'      => ['nullable', 'date'],
            'sede_id'         => ['required', 'integer', 'exists:sedes,id'],
            'especialista_id' => ['nullable', 'integer', 'exists:especialistas,id'],
        ]);

        $cliente->update([
            'fecha_cita'   => $request->fecha_cita ?: null, 
            'sede_id' => $request->sede_id,
        ]);

        // Reflejar el cambio en la tabla citas
        if ($request->fecha_cita) {
            $cliente->agendarProximaCita(
                $request->fecha_cita,
                $request->sede_id,
                $request->especialista_id
            );
        } else {
            $cliente->cancelarProximaCita();
        }



        return redirect()->back()->with('mensaje', 'Cita actualizada correctamente');
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
            return redirect()->route('clientes.show', $cliente->id);
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
            return redirect()->route('clientes.show', $clientes->first()->id);
        }

        $destino = 'clientes.show';

        return view('resultadosBusqueda', compact('clientes', 'busqueda', 'destino'));
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
        $dia   = Carbon::parse($fecha);

        // Mes que pinta el calendario: el del dia elegido, salvo que
        // se navegue con ?mes=AAAA-MM
        $mes = $request->filled('mes')
            ? Carbon::parse($request->mes . '-01')
            : $dia->copy()->startOfMonth();

        // Citas del dia, con su paciente y su doctor
        $citas = Cita::where('sede_id', $sedeId)
            ->where('estado', 'agendada')
            ->whereDate('fecha_hora', $fecha)
            ->with(['cliente', 'especialista'])
            ->orderBy('fecha_hora')
            ->get();

        // Cuantas citas tiene cada dia del mes, para marcar el calendario
        $citasPorDia = Cita::where('sede_id', $sedeId)
            ->where('estado', 'agendada')
            ->whereBetween('fecha_hora', [
                $mes->copy()->startOfMonth(),
                $mes->copy()->endOfMonth()->endOfDay(),
            ])
            ->get()
            ->groupBy(fn($cita) => $cita->fecha_hora->toDateString())
            ->map->count();

        // Citas vencidas que nadie volvio a agendar
        $pendientes = Cita::where('sede_id', $sedeId)
            ->where('estado', 'agendada')
            ->whereDate('fecha_hora', '<', now()->toDateString())
            ->with(['cliente', 'especialista'])
            ->orderBy('fecha_hora', 'desc')
            ->limit(50)
            ->get();

        $especialistas = Especialista::where('sede_id', $sedeId)
            ->orderBy('nombre')
            ->get();

        return view('citas', compact(
            'citas',
            'fecha',
            'dia',
            'mes',
            'citasPorDia',
            'pendientes',
            'especialistas'
        ));
    }
}

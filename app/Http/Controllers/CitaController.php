<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCitaRequest;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CitaController extends Controller
{

    /**
     * Busca pacientes para el buscador del popup de agendar.
     * Acepta documento o nombre, igual que el buscador general.
     */
    public function buscarClientes(Request $request)
    {
        $busqueda = trim($request->query('q', ''));

        if (mb_strlen($busqueda) < 3) {
            return response()->json([]);
        }

        $clientes = ctype_digit($busqueda)
            ? Cliente::where('numero_documento', 'like', $busqueda . '%')->limit(10)->get()
            : Cliente::buscarPorNombre($busqueda)->limit(10)->get();

        return response()->json(
            $clientes->map(fn($cliente) => [
                'id'        => $cliente->id,
                'nombre'    => $cliente->nombre_completo,
                'documento' => $cliente->numero_documento,
                'telefono'  => $cliente->telefono,
            ])
        );
    }



    public function store(StoreCitaRequest $request)
    {
        $cliente = Cliente::findOrFail($request->cliente_id);

        // La sede sale de la sesion, no del formulario
        $cita = $cliente->agendarProximaCita(
            $request->fecha_hora,
            (int) session('sede.id'),
            $request->especialista_id
        );

        // Un paciente tiene una sola cita pendiente: si ya tenia una,
        // esta se movio en vez de crearse otra. Conviene avisarlo.
        $mensaje = $cita->wasRecentlyCreated
            ? 'Cita agendada correctamente'
            : 'El paciente ya tenia una cita pendiente y se movio a esta fecha';

        return redirect()
            ->route('clientes.citas', [
                'sedeId' => session('sede.id'),
                'fecha'  => Carbon::parse($request->fecha_hora)->toDateString(),
            ])
            ->with('mensaje', $mensaje);
    }
}

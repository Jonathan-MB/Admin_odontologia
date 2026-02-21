<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Filters\ClienteFilter;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteCollection;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Diente;
use App\Models\Eps;
use App\Models\Especialista;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class clienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $filter = new ClienteFilter();
        // $queryItems = $filter->transform($request);
        // $includeFacturas = $request->query('includeFacturas');
        // $includeHistorias = $request->query('includeHistorias');

        // $clientesQuery = Cliente::where($queryItems);
        // if ($includeFacturas) {
        //     $clientesQuery = $clientesQuery->with('facturas');
        // }
        // if ($includeHistorias) {
        //     $clientesQuery = $clientesQuery->with('historias');
        // }

        // $clientes = $clientesQuery->get();

        // return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $tipoDocumentos = TipoDocumento::all();
        $eps = Eps::all(); // si también quieres llenar otro select
        return view('crearCliente', compact('tipoDocumentos', 'eps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        // Crear el cliente
        $cliente = Cliente::create($request->validated());

        // Redirigir a una vista, por ejemplo la lista de clientes
        return redirect()->route('clientes.show', $cliente->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load(['historias.especialista','historias.diente', 'facturas']); // ← cargar relación anidada
        $dientes = Diente::all();
        $especialistas = Especialista::all();

        return view('historias', compact('cliente', 'dientes', 'especialistas')); // no necesitas pasar $especialistas
    }


    public function edit(Cliente $cliente)
    {
        $tipoDocumentos = TipoDocumento::all();
        $eps = Eps::all();

        return view('editarCliente', compact('cliente', 'tipoDocumentos', 'eps'));
    }

    /**
     * Update the specified resource in storage.
     */
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

        // Redirigir al método show usando el nombre de la ruta
        return redirect()->route('clientes.show', $cliente->id)
            ->with('success', 'Cliente actualizado correctamente');
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

    public function buscar(Request $request)
    {
        $numeroDocumento = $request->numeroDocumento;

        $cliente = Cliente::where('numero_documento', $numeroDocumento)->first();

        if (!$cliente) {
            return back()->with('error', 'Cliente no encontrado');
        }

        return redirect()->route('clientes.show', $cliente->id);
    }
}

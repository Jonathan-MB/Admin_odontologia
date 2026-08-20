<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetodoPagoRequest;
use App\Http\Requests\UpdateMetodoPagoRequest;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{

    public function index(Request $request)
    {
        $metodoPagos = MetodoPago::all();

        return view('metodoPago', compact('metodoPagos'));
    }



    public function store(StoreMetodoPagoRequest $request)
    {
        MetodoPago::create($request->validated());
        return redirect()->back()->with('mensajeCreado', 'Metodo de pago creado correctamente');

    }



    public function edit(MetodoPago $metodoPago)
    {
        return view('metodoPagoEditar', compact('metodoPago'));
    }



    public function update(UpdateMetodoPagoRequest $request, MetodoPago $metodoPago)
    {
        $metodoPago->update(['nombre' => $request->nombre]);

        return redirect()->route('metodoPagos.index')->with('mensajeActualizado', 'Metodo de pago actualizado correctamente');
    }
}

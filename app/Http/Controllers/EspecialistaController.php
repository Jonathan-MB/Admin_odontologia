<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Filters\EspecialistaFilter;
use App\Http\Requests\StoreEspecialistaRequest;
use App\Http\Requests\UpdateEspecialistaRequest;
use App\Http\Resources\EspecialistaCollection;
use App\Http\Resources\EspecialistaResource;
use App\Models\Especialista;
use App\Models\Sede;
use Illuminate\Http\Request;

class EspecialistaController extends Controller
{

    public function index(Request $request)
    {

        $especialistas   = Especialista::all();
        $sedes       = Sede::all();

        return view('especialista', compact('especialistas', 'sedes'));
    }



    public function store(StoreEspecialistaRequest $request)
    {

        Especialista::create($request->validated());
        return redirect()->back()->with('mensajeCreado', 'Especialista creado correctamente');
    
    }



    public function show(Especialista $especialista)
    {
        return new EspecialistaResource($especialista);
    }


    public function edit(Especialista $especialista)
    {
        $sedes = Sede::all();
        return view('especialistaEditar', compact('especialista', 'sedes'));
    }



    public function update(UpdateEspecialistaRequest $request, Especialista $especialista)
    {

        $data = $request->validated();
        $especialista->fill($data);
        $especialista->save();

        return redirect()->route('especialistas.index')->with('mensajeActualizado', 'Especialista Actualizado correctamente');
    }




}

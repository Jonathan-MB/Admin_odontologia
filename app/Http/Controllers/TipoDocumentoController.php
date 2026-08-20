<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreTipoDocumentoRequest;
use App\Http\Requests\UpdateTipoDocumentoRequest;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{



    public function index(Request $request)
    {
        $tipoDocumentos = TipoDocumento::all();

        return view('tipoDocumento', compact('tipoDocumentos'));
    }



public function store(StoreTipoDocumentoRequest $request)
{
    TipoDocumento::create($request->validated());
    return redirect()->back()->with('mensajeCreado', 'Tipo de documento creado correctamente');
}




    public function edit(TipoDocumento $tipoDocumento)
    {
        return view('tipoDocumentoEditar', compact('tipoDocumento'));
    }



    public function update(UpdateTipoDocumentoRequest $request, TipoDocumento $tipoDocumento)
    {

       $tipoDocumento->update(['nombre' => $request->nombre]);

        return redirect()->route('tipoDocumentos.index')->with('mensajeActualizado', 'Tipo Documento actualizado correctamente');
    }



}

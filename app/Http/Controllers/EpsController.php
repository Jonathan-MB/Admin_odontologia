<?php

namespace App\Http\Controllers;


use App\Filters\EpsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEpsRequest;
use App\Http\Requests\UpdateEpsRequest;
use App\Http\Resources\EpsCollection;
use App\Http\Resources\EpsResource;
use App\Models\Eps;
use Illuminate\Http\Request;

class EpsController extends Controller
{
    public function index(Request $request)
    {
        $eps   = Eps::all();
        return view('eps', compact('eps'));
    }



    public function store(StoreEpsRequest $request)
    {
        Eps::create($request->validated());
        return redirect()->back()->with('mensajeCreado', 'Eps creado correctamente');
    
    }

    public function show(Eps $eps)
    {
        return new EpsResource($eps);
    }


    public function edit(Eps $ep)
    {
        return view('epsEditar', compact('ep'));
    }


    public function update(UpdateEpsRequest $request, Eps $ep)
    {
        $ep->update(['nombre' => $request->nombre]);

        return redirect()->route('eps.index')->with('mensajeActualizado', 'EPS actualizada correctamente');
    }



    public function destroy(Eps $eps)
    {
        $eps->delete();
        return response()->json([
            'message' => 'Eliminado correctamente'
        ], 200);
    }
}

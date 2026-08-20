<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Requests\BulkStoreHistoriaRequest;
use App\Models\Historia;

class HistoriaController extends Controller
{






    public function bulkStore(BulkStoreHistoriaRequest $request)
    {
        $historias = collect($request->validated()['historias'])->map(fn($h) => array_merge($h, [
            'created_at' => now(),
            'updated_at' => now(),
        ]))->toArray();

        Historia::insert($historias);

        return response()->json([
            'message' => 'Historias creadas correctamente',
            'total'   => count($historias)
        ], 201);
    }







}

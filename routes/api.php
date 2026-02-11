<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DienteController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\EspecialistaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/test', function () {
    return 'hola';
});

Route::group(['prefix'=>'v1', 'namespace' => 'App\Http\Controllers'], function(){
    Route::apiResource('dientes',DienteController::class);
    Route::apiResource('clientes',ClienteController::class);
    Route::apiResource('especialistas',EspecialistaController::class);
    Route::apiResource('facturas',FacturaController::class);
    Route::apiResource('grupos',GrupoController::class);
    Route::apiResource('historias',HistoriaController::class);
    Route::apiResource('rols',RolController::class);
    Route::apiResource('sedes',SedeController::class);
    Route::apiResource('tipoDocumentos',TipoDocumentoController::class);
    Route::apiResource('usuarios',UsuarioController::class);
    Route::apiResource('eps', EpsController::class)->parameters(['eps' => 'eps']);
    Route::post('historias/bulk',['uses'=>'HistoriaController@bulkStore']);
});
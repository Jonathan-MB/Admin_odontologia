<?php

use App\Http\Controllers\Api\V1\ClienteController;
use App\Http\Controllers\Api\V1\DienteController;
use App\Http\Controllers\Api\V1\EpsController;
use App\Http\Controllers\Api\V1\EspecialistaController;
use App\Http\Controllers\Api\V1\FacturaController;
use App\Http\Controllers\Api\V1\GrupoController;
use App\Http\Controllers\Api\V1\HistoriaController;
use App\Http\Controllers\Api\V1\RolController;
use App\Http\Controllers\Api\V1\SedeController;
use App\Http\Controllers\Api\V1\TipoDocumentoController;
use App\Http\Controllers\Api\V1\UsuarioController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/test', function () {
    return 'hola';
});

Route::prefix('v1')->group(function () {

    // LOGIN libre
    Route::post('login', [UsuarioController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        // LOGOUT solo autenticado
        Route::post('logout', [UsuarioController::class, 'logout']);

        // TODO usuarios solo ADMIN
        Route::middleware('rol:1')->group(function () {
            Route::apiResource('usuarios', UsuarioController::class);
        });

        // Otras rutas normales autenticadas
        Route::apiResource('dientes', DienteController::class);
        Route::apiResource('clientes', ClienteController::class);
        Route::apiResource('especialistas', EspecialistaController::class);
        Route::apiResource('facturas', FacturaController::class);
        Route::apiResource('grupos', GrupoController::class);
        Route::apiResource('historias', HistoriaController::class);
        Route::post('historias/bulk', [HistoriaController::class, 'bulkStore']);
        Route::apiResource('rols', RolController::class);
        Route::apiResource('sedes', SedeController::class);
        Route::apiResource('tipoDocumentos', TipoDocumentoController::class);
        Route::apiResource('eps', EpsController::class)->parameters(['eps' => 'eps']);
    });
});

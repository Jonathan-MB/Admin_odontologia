<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\EspecialistaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
-------------------------------------------------------------------
*/

Route::view('/login', 'login')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('web.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Rutas autenticadas
-------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {



    /*
|--------------------------------------------------------------------------
| Sin Sede
-------------------------------------------------------------------
*/

    Route::resource('sedes', SedeController::class)->only(['index']);
    Route::post('/guardar-sede', [SedeController::class, 'guardarSede'])
        ->name('guardar.sede');

    /*
|--------------------------------------------------------------------------
| Rutas con sede
-------------------------------------------------------------------
*/
    Route::middleware('verificar.sede')->group(function () {

        /*
|--------------------------------------------------------------------------
| Rutas solo Administradores
------------------------------------------------------------------------------
*/
        Route::middleware('rol:1')->group(function () {
            Route::resource('usuarios', UsuarioController::class)
                ->only(['index', 'store', 'edit', 'update']);
            Route::get('/facturas/totalDia', [FacturaController::class, 'totalDia'])
                ->name('facturas.totalDia');
        });

        //-------------------FIN SOLO ADMIN------------------------------------------------


        Route::view('/', 'inicio')->name('inicio');
        Route::view('/busqueda', 'busqueda')->name('busqueda');
        Route::view('/facturacion', 'facturacion')->name('facturacion');
        Route::get('/citas/{sedeId}', [ClienteController::class, 'citas'])
            ->name('clientes.citas');

        Route::get('/agenda/clientes', [CitaController::class, 'buscarClientes'])
            ->name('agenda.clientes');
        Route::post('/agenda/citas', [CitaController::class, 'store'])
            ->name('citas.store');

        Route::patch('/clientes/{cliente}/agendar', [clienteController::class, 'agendarCita'])->name('clientes.agendar');
        Route::get('facturas/{cliente}', [FacturaController::class, 'show'])->name('facturaCliente');
        Route::get('/clientes/verificar-documento/{numero}', [ClienteController::class, 'verificarDocumento'])->name('clientes.verificarDocumento');


        /*
|--------------------------------------------------------------------------
| Resources
| Solo se declaran las acciones que el controlador implementa y que la
| aplicacion usa. Antes se registraban las 7 de cada uno y quedaban
| rutas abiertas sin metodo detras.
-------------------------------------------------------------------
*/

        Route::resource('clientes', ClienteController::class)
            ->only(['create', 'store', 'show', 'edit', 'update']);

        Route::resource('especialistas', EspecialistaController::class)
            ->only(['index', 'store', 'edit', 'update']);

        Route::resource('eps', EpsController::class)
            ->only(['index', 'store', 'edit', 'update']);

        Route::resource('tipoDocumentos', TipoDocumentoController::class)
            ->only(['index', 'store', 'edit', 'update']);

        Route::resource('metodoPagos', MetodoPagoController::class)
            ->only(['index', 'store', 'edit', 'update']);

        Route::post('historias/bulk', [HistoriaController::class, 'bulkStore']);
        Route::post('/clientes/buscar', [ClienteController::class, 'buscar'])
            ->name('clientes.buscar');
        Route::post('/facturas/buscar', [FacturaController::class, 'buscar'])
            ->name('facturas.buscar');
        Route::view('/config', 'configuracion')->name('configuracion');
        Route::post('/facturas/guardar', [FacturaController::class, 'guardar']);
    });
});

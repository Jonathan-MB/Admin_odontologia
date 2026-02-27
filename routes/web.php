<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
            Route::resource('usuarios', UsuarioController::class);
        });

        //-------------------FIN SOLO ADMIN------------------------------------------------


        Route::view('/', 'inicio')->name('inicio');
        Route::view('/busqueda', 'busqueda')->name('busqueda');
        Route::view('/facturacion', 'facturacion')->name('facturacion');
        Route::get('/citas/{sedeId}', [ClienteController::class, 'citas'])
            ->name('clientes.citas');
        Route::get('/facturas/totalDia', [FacturaController::class, 'totalDia'])
            ->name('facturas.totalDia');
        Route::patch('/clientes/{cliente}/agendar', [clienteController::class, 'agendarCita'])->name('clientes.agendar');
        Route::get('facturas/{cliente}', [FacturaController::class, 'show'])->name('facturaCliente');
        Route::get('/clientes/verificar-documento/{numero}', [ClienteController::class, 'verificarDocumento'])->name('clientes.verificarDocumento');


        Route::resources([
            'clientes' => ClienteController::class,
            'dientes' => DienteController::class,
            'especialistas' => EspecialistaController::class,
            'facturas' => FacturaController::class,
            'grupos' => GrupoController::class,
            'historias' => HistoriaController::class,
            'roles' => RolController::class,
            'tipoDocumentos' => TipoDocumentoController::class,
            'eps' => EpsController::class,
        ]);

        Route::post('historias/bulk', [HistoriaController::class, 'bulkStore']);
        Route::post('/clientes/buscar', [ClienteController::class, 'buscar'])
            ->name('clientes.buscar');
        Route::post('/facturas/buscar', [FacturaController::class, 'buscar'])
            ->name('facturas.buscar');
        Route::view('/config', 'configuracion')->name('configuracion');
        Route::post('/facturas/guardar', [FacturaController::class, 'guardar']);
    });
});

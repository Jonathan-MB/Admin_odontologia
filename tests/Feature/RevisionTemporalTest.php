<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Eps;
use App\Models\Especialista;
use App\Models\Factura;
use App\Models\MetodoPago;
use App\Models\Rol;
use App\Models\Sede;
use App\Models\TipoDocumento;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevisionTemporalTest extends TestCase
{
    use RefreshDatabase;

    protected Usuario $admin;
    protected Sede $sede;
    protected Especialista $especialista;
    protected MetodoPago $efectivo;
    protected MetodoPago $nequi;

    protected function setUp(): void
    {
        parent::setUp();

        $rolAdmin = Rol::create(['nombre' => 'Administrador']);
        Rol::create(['nombre' => 'Colaborador']);

        $this->admin = Usuario::create([
            'nombre'     => 'Admin Test',
            'correo'     => 'admin@test.com',
            'contrasena' => 'secreto123',
            'rol_id'     => $rolAdmin->id,
        ]);

        $this->sede = Sede::create([
            'nombre'     => 'Sede Test',
            'nit'        => '123-4',
            'direccion'  => 'Calle 1',
            'telefono'   => '111',
            'celular'    => '222',
            'no_factura' => 40,
        ]);

        $this->especialista = Especialista::create([
            'nombre'  => 'Dra Prueba',
            'sede_id' => $this->sede->id,
        ]);

        $this->efectivo = MetodoPago::create(['nombre' => 'Efectivo']);
        $this->nequi    = MetodoPago::create(['nombre' => 'Nequi']);

        TipoDocumento::create(['nombre' => 'Cedula de ciudadania']);
        TipoDocumento::create(['nombre' => 'Pasaporte']);
        Eps::create(['nombre' => 'Sura']);
    }

    private function conSede()
    {
        return $this->actingAs($this->admin)->withSession([
            'sede' => [
                'id'        => $this->sede->id,
                'nombre'    => $this->sede->nombre,
                'nit'       => $this->sede->nit,
                'direccion' => $this->sede->direccion,
                'telefono'  => $this->sede->telefono,
                'celular'   => $this->sede->celular,
            ],
        ]);
    }

    private function cliente(array $extra = []): Cliente
    {
        return Cliente::create(array_merge([
            'nombre'            => 'Juan',
            'primer_apellido'   => 'Perez',
            'segundo_apellido'  => 'Gomez',
            'numero_documento'  => '1234567',
            'direccion'         => 'Calle 2',
            'telefono'          => '333',
            'fecha_nacimiento'  => '1990-01-01',
            'saldo'             => 0,
            'tipo_documento_id' => TipoDocumento::first()->id,
            'eps_id'            => Eps::first()->id,
            'sede_id'           => $this->sede->id,
        ], $extra));
    }

    // ---------------------------------------------------------------- ACCESO

    public function test_login_funciona(): void
    {
        $this->post('/login', [
            'correo'   => 'admin@test.com',
            'password' => 'secreto123',
        ])->assertRedirect('sedes');

        $this->assertAuthenticated();
    }

    public function test_sin_sede_redirige_a_sedes(): void
    {
        $this->actingAs($this->admin)->get('/')->assertRedirect(route('sedes.index'));
    }

    // ------------------------------------------------------------- BUSQUEDA

    public function test_busca_por_documento_exacto(): void
    {
        $cliente = $this->cliente();

        $this->conSede()
            ->post(route('clientes.buscar'), ['numeroDocumento' => '1234567'])
            ->assertRedirect(route('clientes.show', $cliente->id));
    }

    public function test_busca_documento_con_letras_pasaporte(): void
    {
        $cliente = $this->cliente(['numero_documento' => 'AB12345']);

        $this->conSede()
            ->post(route('clientes.buscar'), ['numeroDocumento' => 'AB12345'])
            ->assertRedirect(route('clientes.show', $cliente->id));
    }

    public function test_busca_por_nombre_un_resultado_entra_directo(): void
    {
        $cliente = $this->cliente();

        $this->conSede()
            ->post(route('clientes.buscar'), ['numeroDocumento' => 'Juan'])
            ->assertRedirect(route('clientes.show', $cliente->id));
    }

    public function test_busca_por_nombre_varios_muestra_lista(): void
    {
        $this->cliente();
        $this->cliente(['nombre' => 'Juana', 'numero_documento' => '7654321']);

        $this->conSede()
            ->post(route('clientes.buscar'), ['numeroDocumento' => 'Juan'])
            ->assertOk()
            ->assertViewIs('resultadosBusqueda')
            ->assertSee('2 clientes encontrados');
    }

    public function test_busca_por_nombre_desordenado(): void
    {
        $this->cliente();
        $this->cliente(['nombre' => 'Otro', 'primer_apellido' => 'Perez', 'numero_documento' => '999']);

        $this->conSede()
            ->post(route('clientes.buscar'), ['numeroDocumento' => 'perez juan'])
            ->assertRedirect();
    }

    public function test_busqueda_sin_resultados_avisa(): void
    {
        $this->conSede()
            ->from(route('busqueda'))
            ->post(route('clientes.buscar'), ['numeroDocumento' => 'Nadie'])
            ->assertRedirect(route('busqueda'))
            ->assertSessionHas('error');
    }

    public function test_busqueda_facturacion_por_nombre(): void
    {
        $cliente = $this->cliente();

        $this->conSede()
            ->post(route('facturas.buscar'), ['numeroDocumento' => 'Perez'])
            ->assertRedirect(route('facturaCliente', $cliente->id));
    }

    // ----------------------------------------------------------- FACTURACION

    public function test_guardar_factura_con_metodo_de_pago(): void
    {
        $cliente = $this->cliente(['saldo' => 100000]);

        $respuesta = $this->conSede()->postJson('/facturas/guardar', [
            'clienteId'      => $cliente->id,
            'sedeId'         => $this->sede->id,
            'especialistaId' => $this->especialista->id,
            'metodoPagoId'   => $this->nequi->id,
            'nombre'         => 'Juan Perez',
            'abono'          => 30000,
            'saldoFinal'     => 70000,
            'fechaCita'      => null,
        ]);

        $respuesta->assertOk()->assertJson(['success' => true, 'noFactura' => 41]);

        $this->assertDatabaseHas('facturas', [
            'cliente_id'     => $cliente->id,
            'metodo_pago_id' => $this->nequi->id,
            'no_factura'     => 41,
            'abono'          => 30000,
        ]);

        $this->assertEquals(41, $this->sede->fresh()->no_factura);
        $this->assertEquals(70000, $cliente->fresh()->saldo);
    }

    public function test_vista_factura_muestra_documento_y_tipo_de_pago(): void
    {
        $cliente = $this->cliente();

        Factura::create([
            'cliente_id'      => $cliente->id,
            'sede_id'         => $this->sede->id,
            'especialista_id' => $this->especialista->id,
            'metodo_pago_id'  => $this->efectivo->id,
            'nombre'          => 'Juan Perez',
            'abono'           => 5000,
            'saldo'           => 0,
            'no_factura'      => 41,
        ]);

        $this->conSede()
            ->get(route('facturaCliente', $cliente->id))
            ->assertOk()
            ->assertSee('Tipo de pago')
            ->assertSee('Documento')
            ->assertSee('Efectivo')
            ->assertSee('1234567');
    }

    public function test_factura_vieja_sin_metodo_muestra_sin_registrar(): void
    {
        $cliente = $this->cliente();

        Factura::create([
            'cliente_id'      => $cliente->id,
            'sede_id'         => $this->sede->id,
            'especialista_id' => $this->especialista->id,
            'metodo_pago_id'  => null,
            'nombre'          => 'Juan Perez',
            'abono'           => 5000,
            'saldo'           => 0,
            'no_factura'      => 12,
        ]);

        $this->conSede()
            ->get(route('facturaCliente', $cliente->id))
            ->assertOk()
            ->assertSee('Sin registrar');
    }

    public function test_popup_factura_trae_metodos_de_pago(): void
    {
        $cliente = $this->cliente();

        $this->conSede()
            ->get(route('facturaCliente', $cliente->id))
            ->assertOk()
            ->assertSee('metodo-pago-input')
            ->assertSee('Nequi');
    }

    // ----------------------------------------------------------- CIERRE DIA

    public function test_total_dia_agrupa_por_metodo_de_pago(): void
    {
        $cliente = $this->cliente();

        foreach ([[$this->efectivo->id, 10000], [$this->efectivo->id, 5000], [$this->nequi->id, 7000], [null, 3000]] as $i => [$metodo, $abono]) {
            Factura::create([
                'cliente_id'      => $cliente->id,
                'sede_id'         => $this->sede->id,
                'especialista_id' => $this->especialista->id,
                'metodo_pago_id'  => $metodo,
                'nombre'          => 'Juan Perez',
                'abono'           => $abono,
                'saldo'           => 0,
                'no_factura'      => 100 + $i,
            ]);
        }

        $respuesta = $this->conSede()->get(route('facturas.totalDia'));

        $respuesta->assertOk()
            ->assertSee('Por metodo de pago')
            ->assertSee('Efectivo')
            ->assertSee('15.000')
            ->assertSee('Nequi')
            ->assertSee('7.000')
            ->assertSee('Sin registrar')
            ->assertSee('3.000');
    }

    // -------------------------------------------------------- METODOS DE PAGO

    public function test_crud_metodos_de_pago(): void
    {
        $this->conSede()->get(route('metodoPagos.index'))
            ->assertOk()
            ->assertSee('Efectivo');

        $this->conSede()->post(route('metodoPagos.store'), ['nombre' => 'Bancolombia'])
            ->assertRedirect()
            ->assertSessionHas('mensajeCreado');

        $this->assertDatabaseHas('metodo_pagos', ['nombre' => 'Bancolombia']);

        $nuevo = MetodoPago::where('nombre', 'Bancolombia')->first();

        $this->conSede()->get(route('metodoPagos.edit', $nuevo->id))->assertOk();

        $this->conSede()->put(route('metodoPagos.update', $nuevo->id), ['nombre' => 'Bancolombia QR'])
            ->assertRedirect(route('metodoPagos.index'));

        $this->assertDatabaseHas('metodo_pagos', ['nombre' => 'Bancolombia QR']);
    }

    public function test_no_permite_metodo_de_pago_duplicado(): void
    {
        $this->conSede()
            ->from(route('metodoPagos.index'))
            ->post(route('metodoPagos.store'), ['nombre' => 'Efectivo'])
            ->assertSessionHasErrors('nombre');
    }

    public function test_configuracion_enlaza_metodos_de_pago(): void
    {
        $this->conSede()->get(route('configuracion'))
            ->assertOk()
            ->assertSee('Metodos de Pago');
    }

    // ------------------------------------------------------- POSIBLES HUECOS

    /** El error de duplicado queda en sesion, pero la vista lo muestra? */
    public function test_hueco_error_duplicado_se_ve_en_pantalla(): void
    {
        $this->conSede()
            ->from(route('metodoPagos.index'))
            ->post(route('metodoPagos.store'), ['nombre' => 'Efectivo']);

        $this->conSede()->get(route('metodoPagos.index'))
            ->assertSee('Ya existe un metodo de pago con ese nombre.');
    }

    /** Un colaborador (rol 2) puede entrar a metodos de pago? */
    public function test_hueco_colaborador_entra_a_metodos_de_pago(): void
    {
        $colaborador = Usuario::create([
            'nombre'     => 'Recepcion',
            'correo'     => 'recepcion@test.com',
            'contrasena' => 'secreto123',
            'rol_id'     => Rol::where('nombre', 'Colaborador')->first()->id,
        ]);

        $this->actingAs($colaborador)->withSession([
            'sede' => ['id' => $this->sede->id, 'nombre' => $this->sede->nombre],
        ])->get(route('metodoPagos.index'))->assertOk(); // documenta: hoy SI entra
    }

    /** Rutas del resource sin metodo en el controlador */
    public function test_hueco_rutas_resource_sin_metodo(): void
    {
        $this->conSede()->get('/metodoPagos/create')->assertStatus(405); // ya no es error 500
    }

    /** Se avisa cuando hay mas de 50 coincidencias? */
    public function test_hueco_aviso_de_limite_50(): void
    {
        for ($i = 0; $i < 55; $i++) {
            $this->cliente([
                'nombre'           => 'Maria',
                'primer_apellido'  => 'Lopez' . $i,
                'numero_documento' => '90' . $i,
            ]);
        }

        $this->conSede()
            ->post(route('clientes.buscar'), ['numeroDocumento' => 'Maria'])
            ->assertSee('Se muestran los 50 primeros');
    }

    // ---------------------------------------------------------------- AGENDA

    public function test_agenda_agrupa_por_doctor(): void
    {
        $otro = Especialista::create(['nombre' => 'Dr Segundo', 'sede_id' => $this->sede->id]);

        $a = $this->cliente(['nombre' => 'Ana',  'numero_documento' => '111']);
        $b = $this->cliente(['nombre' => 'Beto', 'numero_documento' => '222']);

        $a->agendarProximaCita(now()->addDay()->setTime(9, 0), $this->sede->id, $this->especialista->id);
        $b->agendarProximaCita(now()->addDay()->setTime(15, 30), $this->sede->id, $otro->id);

        $this->conSede()
            ->get(route('clientes.citas', ['sedeId' => $this->sede->id, 'fecha' => now()->addDay()->toDateString()]))
            ->assertOk()
            ->assertSee('doctor-titulo', false)
            ->assertSee('Dra Prueba')
            ->assertSee('Dr Segundo')
            ->assertSee('2 citas');
    }

    public function test_agenda_marca_los_dias_con_citas_en_el_calendario(): void
    {
        $c = $this->cliente();
        $c->agendarProximaCita(now()->addDays(3)->setTime(10, 0), $this->sede->id, $this->especialista->id);

        $this->conSede()
            ->get(route('clientes.citas', $this->sede->id))
            ->assertOk()
            ->assertSee('calendario-marca', false)
            ->assertSee('calendario-mover', false);
    }

    public function test_agenda_no_ve_citas_de_otra_sede(): void
    {
        $otraSede = Sede::create(['nombre'=>'Otra','nit'=>'9','direccion'=>'z','telefono'=>'9','celular'=>'9','no_factura'=>0]);

        $c = $this->cliente();
        $c->agendarProximaCita(now()->addDay()->setTime(9, 0), $otraSede->id, null);

        $this->conSede()
            ->get(route('clientes.citas', ['sedeId' => $this->sede->id, 'fecha' => now()->addDay()->toDateString()]))
            ->assertOk()
            ->assertSee('No hay citas para este d', false);
    }

    public function test_agenda_muestra_documento_y_telefono(): void
    {
        $c = $this->cliente(['numero_documento' => '55667788']);
        $c->agendarProximaCita(now()->addDay()->setTime(10, 0), $this->sede->id, $this->especialista->id);

        $this->conSede()
            ->get(route('clientes.citas', ['sedeId' => $this->sede->id, 'fecha' => now()->addDay()->toDateString()]))
            ->assertOk()
            ->assertSee('55667788')
            ->assertSee('333');
    }

    /** data-fecha debe llegar al HTML para que el JS precargue el popup */
    public function test_agenda_envia_fecha_para_precargar(): void
    {
        $c = $this->cliente();
        $c->agendarProximaCita(now()->addDay()->setTime(11, 15), $this->sede->id, $this->especialista->id);

        $this->conSede()
            ->get(route('clientes.citas', ['sedeId' => $this->sede->id, 'fecha' => now()->addDay()->toDateString()]))
            ->assertOk()
            ->assertSee('data-fecha', false)
            ->assertSee(now()->addDay()->format('Y-m-d'), false);
    }

    public function test_agenda_lista_pendientes_sin_reagendar(): void
    {
        $v = $this->cliente(['nombre' => 'Vencido', 'numero_documento' => '999']);
        \App\Models\Cita::create([
            'cliente_id' => $v->id, 'sede_id' => $this->sede->id,
            'fecha_hora' => now()->subDays(3)->setTime(10, 0), 'estado' => 'agendada',
        ]);

        $this->conSede()
            ->get(route('clientes.citas', $this->sede->id))
            ->assertOk()
            ->assertSee('Sin reagendar')
            ->assertSee('Vencido');
    }

    public function test_agenda_sin_citas_avisa(): void
    {
        $this->conSede()
            ->get(route('clientes.citas', $this->sede->id))
            ->assertOk()
            ->assertSee('No hay citas para este d', false);
    }

    public function test_agendar_cita_sigue_funcionando(): void
    {
        $cliente = $this->cliente();
        $nueva   = now()->addDays(2)->setTime(14, 0)->format('Y-m-d\TH:i');

        $this->conSede()
            ->patch(route('clientes.agendar', $cliente->id), [
                'fecha_cita' => $nueva,
                'sede_id'    => $this->sede->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('mensaje');

        $this->assertNotNull($cliente->fresh()->fecha_cita);
    }

    public function test_eliminar_cita_sigue_funcionando(): void
    {
        $cliente = $this->cliente(['fecha_cita' => now()->setTime(10, 0)]);

        $this->conSede()
            ->patch(route('clientes.agendar', $cliente->id), [
                'fecha_cita' => '',
                'sede_id'    => $this->sede->id,
            ])
            ->assertRedirect();

        $this->assertNull($cliente->fresh()->fecha_cita);
    }

    // ------------------------------------------------- CONSECUTIVO DE FACTURA

    public function test_el_consecutivo_avanza_de_uno_en_uno(): void
    {
        $cliente = $this->cliente();

        foreach ([41, 42, 43] as $esperado) {
            $this->conSede()->postJson('/facturas/guardar', [
                'clienteId'      => $cliente->id,
                'sedeId'         => $this->sede->id,
                'especialistaId' => $this->especialista->id,
                'metodoPagoId'   => $this->efectivo->id,
                'nombre'         => 'Juan Perez',
                'abono'          => 1000,
                'saldoFinal'     => 0,
            ])->assertOk()->assertJson(['noFactura' => $esperado]);
        }

        $this->assertEquals(43, $this->sede->fresh()->no_factura);
        $this->assertEquals(3, Factura::count());
    }

    /** Si algo revienta a mitad, el consecutivo no se gasta */
    public function test_si_falla_la_factura_el_consecutivo_no_avanza(): void
    {
        $cliente = $this->cliente();

        try {
            $this->conSede()->postJson('/facturas/guardar', [
                'clienteId'      => $cliente->id,
                'sedeId'         => $this->sede->id,
                'especialistaId' => 99999,   // no existe: viola la llave foranea
                'metodoPagoId'   => $this->efectivo->id,
                'nombre'         => 'Juan Perez',
                'abono'          => 1000,
                'saldoFinal'     => 0,
            ]);
        } catch (\Throwable $e) {
            // se esperaba que fallara
        }

        $this->assertEquals(40, $this->sede->fresh()->no_factura, 'El consecutivo no debe gastarse');
        $this->assertEquals(0, Factura::count(), 'No debe quedar factura a medias');
    }

    public function test_sin_sede_activa_no_se_factura(): void
    {
        $cliente = $this->cliente();

        $this->actingAs($this->admin)->withSession(['sede' => ['id' => 99999]])
            ->postJson('/facturas/guardar', [
                'clienteId'      => $cliente->id,
                'sedeId'         => $this->sede->id,
                'especialistaId' => $this->especialista->id,
                'metodoPagoId'   => $this->efectivo->id,
                'nombre'         => 'Juan Perez',
                'abono'          => 1000,
                'saldoFinal'     => 0,
            ])
            ->assertStatus(422);

        $this->assertEquals(0, Factura::count());
    }
}

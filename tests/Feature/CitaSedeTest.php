<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Eps;
use App\Models\Especialista;
use App\Models\Sede;
use App\Models\TipoDocumento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitaSedeTest extends TestCase
{
    use RefreshDatabase;

    private function armar(): array
    {
        $sedeA = Sede::create(['nombre'=>'Republica','nit'=>'1','direccion'=>'a','telefono'=>'1','celular'=>'1','no_factura'=>0]);
        $sedeB = Sede::create(['nombre'=>'Colseguros','nit'=>'2','direccion'=>'b','telefono'=>'2','celular'=>'2','no_factura'=>0]);

        $docA = Especialista::create(['nombre'=>'Dra Pena','sede_id'=>$sedeA->id]);
        $docB = Especialista::create(['nombre'=>'Dr Marin','sede_id'=>$sedeB->id]);

        TipoDocumento::create(['nombre'=>'CC']);
        Eps::create(['nombre'=>'Sura']);

        $cliente = Cliente::create([
            'nombre'=>'Juan','primer_apellido'=>'Perez','numero_documento'=>'111',
            'direccion'=>'x','telefono'=>'1','fecha_nacimiento'=>'1990-01-01','saldo'=>0,
            'tipo_documento_id'=>TipoDocumento::first()->id,'eps_id'=>Eps::first()->id,
            'sede_id'=>$sedeA->id,
        ]);

        return compact('sedeA','sedeB','docA','docB','cliente');
    }

    public function test_la_agenda_de_una_sede_no_ve_las_citas_de_la_otra(): void
    {
        ['sedeA'=>$a, 'sedeB'=>$b, 'docA'=>$docA, 'docB'=>$docB, 'cliente'=>$c] = $this->armar();

        Cita::create(['cliente_id'=>$c->id,'sede_id'=>$a->id,'especialista_id'=>$docA->id,'fecha_hora'=>now()->addDay()->setTime(9,0)]);
        Cita::create(['cliente_id'=>$c->id,'sede_id'=>$b->id,'especialista_id'=>$docB->id,'fecha_hora'=>now()->addDay()->setTime(11,0)]);

        $this->assertEquals(1, Cita::where('sede_id', $a->id)->count(), 'Sede A debe ver solo la suya');
        $this->assertEquals(1, Cita::where('sede_id', $b->id)->count(), 'Sede B debe ver solo la suya');

        $this->assertEquals($docA->id, Cita::where('sede_id',$a->id)->first()->especialista_id);
        $this->assertEquals($docB->id, Cita::where('sede_id',$b->id)->first()->especialista_id);
    }

    public function test_el_espejo_toma_la_proxima_cita_sin_importar_la_sede(): void
    {
        ['sedeA'=>$a, 'sedeB'=>$b, 'cliente'=>$c] = $this->armar();

        Cita::create(['cliente_id'=>$c->id,'sede_id'=>$b->id,'fecha_hora'=>now()->addDays(2)->setTime(11,0)]);
        Cita::create(['cliente_id'=>$c->id,'sede_id'=>$a->id,'fecha_hora'=>now()->addDay()->setTime(9,0)]);

        // clientes.fecha_cita queda con la mas proxima de las dos
        $this->assertEquals(
            now()->addDay()->setTime(9,0)->format('Y-m-d H:i'),
            $c->fresh()->fecha_cita->format('Y-m-d H:i')
        );
    }

    public function test_una_cita_pasada_no_queda_como_proxima(): void
    {
        ['sedeA'=>$a, 'cliente'=>$c] = $this->armar();

        Cita::create(['cliente_id'=>$c->id,'sede_id'=>$a->id,'fecha_hora'=>now()->subDays(3)->setTime(9,0)]);

        $this->assertNull($c->fresh()->fecha_cita);
    }

    public function test_borrar_la_cita_limpia_el_espejo(): void
    {
        ['sedeA'=>$a, 'cliente'=>$c] = $this->armar();

        $cita = Cita::create(['cliente_id'=>$c->id,'sede_id'=>$a->id,'fecha_hora'=>now()->addDay()->setTime(9,0)]);
        $this->assertNotNull($c->fresh()->fecha_cita);

        $cita->delete();
        $this->assertNull($c->fresh()->fecha_cita);
    }

    public function test_reagendar_mueve_la_cita_no_crea_otra(): void
    {
        ['sedeA'=>$a, 'docA'=>$doc, 'cliente'=>$c] = $this->armar();

        $c->agendarProximaCita(now()->addDay()->setTime(9,0), $a->id, $doc->id);
        $this->assertEquals(1, $c->citas()->count());

        // Reagendar para otro dia
        $c->agendarProximaCita(now()->addDays(5)->setTime(15,0), $a->id, $doc->id);

        $this->assertEquals(1, $c->citas()->count(), 'No debe quedar la cita vieja colgada');
        $this->assertEquals(
            now()->addDays(5)->setTime(15,0)->format('Y-m-d H:i'),
            $c->citas()->first()->fecha_hora->format('Y-m-d H:i')
        );
    }

    public function test_cancelar_deja_rastro_y_limpia_el_espejo(): void
    {
        ['sedeA'=>$a, 'cliente'=>$c] = $this->armar();

        $c->agendarProximaCita(now()->addDay()->setTime(9,0), $a->id);
        $c->cancelarProximaCita();

        $this->assertNull($c->fresh()->fecha_cita, 'El espejo debe quedar vacio');
        $this->assertEquals(1, $c->citas()->count(), 'La cita no se borra');
        $this->assertEquals('cancelada', $c->citas()->first()->estado);
    }

    public function test_una_cita_cumplida_no_estorba_a_la_siguiente(): void
    {
        ['sedeA'=>$a, 'cliente'=>$c] = $this->armar();

        // Cita pasada: el paciente ya vino
        Cita::create(['cliente_id'=>$c->id,'sede_id'=>$a->id,'fecha_hora'=>now()->subDays(2)->setTime(9,0)]);

        // Al facturar se le agenda la siguiente
        $c->agendarProximaCita(now()->addDays(7)->setTime(10,0), $a->id);

        $this->assertEquals(2, $c->citas()->count(), 'La pasada se conserva como historial');
        $this->assertEquals(
            now()->addDays(7)->setTime(10,0)->format('Y-m-d H:i'),
            $c->fresh()->fecha_cita->format('Y-m-d H:i')
        );
    }

    // ------------------------------------------- AGENDAR DESDE EL CALENDARIO

    private function comoAdmin($sede)
    {
        $rol = \App\Models\Rol::firstOrCreate(['nombre' => 'Administrador']);

        $admin = \App\Models\Usuario::create([
            'nombre' => 'Admin', 'correo' => 'a@a.com',
            'contrasena' => 'secreto123', 'rol_id' => $rol->id,
        ]);

        return $this->actingAs($admin)->withSession([
            'sede' => ['id' => $sede->id, 'nombre' => $sede->nombre],
        ]);
    }

    public function test_buscar_paciente_devuelve_json(): void
    {
        ['sedeA'=>$a] = $this->armar();

        $this->comoAdmin($a)
            ->getJson(route('agenda.clientes', ['q' => 'Juan']))
            ->assertOk()
            ->assertJsonFragment(['documento' => '111']);
    }

    public function test_buscar_paciente_ignora_textos_muy_cortos(): void
    {
        ['sedeA'=>$a] = $this->armar();

        $this->comoAdmin($a)
            ->getJson(route('agenda.clientes', ['q' => 'Ju']))
            ->assertOk()
            ->assertExactJson([]);
    }

    public function test_agendar_desde_el_calendario_crea_la_cita(): void
    {
        ['sedeA'=>$a, 'docA'=>$doc, 'cliente'=>$c] = $this->armar();

        $cuando = now()->addDays(4)->setTime(10, 30);

        $this->comoAdmin($a)
            ->post(route('citas.store'), [
                'cliente_id'      => $c->id,
                'especialista_id' => $doc->id,
                'fecha_hora'      => $cuando->format('Y-m-d\TH:i'),
            ])
            ->assertRedirect()
            ->assertSessionHas('mensaje');

        $this->assertDatabaseHas('citas', [
            'cliente_id'      => $c->id,
            'especialista_id' => $doc->id,
            'sede_id'         => $a->id,
            'estado'          => 'agendada',
        ]);

        // El espejo tambien se actualiza, para la impresion de la factura
        $this->assertEquals($cuando->format('Y-m-d H:i'), $c->fresh()->fecha_cita->format('Y-m-d H:i'));
    }

    public function test_la_sede_sale_de_la_sesion_no_del_formulario(): void
    {
        ['sedeA'=>$a, 'sedeB'=>$b, 'docA'=>$doc, 'cliente'=>$c] = $this->armar();

        // Se intenta colar otra sede en el formulario
        $this->comoAdmin($a)
            ->post(route('citas.store'), [
                'cliente_id'      => $c->id,
                'especialista_id' => $doc->id,
                'fecha_hora'      => now()->addDay()->setTime(9,0)->format('Y-m-d\TH:i'),
                'sede_id'         => $b->id,
            ])
            ->assertRedirect();

        $this->assertEquals($a->id, Cita::first()->sede_id, 'Debe usar la sede de la sesion');
    }

    public function test_agendar_exige_paciente_y_doctor(): void
    {
        ['sedeA'=>$a] = $this->armar();

        $this->comoAdmin($a)
            ->post(route('citas.store'), ['fecha_hora' => now()->addDay()->format('Y-m-d\TH:i')])
            ->assertSessionHasErrors(['cliente_id', 'especialista_id']);
    }
}

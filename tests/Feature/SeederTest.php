<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_los_seeders_guardan_los_acentos_bien(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $esperados = [
            'metodo_pagos'    => 'Tarjeta débito o crédito',
            'eps'             => 'Salud Mía',
            'tipo_documentos' => 'Cédula de ciudadanía',
            'sedes'           => 'Peña',
        ];

        foreach ($esperados as $tabla => $nombre) {
            $this->assertDatabaseHas($tabla, ['nombre' => $nombre]);
        }

        // Y que ninguno haya quedado con la corrupcion tipico de consola
        foreach (array_keys($esperados) as $tabla) {
            $sucios = DB::table($tabla)->where('nombre', 'like', '%Ú%')
                ->orWhere('nombre', 'like', '%Ã%')->count();
            $this->assertEquals(0, $sucios, "Hay nombres corruptos en {$tabla}");
        }
    }

    public function test_los_cuatro_metodos_son_los_que_pidio_el_cliente(): void
    {
        $this->seed(\Database\Seeders\MetodoPagoSeeder::class);

        $this->assertEquals([
            'Efectivo',
            'Tarjeta débito o crédito',
            'Transferencia Nequi',
            'Transferencia Bancolombia',
        ], DB::table('metodo_pagos')->orderBy('id')->pluck('nombre')->all());
    }

    public function test_el_seeder_no_expone_datos_reales(): void
    {
        $codigo = file_get_contents(database_path('seeders/UsuarioSeeder.php'));

        // Ni contrasenas ni correos reales de la clinica
        foreach (['Administrador198', 'colseguros5b', 'republicad5',
                  'penadent.com', 'develobri', 'gmail.com'] as $dato) {
            $this->assertStringNotContainsString($dato, $codigo);
        }
    }

    public function test_los_dos_usuarios_de_demo_tienen_roles_distintos(): void
    {
        $this->seed(\Database\Seeders\RolSeeder::class);
        $this->seed(\Database\Seeders\UsuarioSeeder::class);

        $admin       = \App\Models\Usuario::where('correo', 'admin@demo.test')->first();
        $colaborador = \App\Models\Usuario::where('correo', 'recepcion@demo.test')->first();

        $this->assertNotNull($admin, 'Falta el usuario administrador de demo');
        $this->assertNotNull($colaborador, 'Falta el usuario colaborador de demo');

        $this->assertEquals('Administrador', $admin->rol->nombre);
        $this->assertEquals('Colaborador', $colaborador->rol->nombre);

        // Y que las contrasenas por defecto sirvan para entrar
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('admin12345', $admin->contrasena));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('recepcion12345', $colaborador->contrasena));
    }


    public function test_el_colaborador_no_entra_donde_solo_va_el_admin(): void
    {
        $this->seed(\Database\Seeders\RolSeeder::class);
        $this->seed(\Database\Seeders\SedeSeeder::class);
        $this->seed(\Database\Seeders\UsuarioSeeder::class);

        $sede = \App\Models\Sede::first();
        $sesion = ['sede' => ['id' => $sede->id, 'nombre' => $sede->nombre]];

        $admin       = \App\Models\Usuario::where('correo', 'admin@demo.test')->first();
        $colaborador = \App\Models\Usuario::where('correo', 'recepcion@demo.test')->first();

        // El administrador si entra
        $this->actingAs($admin)->withSession($sesion)
            ->get(route('usuarios.index'))->assertOk();
        $this->actingAs($admin)->withSession($sesion)
            ->get(route('facturas.totalDia'))->assertOk();

        // El colaborador es devuelto al inicio
        $this->actingAs($colaborador)->withSession($sesion)
            ->get(route('usuarios.index'))->assertRedirect(route('inicio'));
        $this->actingAs($colaborador)->withSession($sesion)
            ->get(route('facturas.totalDia'))->assertRedirect(route('inicio'));
    }
}

<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EncodingTest extends TestCase
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

    public function test_el_seeder_de_usuarios_ya_no_trae_contrasenas(): void
    {
        $codigo = file_get_contents(database_path('seeders/UsuarioSeeder.php'));

        foreach (['Administrador198', 'colseguros5b', 'republicad5'] as $vieja) {
            $this->assertStringNotContainsString($vieja, $codigo);
        }
    }
}

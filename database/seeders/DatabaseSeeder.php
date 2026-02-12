<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Especialista;
use App\Models\Factura;
use App\Models\Historia;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        
        $this->call([
            RolSeeder::class,
            UsuarioSeeder::class,
            TipoDocumentoSeeder::class,
            SedeSeeder::class,
            EpsSeeder::class,
            GrupoSeeder::class,
            DienteSeeder::class,
            
            ]);

            Cliente::factory(10)->create();
            Especialista::factory(8)->create();
            Historia::factory(40)->create();
            Factura::factory(50)->create();

            }
}

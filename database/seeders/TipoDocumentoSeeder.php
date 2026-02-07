<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoDocumento::insert([
            ['nombre' => 'Cédula de ciudadanía'],
            ['nombre' => 'Tarjeta de identidad'],
            ['nombre' => 'Registro civil'],
            ['nombre' => 'Cédula de extranjería'],
            ['nombre' => 'Pasaporte'],

        ]);
    }
}

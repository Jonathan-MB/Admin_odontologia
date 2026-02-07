<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Grupo::insert([
            ['nombre' => 'Vestibular Arriba Derecha'],
            ['nombre' => 'Vestibular Arriba Izquierda'],
            ['nombre' => 'Vestibular Abajo Derecha'],
            ['nombre' => 'Vestibular Abajo Izquierda'],
            ['nombre' => 'Unguales Arriba Derecha'],
            ['nombre' => 'Unguales Arriba Izquierda'],
            ['nombre' => 'Unguales Abajo Derecha'],
            ['nombre' => 'Unguales Abajo Izquierda'],
            ['nombre' => 'General'],
            ['nombre' => 'Otros'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            'Vestibular Arriba Derecha',
            'Vestibular Arriba Izquierda',
            'Vestibular Abajo Derecha',
            'Vestibular Abajo Izquierda',
            'Unguales Arriba Derecha',
            'Unguales Arriba Izquierda',
            'Unguales Abajo Derecha',
            'Unguales Abajo Izquierda',
            'General',
            'Otros',
        ];

        foreach ($grupos as $nombre) {
            Grupo::firstOrCreate([
                'nombre' => $nombre,
            ]);
        }
    }
}

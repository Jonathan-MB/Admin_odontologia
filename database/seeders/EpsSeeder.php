<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Eps;

class EpsSeeder extends Seeder
{
    public function run(): void
    {


        $eps = [
            'Coosalud',
            'Nueva EPS',
            'Salud Mía',
            'Salud Total',
            'Sanitas',
            'Sura',
            'Famisanar',
            'SOS (Servicio Occidental de Salud)',
            'Comfenalco Valle',
            'Compensar',
            'Emssanar',
        ];

        foreach ($eps as $nombre) {
            Eps::firstOrCreate([
                'nombre' => $nombre,
            ]);
        }
    }
}

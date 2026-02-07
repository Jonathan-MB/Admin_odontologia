<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Eps;

class EpsSeeder extends Seeder
{
    public function run(): void
    {
        Eps::insert([
            ['nombre' => 'Coosalud'],
            ['nombre' => 'Nueva EPS'],
            ['nombre' => 'Salud Mía'],
            ['nombre' => 'Salud Total'],
            ['nombre' => 'Sanitas'],
            ['nombre' => 'Sura'],
            ['nombre' => 'Famisanar'],
            ['nombre' => 'SOS (Servicio Occidental de Salud)'],
            ['nombre' => 'Comfenalco Valle'],
            ['nombre' => 'Compensar'],
            ['nombre' => 'Emssanar'],
        ]);
    }
}

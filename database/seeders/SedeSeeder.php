<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sede::firstOrCreate(
            ['nombre' => 'Republica de Israel'],
            [
                'nit'       => '38552615-6',
                'direccion' => 'Cra 42B No.40-44',
                'telefono'  => '602 338 9412',
                'celular'  => '310 502 6398',

            ]
        );
        Sede::firstOrCreate(
            ['nombre' => 'Peña'], 
            [                                    
                'nit'       => '14637567-1',
                'direccion' => 'Diag 23 No.10B-45 B/Colseguros',
                'telefono'  => '602 885 2034',
                'celular'  => '305 332 8436',

            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Diente;
use App\Models\Grupo;
use Illuminate\Database\Seeder;

class DienteSeeder extends Seeder
{
    public function run(): void
    {


        //  Crear diente "General" en grupo "General"
        $grupoGeneral = Grupo::where('nombre', 'General')->first();
        if ($grupoGeneral) {
            Diente::firstOrCreate([

                'nombre'    => 'General',
                'grupo_id'  => $grupoGeneral->id,

            ]);
        }


        $config = [
            // VESTIBULARES
            'Vestibular Arriba Derecha'   => range(11, 18),
            'Vestibular Arriba Izquierda' => range(21, 28),
            'Vestibular Abajo Izquierda'  => range(31, 38),
            'Vestibular Abajo Derecha'    => range(41, 48),

            // UNGUALES
            'Unguales Arriba Derecha'     => range(51, 55),
            'Unguales Arriba Izquierda'   => range(61, 65),
            'Unguales Abajo Izquierda'    => range(71, 75),
            'Unguales Abajo Derecha'      => range(81, 85),
        ];

        // 🔹 Crear dientes numerados
        foreach ($config as $nombreGrupo => $dientes) {
            $grupo = Grupo::where('nombre', $nombreGrupo)->first();

            if (!$grupo) {
                continue;
            }

            foreach ($dientes as $numero) {
                Diente::firstOrCreate([

                    'nombre'    => (string) $numero,
                    'grupo_id'  => $grupo->id,

                ]);
            }
        }




    }
}

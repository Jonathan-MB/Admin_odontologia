<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Especialista;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cliente_id'        => Cliente::inRandomOrder()->value('id'),
            'sede_id'           => Sede::inRandomOrder()->value('id'),
            'especialista_id'   => Especialista::inRandomOrder()->value('id'),
            'fecha_hora'        => fake()->dateTimeBetween('now', '+1 month'),
            'estado'            => 'agendada',
            'observacion'       => null,

        ];
    }
}

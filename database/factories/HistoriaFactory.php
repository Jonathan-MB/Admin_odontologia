<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Diente;
use App\Models\Especialista;

use Illuminate\Database\Eloquent\Factories\Factory;

class HistoriaFactory extends Factory
{

    public function definition(): array
    {
        return [
            'cliente_id'        => fake()->numberBetween(1,10),
            'especialista_id'   => fake()->numberBetween(1,8),
            'diente_id'         => fake()->numberBetween(1,54),
            'observacion'       => fake()->realTextBetween(15,400),
            ];
    }
}

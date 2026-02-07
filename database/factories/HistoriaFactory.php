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
            'cliente_id'=>Cliente::inRandomOrder()->value('id'),
            'especialista_id'=>Especialista::inRandomOrder()->value('id'),
            'diente_id'=>Diente::inRandomOrder()->value('id'),
            'observacion'=>fake()->realTextBetween(15,400),
            ];
    }
}

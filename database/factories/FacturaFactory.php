<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Especialista;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacturaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'abono'             => fake()->randomFloat(2, 0, 10000),
            'saldo'             => fake()->randomFloat(2, 0, 1000000),
            'cliente_id'        => fake()->numberBetween(1,10),
            'especialista_id'   => fake()->numberBetween(1,8),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Especialista;
use App\Models\MetodoPago;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacturaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'abono'             => fake()->randomFloat(2, 0, 10000),
            'saldo'             => fake()->randomFloat(2, 0, 1000000),
            'nombre'            => fake()->name(),
            'cliente_id'        => Cliente::inRandomOrder()->value('id'),
            'sede_id'           => Sede::inRandomOrder()->value('id'),
            'especialista_id'   => Especialista::inRandomOrder()->value('id'),
            'metodo_pago_id'    => MetodoPago::inRandomOrder()->value('id'),
            'no_factura'        => fake()->numberBetween(1,999),

        ];
    }
}

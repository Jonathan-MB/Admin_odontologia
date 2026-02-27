<?php

namespace Database\Factories;

use App\Models\Eps;
use App\Models\TipoDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'            => fake()->firstName(),
            'primer_apellido'   => fake()->lastName(),
            'segundo_apellido'  => fake()->lastName(),
            'numero_documento'  => fake()->unique()->numerify('##########'),
            'direccion'         => fake()->address(),
            'correo'            => fake()->unique()->safeEmail(),
            'telefono'          => fake()->phoneNumber(),
            'fecha_nacimiento'  => fake()->dateTimeBetween('-80 years', '-5 years')->format('Y-m-d'),
            'fecha_cita'        => fake()->dateTimeBetween('now', '+1 month'),
            'saldo'             => fake()->randomFloat(2, 0, 1000000),
            'tipo_documento_id' => TipoDocumento::inRandomOrder()->value('id'),
            'eps_id'            => Eps::inRandomOrder()->value('id'),
            'sede_id'            => fake()->numberBetween(1,2),
        ];
    }
}

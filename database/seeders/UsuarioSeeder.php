<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();
        $rolColaborador = Rol::where('nombre', 'Colaborador')->first();

        // Usuario Administrador
        Usuario::firstOrCreate(
            ['correo' => 'develobri@gmail.com'],
            [
                'nombre' => 'Jonathan Marin',
                'contrasena' => Hash::make('Administrador1$'),
                'rol_id' => $rolAdmin?->id,
            ]
        );

        // Usuario de pruebas
        Usuario::firstOrCreate(
            ['correo' => 'pruebas@gmail.com'],
            [
                'nombre' => 'Usuario Prueba',
                'contrasena' => Hash::make('Prueba1$'),
                'rol_id' => $rolColaborador?->id,
            ]
        );
    }
}

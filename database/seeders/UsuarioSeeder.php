<?php
namespace Database\Seeders;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Usuarios de demostracion, para que cualquiera pueda clonar el
     * proyecto y probar los dos roles.
     *
     *   Administrador  ve Usuarios, Especialistas y Total Diario
     *   Colaborador    no los ve, ni entrando por la URL
     *
     * Los correos son ficticios y usan el dominio .test, reservado por
     * la IANA para pruebas: nunca podra existir de verdad.
     *
     * En produccion NO se ejecuta: los usuarios reales se crean desde
     * la propia aplicacion, en Configuracion / Usuarios.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('  UsuarioSeeder omitido: en produccion los usuarios se crean desde la app.');
            return;
        }

        $rolAdmin = Rol::where('nombre', 'Administrador')->first();
        $rolColaborador = Rol::where('nombre', 'Colaborador')->first();

        // Administrador
        Usuario::firstOrCreate(
            ['correo' => 'admin@demo.test'],
            [
                'nombre' => 'Administrador Demo',
                'contrasena' => env('SEED_PASS_ADMIN', 'admin12345'),
                'rol_id' => $rolAdmin?->id,
            ]
        );

        // Colaborador
        Usuario::firstOrCreate(
            ['correo' => 'recepcion@demo.test'],
            [
                'nombre' => 'Recepcion Demo',
                'contrasena' => env('SEED_PASS_COLABORADOR', 'recepcion12345'),
                'rol_id' => $rolColaborador?->id,
            ]
        );

        $this->command?->info('  Demo -> admin@demo.test / recepcion@demo.test');
    }
}

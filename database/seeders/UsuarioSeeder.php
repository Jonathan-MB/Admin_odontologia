<?php
namespace Database\Seeders;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();
        $rolColaborador = Rol::where('nombre', 'Colaborador')->first();

        // Administradores
        Usuario::firstOrCreate(
            ['correo' => 'develobri@gmail.com'],
            [
                'nombre' => 'Jonathan Marin',
                'contrasena' => $this->clave('SEED_PASS_JONATHAN'),
                'rol_id' => $rolAdmin?->id,
            ]
        );

        Usuario::firstOrCreate(
            ['correo' => 'Admin.Republica@penadent.com'],
            [
                'nombre' => 'Administrador Republica',
                'contrasena' => $this->clave('SEED_PASS_ADMIN_REPUBLICA'),
                'rol_id' => $rolAdmin?->id,
            ]
        );

        Usuario::firstOrCreate(
            ['correo' => 'Admin.Colseguros@penadent.com'],
            [
                'nombre' => 'Administrador Colseguros',
                'contrasena' => $this->clave('SEED_PASS_ADMIN_COLSEGUROS'),
                'rol_id' => $rolAdmin?->id,
            ]
        );

        // Colaboradores
        Usuario::firstOrCreate(
            ['correo' => 'colseguros@penadent.com'],
            [
                'nombre' => 'Recepcion Colseguros',
                'contrasena' => $this->clave('SEED_PASS_COLSEGUROS'),
                'rol_id' => $rolColaborador?->id,
            ]
        );

        Usuario::firstOrCreate(
            ['correo' => 'republica@penadent.com'],
            [
                'nombre' => 'Recepcion Republica',
                'contrasena' => $this->clave('SEED_PASS_REPUBLICA'),
                'rol_id' => $rolColaborador?->id,
            ]
        );
    }


    /**
     * Las contrasenas ya no viven en el codigo: se leen de .env, que no
     * se versiona. Si la variable no esta definida se genera una al azar
     * y se muestra en consola, para que nunca quede una clave conocida
     * por defecto en una instalacion nueva.
     */
    private function clave(string $variable): string
    {
        $clave = env($variable);

        if ($clave) {
            return $clave;
        }

        $clave = Str::random(14);

        $this->command?->warn("  {$variable} sin definir en .env -> generada: {$clave}");

        return $clave;
    }
}

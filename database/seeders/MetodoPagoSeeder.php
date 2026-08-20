<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {

        $metodoPagos = [
            'Efectivo',
            'Tarjeta débito o crédito',
            'Transferencia Nequi',
            'Transferencia Bancolombia',
        ];

        foreach ($metodoPagos as $nombre) {
            MetodoPago::firstOrCreate([
                'nombre' => $nombre,
            ]);
        }
    }
}

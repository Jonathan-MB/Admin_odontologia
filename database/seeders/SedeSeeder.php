<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sede::firstOrCreate(['nombre' => 'Republica de Israel']);
        Sede::firstOrCreate(['nombre' => 'Peñadent']);
    }
}

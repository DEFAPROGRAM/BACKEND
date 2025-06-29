<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sedes;
use App\Models\Juzgados;

class SedeJuzgadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear sede temporal
        $sede = Sedes::create([
            'nom_sede' => 'Sede Principal',
            'direccion' => 'Dirección Temporal',
            'municipio' => 'Municipio Temporal',
        ]);

        // Crear juzgado temporal
        Juzgados::create([
            'nom_juzgado' => 'Juzgado Temporal',
            'id_sede' => $sede->id_sede,
        ]);

        $this->command->info('Sede y Juzgado temporales creados exitosamente!');
        $this->command->info('Sede ID: ' . $sede->id_sede);
        $this->command->info('Juzgado ID: 1');
    }
}

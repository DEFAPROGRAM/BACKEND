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
            'nom_sede' => 'Cuartel del fijo',
            'direccion' => 'Calle del Cuartel, cra 5 # 36-29',
            'municipio' => 'Cartagena',
        ]);

        // Crear juzgado temporal
        Juzgados::create([
            'nom_juzgado' => 'Area de Sistemas',
            'id_sede' => $sede->id_sede,
        ]);

        $this->command->info('Sede y Juzgado temporales creados exitosamente!');
        $this->command->info('Sede ID: ' . $sede->id_sede);
        $this->command->info('Juzgado ID: 1');
    }
}

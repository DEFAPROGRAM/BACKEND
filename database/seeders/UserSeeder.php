<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador - Diego Esteban Figueroa Ariza
        User::create([
            'nombres' => 'Diego Esteban',
            'apellidos' => 'Figueroa Ariza',
            'cargo' => 'Técnico Mesa Ayuda',
            'id_sede' => 1, // Se actualizará después
            'id_juzgado' => 1, // Se actualizará después
            'email' => 'defigueroa01@gmail.com',
            'password' => Hash::make('Defa12345'),
            'rol' => 'admin',
        ]);

        $this->command->info('Usuario administrador creado exitosamente!');
        $this->command->info('Email: defigueroa01@gmail.com');
        $this->command->info('Password: Defa12345');
        $this->command->info('Nota: Recuerda actualizar id_sede e id_juzgado después de crear las sedes y juzgados');
    }
}

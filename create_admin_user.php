<?php
// Script para crear usuario administrador
require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== CREANDO USUARIO ADMINISTRADOR ===\n";

// Verificar si ya existe el usuario
$existingUser = User::where('email', 'defigueroa01@gmail.com')->first();

if ($existingUser) {
    echo "✅ Usuario administrador ya existe:\n";
    echo "   Email: " . $existingUser->email . "\n";
    echo "   Rol: " . $existingUser->rol . "\n";
    echo "   Nombres: " . $existingUser->nombres . "\n";
    echo "   Apellidos: " . $existingUser->apellidos . "\n";
} else {
    echo "📝 Creando usuario administrador...\n";
    
    try {
        $user = User::create([
            'nombres' => 'Diego Esteban',
            'apellidos' => 'Figueroa Ariza',
            'cargo' => 'Técnico Mesa Ayuda',
            'id_sede' => 1,
            'id_juzgado' => 1,
            'email' => 'defigueroa01@gmail.com',
            'password' => Hash::make('Defa12345'),
            'rol' => 'admin',
        ]);
        
        echo "✅ Usuario administrador creado exitosamente!\n";
        echo "   Email: defigueroa01@gmail.com\n";
        echo "   Password: Defa12345\n";
        echo "   Rol: admin\n";
    } catch (Exception $e) {
        echo "❌ Error al crear usuario: " . $e->getMessage() . "\n";
    }
}

echo "\n=== CREDENCIALES DE ACCESO ===\n";
echo "Email: defigueroa01@gmail.com\n";
echo "Password: Defa12345\n";
echo "Rol: admin\n";

echo "\n=== FIN ===\n";
?> 
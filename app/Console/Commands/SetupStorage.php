<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SetupStorage extends Command
{
    protected $signature = 'setup:storage';
    protected $description = 'Configurar el enlace simbólico de storage y verificar imágenes';

    public function handle()
    {
        $this->info('🔧 Configurando enlace simbólico de storage...');

        // Verificar si existe el enlace simbólico
        if (File::exists(public_path('storage'))) {
            $this->warn('Enlace simbólico existente detectado. Eliminando...');
            File::deleteDirectory(public_path('storage'));
        }

        // Crear nuevo enlace simbólico
        $this->info('Creando nuevo enlace simbólico...');
        $this->call('storage:link');

        // Verificar que las imágenes existen
        $this->info('Verificando imágenes...');
        $imageFiles = [
            storage_path('app/public/slider/1.jpg'),
            storage_path('app/public/slider/2.png'),
            storage_path('app/public/slider/3.jpg')
        ];

        foreach ($imageFiles as $imageFile) {
            if (File::exists($imageFile)) {
                $this->info("✅ " . basename($imageFile) . " - Existe");
            } else {
                $this->error("❌ " . basename($imageFile) . " - No existe");
            }
        }

        $this->info('');
        $this->info('🎉 ¡Configuración completada!');
        $this->info('Las imágenes ahora deberían ser accesibles en:');
        $this->info('- http://127.0.0.1:8000/storage/slider/1.jpg');
        $this->info('- http://127.0.0.1:8000/storage/slider/2.png');
        $this->info('- http://127.0.0.1:8000/storage/slider/3.jpg');
    }
} 
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsSlider;

class InsertDemoNewsSlider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:insert-news-slider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert demo news slider data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Inserting demo news slider data...');

        NewsSlider::create([
            'title' => '¡Bienvenido al nuevo slider de noticias!',
            'content' => 'Este es el nuevo sistema de slider de noticias. Puedes agregar, editar y eliminar noticias desde el panel de administración.',
            'image_path' => 'news_sliders/1.jpg',
            'order' => 1,
            'active' => true,
        ]);

        NewsSlider::create([
            'title' => 'Funcionalidad 100% administrable',
            'content' => 'Gestiona el slider de noticias con imágenes, títulos y contenido desde la web.',
            'image_path' => 'news_sliders/2.png',
            'order' => 2,
            'active' => true,
        ]);

        NewsSlider::create([
            'title' => '¡Prueba la edición y el borrado!',
            'content' => 'Puedes editar o eliminar cualquier noticia del slider en cualquier momento.',
            'image_path' => 'news_sliders/3.png',
            'order' => 3,
            'active' => true,
        ]);

        $this->info('Demo news slider data inserted successfully!');
    }
} 
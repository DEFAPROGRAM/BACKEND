<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsSlider;

class NewsSliderSeeder extends Seeder
{
    public function run(): void
    {
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
    }
} 
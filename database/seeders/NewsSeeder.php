<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'Bienvenidos al sistema de gestión y administración de salas de audiencias JUSTROOM',
            'content' => 'Este es el sistema oficial para la gestión y administración de salas de audiencias. ¡Bienvenido!',
            'image_path' => '',
            'published_at' => now(),
            'active' => true
        ]);
        News::create([
            'title' => 'Noticia 1',
            'content' => 'Contenido de la noticia 1',
            'image_path' => 'news/noticia1.jpg',
            'published_at' => Carbon::now()->subDays(1),
            'active' => true
        ]);
        News::create([
            'title' => 'Noticia 2',
            'content' => 'Contenido de la noticia 2',
            'image_path' => 'news/noticia2.jpg',
            'published_at' => Carbon::now()->subDays(2),
            'active' => true
        ]);
        News::create([
            'title' => 'Noticia 3',
            'content' => 'Contenido de la noticia 3',
            'image_path' => 'news/noticia3.jpg',
            'published_at' => Carbon::now()->subDays(3),
            'active' => false
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SliderImage;

class SliderImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SliderImage::create([
            'title' => 'Imagen 1',
            'description' => 'Imagen institucional de bienvenida',
            'image_path' => 'http://127.0.0.1:8000/storage/slider/1.jpg',
            'caracteristicas' => 'Proyector de Video, Sistema de Audio, Aire Acondicionado',
            'order' => 1,
            'active' => true
        ]);
        
        SliderImage::create([
            'title' => 'Imagen 2',
            'description' => 'Imagen institucional secundaria',
            'image_path' => 'http://127.0.0.1:8000/storage/slider/2.png',
            'caracteristicas' => 'Proyector de Video, Sistema de Audio, Aire Acondicionado',
            'order' => 2,
            'active' => true
        ]);
        
        SliderImage::create([
            'title' => 'Imagen 3',
            'description' => 'Imagen institucional adicional',
            'image_path' => 'http://127.0.0.1:8000/storage/slider/3.jpg',
            'caracteristicas' => 'Proyector de Video, Sistema de Audio, Aire Acondicionado',
            'order' => 3,
            'active' => true
        ]);
    }
}

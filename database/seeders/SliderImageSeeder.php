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
            'image_path' => 'slider/1.jpg',
            'order' => 1,
            'active' => true
        ]);
        
        SliderImage::create([
            'title' => 'Imagen 2',
            'description' => 'Imagen institucional secundaria',
            'image_path' => 'slider/2.png',
            'order' => 2,
            'active' => true
        ]);
        
        SliderImage::create([
            'title' => 'Imagen 3',
            'description' => 'Imagen institucional adicional',
            'image_path' => 'slider/3.jpg',
            'order' => 3,
            'active' => true
        ]);
    }
}

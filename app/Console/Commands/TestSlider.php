<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SliderImage;

class TestSlider extends Command
{
    protected $signature = 'test:slider';
    protected $description = 'Test slider images from database';

    public function handle()
    {
        $this->info('Testing SliderImage model...');
        
        try {
            $images = SliderImage::where('active', true)->orderBy('order')->get();
            $this->info('Total images found: ' . $images->count());
            
            foreach ($images as $img) {
                $this->info("ID: {$img->id}, Title: {$img->title}, Path: {$img->image_path}, Active: " . ($img->active ? 'Yes' : 'No'));
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
} 
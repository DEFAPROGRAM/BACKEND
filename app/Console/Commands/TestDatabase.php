<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabase extends Command
{
    protected $signature = 'test:database';
    protected $description = 'Test database connection and slider_images table';

    public function handle()
    {
        $this->info('Testing database connection...');
        
        try {
            // Verificar conexión
            DB::connection()->getPdo();
            $this->info('Database connection: OK');
            
            // Verificar si la tabla existe
            $tables = DB::select('SHOW TABLES LIKE "slider_images"');
            if (empty($tables)) {
                $this->error('Table slider_images does not exist!');
                return 1;
            }
            $this->info('Table slider_images: EXISTS');
            
            // Verificar datos
            $count = DB::table('slider_images')->count();
            $this->info('Total records in slider_images: ' . $count);
            
            if ($count > 0) {
                $images = DB::table('slider_images')->get();
                foreach ($images as $img) {
                    $this->info("ID: {$img->id}, Title: {$img->title}, Path: {$img->image_path}, Active: " . ($img->active ? 'Yes' : 'No'));
                }
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
} 
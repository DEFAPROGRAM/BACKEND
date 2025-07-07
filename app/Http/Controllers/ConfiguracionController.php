<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Configuración: Iniciando guardado');
        \Log::info('Configuración: Datos recibidos', $request->all());
        
        $data = $request->validate([
            'sliderImages' => 'required|array',
            'newsList' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            \Log::info('Configuración: Procesando imágenes del slider', ['count' => count($data['sliderImages'])]);
            
            // Guardar imágenes del slider usando consulta directa
            foreach ($data['sliderImages'] as $img) {
                \Log::info('Configuración: Procesando imagen', $img);
                
                $sliderData = [
                    'title' => $img['title'] ?? '',
                    'description' => $img['description'] ?? '',
                    'image_path' => $img['url'] ?? $img['image_path'] ?? '',
                    'order' => $img['order'] ?? 0,
                    'active' => $img['active'] ?? true,
                ];
                
                \Log::info('Configuración: Datos de imagen a guardar', $sliderData);
                
                if (!empty($img['id'])) {
                    $updated = DB::table('slider_images')->where('id', $img['id'])->update($sliderData);
                    \Log::info('Configuración: Imagen actualizada', ['id' => $img['id'], 'updated' => $updated]);
                } else {
                    $created = DB::table('slider_images')->insertGetId($sliderData);
                    \Log::info('Configuración: Imagen creada', ['id' => $created]);
                }
            }

            \Log::info('Configuración: Procesando noticias', ['count' => count($data['newsList'])]);
            
            // Guardar noticias
            foreach ($data['newsList'] as $news) {
                \Log::info('Configuración: Procesando noticia', $news);
                
                $newsData = [
                    'title' => $news['title'] ?? '',
                    'content' => $news['content'] ?? '',
                    'image_path' => $news['image_path'] ?? '',
                    'published_at' => $news['publish_date'] ?? $news['published_at'] ?? now(),
                    'active' => $news['active'] ?? true,
                ];
                
                \Log::info('Configuración: Datos de noticia a guardar', $newsData);
                
                if (!empty($news['id'])) {
                    $updated = News::updateOrCreate(['id' => $news['id']], $newsData);
                    \Log::info('Configuración: Noticia actualizada', ['id' => $updated->id]);
                } else {
                    $created = News::create($newsData);
                    \Log::info('Configuración: Noticia creada', ['id' => $created->id]);
                }
            }

            DB::commit();
            \Log::info('Configuración: Guardado completado exitosamente');
            
            return response()->json([
                'success' => true,
                'message' => 'Configuración guardada correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Configuración: Error al guardar', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la configuración.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 
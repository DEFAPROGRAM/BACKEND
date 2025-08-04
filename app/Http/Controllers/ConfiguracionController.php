<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\SliderImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            
            // Procesar imágenes del slider (solo actualizar datos, no subir archivos)
            foreach ($data['sliderImages'] as $img) {
                \Log::info('Configuración: Procesando imagen', $img);
                
                $sliderData = [
                    'title' => $img['title'] ?? '',
                    'description' => $img['description'] ?? '',
                    'caracteristicas' => $img['caracteristicas'] ?? '',
                    'order' => $img['order'] ?? 0,
                    'active' => $img['active'] ?? true,
                ];
                
                // Solo actualizar si la imagen ya existe en la BD
                if (!empty($img['id']) && !str_starts_with($img['id'], 'temp_')) {
                    SliderImage::updateOrCreate(['id' => $img['id']], $sliderData);
                    \Log::info('Configuración: Imagen actualizada', ['id' => $img['id']]);
                } else {
                    \Log::info('Configuración: Imagen nueva ya fue subida, saltando procesamiento');
                }
            }

            \Log::info('Configuración: Procesando noticias', ['count' => count($data['newsList'])]);
            
            // Procesar noticias (solo actualizar datos, no crear nuevas)
            foreach ($data['newsList'] as $news) {
                \Log::info('Configuración: Procesando noticia', $news);
                
                $newsData = [
                    'title' => $news['title'] ?? '',
                    'content' => $news['content'] ?? '',
                    'published_at' => $news['published_at'] ?? now(),
                    'active' => $news['active'] ?? true,
                ];
                
                // Solo actualizar si la noticia ya existe en la BD
                if (!empty($news['id']) && !str_starts_with($news['id'], 'temp_')) {
                    // Verificar si hay cambios en la imagen
                    if (isset($news['image_path'])) {
                        // Si image_path es null, eliminar la imagen
                        if ($news['image_path'] === null) {
                            $existingNews = News::find($news['id']);
                            if ($existingNews && $existingNews->image_path && Storage::disk('public')->exists($existingNews->image_path)) {
                                Storage::disk('public')->delete($existingNews->image_path);
                            }
                            $newsData['image_path'] = null;
                        } else {
                            $newsData['image_path'] = $news['image_path'];
                        }
                        \Log::info('Configuración: Actualizando image_path de noticia', [
                            'id' => $news['id'],
                            'image_path' => $news['image_path']
                        ]);
                    }
                    
                    News::updateOrCreate(['id' => $news['id']], $newsData);
                    \Log::info('Configuración: Noticia actualizada', ['id' => $news['id']]);
                } else {
                    \Log::info('Configuración: Noticia nueva ya fue creada, saltando procesamiento');
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
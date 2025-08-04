<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SliderImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        try {
            $sliderImages = SliderImage::where('active', true)->orderBy('order')->get();
            
            // Normalizar URLs para que siempre sean absolutas
            foreach ($sliderImages as $img) {
                if ($img->image_path && !str_starts_with($img->image_path, 'http')) {
                    $img->image_path = 'http://127.0.0.1:8000/storage/' . ltrim($img->image_path, '/');
                }
            }
            
            return response()->json(['success' => true, 'data' => $sliderImages]);
        } catch (\Exception $e) {
            \Log::error('API Slider: Error - ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function adminIndex()
    {
        $sliderImages = SliderImage::orderBy('order')->get();
        
        // Normalizar URLs para que siempre sean absolutas
        foreach ($sliderImages as $img) {
            if ($img->image_path && !str_starts_with($img->image_path, 'http')) {
                $img->image_path = 'http://127.0.0.1:8000/storage/' . ltrim($img->image_path, '/');
            }
        }
        
        return response()->json(['success' => true, 'data' => $sliderImages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'caracteristicas' => 'nullable|string',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);
        
        $path = $request->file('image')->store('slider', 'public');
        
        // Convertir active a booleano si viene como string
        $active = $request->input('active');
        if (is_string($active)) {
            $active = in_array(strtolower($active), ['1', 'true', 'on', 'yes']);
        }
        
        $sliderImage = SliderImage::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'image_path' => $path, // Guardar solo la ruta relativa
            'caracteristicas' => $validated['caracteristicas'] ?? '',
            'order' => $validated['order'] ?? 0,
            'active' => $active ?? true,
        ]);
        
        // Normalizar URL para la respuesta
        if ($sliderImage->image_path && !str_starts_with($sliderImage->image_path, 'http')) {
            $sliderImage->image_path = 'http://127.0.0.1:8000/storage/' . ltrim($sliderImage->image_path, '/');
        }
        
        return response()->json(['success' => true, 'data' => $sliderImage]);
    }

    public function update(Request $request, $id)
    {
        $sliderImage = SliderImage::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'caracteristicas' => 'nullable|string',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);
        
        $updateData = $request->only(['title', 'description', 'caracteristicas', 'order']);
        
        // Manejar el campo active por separado
        if ($request->has('active')) {
            $active = $request->input('active');
            if (is_string($active)) {
                $active = in_array(strtolower($active), ['1', 'true', 'on', 'yes']);
            }
            $updateData['active'] = $active;
        }
        
        $sliderImage->update($updateData);
        
        // Normalizar URL para la respuesta
        if ($sliderImage->image_path && !str_starts_with($sliderImage->image_path, 'http')) {
            $sliderImage->image_path = 'http://127.0.0.1:8000/storage/' . ltrim($sliderImage->image_path, '/');
        }
        
        return response()->json(['success' => true, 'data' => $sliderImage]);
    }

    public function destroy($id)
    {
        $sliderImage = SliderImage::findOrFail($id);
        
        // Eliminar imagen física si existe
        if ($sliderImage->image_path) {
            $path = $sliderImage->image_path;
            // Si es una URL completa, extraer la ruta relativa
            if (str_starts_with($path, 'http')) {
                $path = str_replace('http://127.0.0.1:8000/storage/', '', $path);
            }
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $sliderImage->delete();
        return response()->json(['success' => true, 'message' => 'Imagen eliminada del slider']);
    }
}

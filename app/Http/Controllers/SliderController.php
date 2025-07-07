<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SliderImage;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    public function index()
    {
        \Log::info('API Slider: Iniciando consulta');
        try {
            // Usar consulta directa en lugar del modelo para probar
            $sliderImages = DB::table('slider_images')
                ->where('active', true)
                ->orderBy('order')
                ->get();
            
            \Log::info('API Slider: Imágenes encontradas: ' . $sliderImages->count());
            foreach ($sliderImages as $img) {
                \Log::info('API Slider: Imagen - ID: ' . $img->id . ', Title: ' . $img->title . ', Path: ' . $img->image_path . ', Active: ' . ($img->active ? 'true' : 'false'));
            }
            return response()->json($sliderImages);
        } catch (\Exception $e) {
            \Log::error('API Slider: Error - ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function adminIndex()
    {
        $sliderImages = SliderImage::orderBy('order')->get();
        return response()->json(['success' => true, 'data' => $sliderImages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image_path' => 'required|string',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);
        $sliderImage = SliderImage::create($validated);
        return response()->json(['success' => true, 'data' => $sliderImage]);
    }

    public function update(Request $request, $id)
    {
        $sliderImage = SliderImage::findOrFail($id);
        $sliderImage->update($request->only(['title', 'description', 'image_path', 'order', 'active']));
        return response()->json(['success' => true, 'data' => $sliderImage]);
    }

    public function destroy($id)
    {
        $sliderImage = SliderImage::findOrFail($id);
        $sliderImage->delete();
        return response()->json(['success' => true, 'message' => 'Imagen eliminada del slider']);
    }
}

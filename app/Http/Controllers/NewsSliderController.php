<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsSlider;
use Illuminate\Support\Facades\Storage;

class NewsSliderController extends Controller
{
    // Público: obtener noticias activas para el slider
    public function index()
    {
        $news = NewsSlider::where('active', true)->orderBy('order')->get();
        foreach ($news as $item) {
            if ($item->image_path && !str_starts_with($item->image_path, 'http')) {
                $item->image_path = url('storage/' . ltrim($item->image_path, '/'));
            }
        }
        return response()->json(['success' => true, 'data' => $news]);
    }

    // Admin: obtener todas las noticias del slider
    public function adminIndex()
    {
        $news = NewsSlider::orderBy('order')->get();
        foreach ($news as $item) {
            if ($item->image_path && !str_starts_with($item->image_path, 'http')) {
                $item->image_path = url('storage/' . ltrim($item->image_path, '/'));
            }
        }
        return response()->json(['success' => true, 'data' => $news]);
    }

    // Admin: crear noticia del slider
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);
        $newsData = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'order' => $validated['order'] ?? 0,
            'active' => $validated['active'] ?? true,
        ];
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news_sliders', 'public');
            $newsData['image_path'] = $path;
        }
        $news = NewsSlider::create($newsData);
        
        // Normalizar URL para la respuesta
        if ($news->image_path && !str_starts_with($news->image_path, 'http')) {
            $news->image_path = url('storage/' . $news->image_path);
        }
        return response()->json(['success' => true, 'data' => $news]);
    }

    // Admin: actualizar noticia del slider
    public function update(Request $request, $id)
    {
        $news = NewsSlider::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'image_path' => 'nullable',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);
        
        $updateData = $request->only(['title', 'content', 'order']);
        
        // Manejar el campo active por separado
        if ($request->has('active')) {
            $active = $request->input('active');
            if (is_string($active)) {
                $active = in_array(strtolower($active), ['1', 'true', 'on', 'yes']);
            }
            $updateData['active'] = $active;
        }
        
        if ($request->hasFile('image')) {
            if ($news->image_path && Storage::disk('public')->exists($news->image_path)) {
                Storage::disk('public')->delete($news->image_path);
            }
            $path = $request->file('image')->store('news_sliders', 'public');
            $updateData['image_path'] = $path;
        }
        
        if ($request->has('image_path') && $request->input('image_path') === null) {
            if ($news->image_path && Storage::disk('public')->exists($news->image_path)) {
                Storage::disk('public')->delete($news->image_path);
            }
            $updateData['image_path'] = null;
        }
        
        $news->update($updateData);
        
        // Normalizar URL para la respuesta
        if ($news->image_path && !str_starts_with($news->image_path, 'http')) {
            $news->image_path = url('storage/' . $news->image_path);
        }
        
        return response()->json(['success' => true, 'data' => $news]);
    }

    // Admin: eliminar noticia del slider
    public function destroy($id)
    {
        $news = NewsSlider::findOrFail($id);
        if ($news->image_path) {
            $path = $news->image_path;
            // Si es una URL completa, extraer la ruta relativa
            if (str_starts_with($path, 'http')) {
                $path = str_replace(url('storage/'), '', $path);
            }
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        $news->delete();
        return response()->json(['success' => true, 'message' => 'Noticia eliminada del slider']);
    }
} 
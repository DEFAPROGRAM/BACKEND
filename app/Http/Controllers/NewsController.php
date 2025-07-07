<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('active', true)->orderByDesc('published_at')->get();
        return response()->json($news);
    }

    public function adminIndex()
    {
        $news = News::orderByDesc('published_at')->get();
        return response()->json(['success' => true, 'data' => $news]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image_path' => 'nullable|string',
            'published_at' => 'nullable|date',
            'active' => 'nullable|boolean',
        ]);
        $news = News::create($validated);
        return response()->json(['success' => true, 'data' => $news]);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $news->update($request->only(['title', 'content', 'image_path', 'published_at', 'active']));
        return response()->json(['success' => true, 'data' => $news]);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return response()->json(['success' => true, 'message' => 'Noticia eliminada']);
    }
}

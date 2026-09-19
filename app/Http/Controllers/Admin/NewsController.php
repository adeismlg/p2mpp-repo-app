<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(15);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:4096',
            'published' => 'nullable|boolean',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('news', 'public');
        }

        News::create([
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'thumbnail' => $thumbnailPath,
            'published_at' => $request->boolean('published') ? now() : null,
        ]);

        return redirect()->route('admin.news.index')->with('status', 'Berita berhasil dibuat.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:4096',
            'published' => 'nullable|boolean',
        ]);

        $news->title = $data['title'];
        $news->excerpt = $data['excerpt'] ?? null;
        $news->content = $data['content'];
        $news->published_at = $request->boolean('published') ? ($news->published_at ?? now()) : null;

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $news->thumbnail = $request->file('thumbnail')->store('news', 'public');
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('status', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();

        return back()->with('status', 'Berita berhasil dihapus.');
    }
}

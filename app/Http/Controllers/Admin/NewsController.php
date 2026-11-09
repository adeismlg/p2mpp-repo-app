<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\SafeImageStorage;
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

    public function store(Request $request, SafeImageStorage $imageStorage)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'excerpt_en' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|dimensions:max_width=10000,max_height=10000|max:'.config('uploads.max_size_kilobytes'),
            'published' => 'nullable|boolean',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $imageStorage->store($request->file('thumbnail'), 'news', inputName: 'thumbnail');
        }

        News::create([
            'title' => $data['title'],
            'title_en' => $data['title_en'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'excerpt_en' => $data['excerpt_en'] ?? null,
            'content' => $data['content'],
            'content_en' => $data['content_en'] ?? null,
            'thumbnail' => $thumbnailPath,
            'published_at' => $request->boolean('published') ? now() : null,
        ]);

        return $this->respondAfterUpload($request, 'admin.news.index', 'Berita berhasil dibuat.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news, SafeImageStorage $imageStorage)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'excerpt_en' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|dimensions:max_width=10000,max_height=10000|max:'.config('uploads.max_size_kilobytes'),
            'published' => 'nullable|boolean',
        ]);

        $news->title = $data['title'];
        $news->title_en = $data['title_en'] ?? null;
        $news->excerpt = $data['excerpt'] ?? null;
        $news->excerpt_en = $data['excerpt_en'] ?? null;
        $news->content = $data['content'];
        $news->content_en = $data['content_en'] ?? null;
        $news->published_at = $request->boolean('published') ? ($news->published_at ?? now()) : null;

        $oldThumbnail = $news->thumbnail;
        $newThumbnail = $request->hasFile('thumbnail')
            ? $imageStorage->store($request->file('thumbnail'), 'news', inputName: 'thumbnail')
            : null;

        if ($newThumbnail) {
            $news->thumbnail = $newThumbnail;
        }

        $news->save();

        if ($newThumbnail && $oldThumbnail) {
            Storage::disk('public')->delete($oldThumbnail);
        }

        return $this->respondAfterUpload($request, 'admin.news.index', 'Berita berhasil diperbarui.');
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

<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        abort_unless($news->published_at, 404);

        return view('news.show', compact('news'));
    }
}

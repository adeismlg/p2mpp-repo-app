<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Document;
use App\Models\News;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect();

        $urls->push(['loc' => route('home'), 'lastmod' => now()->toAtomString()]);
        $urls->push(['loc' => route('news.index'), 'lastmod' => now()->toAtomString()]);
        $urls->push(['loc' => route('courses.index'), 'lastmod' => now()->toAtomString()]);
        $urls->push(['loc' => route('documents.index'), 'lastmod' => now()->toAtomString()]);

        News::whereNotNull('published_at')->get()->each(function (News $news) use ($urls) {
            $urls->push(['loc' => route('news.show', $news), 'lastmod' => $news->updated_at->toAtomString()]);
        });

        Course::all()->each(function (Course $course) use ($urls) {
            $urls->push(['loc' => route('courses.show', $course), 'lastmod' => $course->updated_at->toAtomString()]);
        });

        Document::public()->get()->each(function (Document $document) use ($urls) {
            $urls->push(['loc' => route('documents.show', $document), 'lastmod' => $document->updated_at->toAtomString()]);
        });

        $xml = view('sitemap', compact('urls'))->render();

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }
}

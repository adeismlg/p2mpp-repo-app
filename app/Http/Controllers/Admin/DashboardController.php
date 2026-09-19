<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\News;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'documents' => Document::count(),
            'categories' => DocumentCategory::count(),
            'news' => News::count(),
            'courses' => Course::count(),
            'downloads' => (int) Document::sum('downloads_count'),
        ];

        $topDocuments = Document::with('category')
            ->orderByDesc('downloads_count')
            ->take(5)
            ->get();

        $recentDocuments = Document::with('category')
            ->latest()
            ->take(6)
            ->get();

        // Distribusi unduhan per kategori, untuk grafik batang
        $categoryStats = DocumentCategory::withSum('documents as total_downloads', 'downloads_count')
            ->withCount('documents')
            ->orderByDesc('total_downloads')
            ->take(6)
            ->get()
            ->map(fn ($category) => [
                'name' => $category->name,
                'downloads' => (int) $category->total_downloads,
                'count' => $category->documents_count,
            ]);

        $publicCount = Document::where('is_public', true)->count();
        $hiddenCount = $stats['documents'] - $publicCount;

        return view('admin.dashboard', compact(
            'stats',
            'topDocuments',
            'recentDocuments',
            'categoryStats',
            'publicCount',
            'hiddenCount'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $categories = DocumentCategory::orderBy('name')->get();

        $documents = Document::public()
            ->with('category')
            ->search($request->query('q'))
            ->when($request->query('kategori'), function ($query, $slug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('documents.index', [
            'documents' => $documents,
            'categories' => $categories,
            'activeCategory' => $request->query('kategori'),
            'q' => $request->query('q'),
        ]);
    }

    public function show(Document $document)
    {
        abort_unless($document->is_public, 404);

        return view('documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        abort_unless($document->is_public, 404);
        abort_unless(Storage::disk('documents')->exists($document->file_path), 404);

        $document->increment('downloads_count');

        return Storage::disk('documents')->download($document->file_path, $document->file_name);
    }
}

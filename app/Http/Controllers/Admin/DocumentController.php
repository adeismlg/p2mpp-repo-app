<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = Document::with('category')
            ->search($request->query('q'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.documents.index', [
            'documents' => $documents,
            'q' => $request->query('q'),
        ]);
    }

    public function create()
    {
        $categories = DocumentCategory::orderBy('name')->get();

        return view('admin.documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'document_category_id' => 'nullable|exists:document_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'file' => 'required|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
        ]);

        $file = $request->file('file');
        $storedPath = $file->store('/', 'documents');

        Document::create([
            'document_category_id' => $data['document_category_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_public' => $request->boolean('is_public', true),
            'file_path' => $storedPath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()?->id,
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Dokumen berhasil diunggah.');
    }

    public function edit(Document $document)
    {
        $categories = DocumentCategory::orderBy('name')->get();

        return view('admin.documents.edit', compact('document', 'categories'));
    }

    public function update(Request $request, Document $document)
    {
        $data = $request->validate([
            'document_category_id' => 'nullable|exists:document_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'file' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
        ]);

        $document->title = $data['title'];
        $document->description = $data['description'] ?? null;
        $document->document_category_id = $data['document_category_id'] ?? null;
        $document->is_public = $request->boolean('is_public', true);

        if ($request->hasFile('file')) {
            Storage::disk('documents')->delete($document->file_path);

            $file = $request->file('file');
            $document->file_path = $file->store('/', 'documents');
            $document->file_name = $file->getClientOriginalName();
            $document->file_type = $file->getClientOriginalExtension();
            $document->file_size = $file->getSize();
        }

        $document->save();

        return redirect()->route('admin.documents.index')->with('status', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('documents')->delete($document->file_path);
        $document->delete();

        return back()->with('status', 'Dokumen berhasil dihapus.');
    }
}

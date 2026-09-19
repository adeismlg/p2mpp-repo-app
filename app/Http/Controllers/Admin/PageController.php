<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('title')->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published', true);

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('status', 'Halaman berhasil dibuat.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published', true);

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('status', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return back()->with('status', 'Halaman berhasil dihapus.');
    }
}

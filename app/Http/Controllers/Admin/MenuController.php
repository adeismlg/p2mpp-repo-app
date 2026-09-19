<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $items = MenuItem::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('admin.menu.index', compact('items'));
    }

    public function create()
    {
        $parents = MenuItem::whereNull('parent_id')->orderBy('order')->get();
        $pages = Page::orderBy('title')->get();
        $routes = MenuItem::availableRoutes();

        return view('admin.menu.create', compact('parents', 'pages', 'routes'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['order'] = MenuItem::where('parent_id', $data['parent_id'] ?? null)->max('order') + 1;

        MenuItem::create($data);

        return redirect()->route('admin.menu.index')->with('status', 'Menu berhasil ditambahkan.');
    }

    public function edit(MenuItem $menu)
    {
        $parents = MenuItem::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('order')->get();
        $pages = Page::orderBy('title')->get();
        $routes = MenuItem::availableRoutes();

        return view('admin.menu.edit', ['item' => $menu, 'parents' => $parents, 'pages' => $pages, 'routes' => $routes]);
    }

    public function update(Request $request, MenuItem $menu)
    {
        $data = $this->validated($request, $menu);

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('status', 'Menu berhasil diperbarui.');
    }

    public function destroy(MenuItem $menu)
    {
        $menu->delete();

        return back()->with('status', 'Menu berhasil dihapus.');
    }

    public function moveUp(MenuItem $menu)
    {
        $this->swapWithSibling($menu, 'previous');

        return back();
    }

    public function moveDown(MenuItem $menu)
    {
        $this->swapWithSibling($menu, 'next');

        return back();
    }

    private function swapWithSibling(MenuItem $menu, string $direction): void
    {
        $query = MenuItem::where('parent_id', $menu->parent_id);

        $sibling = $direction === 'previous'
            ? $query->where('order', '<', $menu->order)->orderByDesc('order')->first()
            : $query->where('order', '>', $menu->order)->orderBy('order')->first();

        if (! $sibling) {
            return;
        }

        [$menu->order, $sibling->order] = [$sibling->order, $menu->order];
        $menu->save();
        $sibling->save();
    }

    private function validated(Request $request, ?MenuItem $ignore = null): array
    {
        $data = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'label' => 'required|string|max:255',
            'label_en' => 'nullable|string|max:255',
            'type' => 'required|in:route,page,external',
            'route_name' => 'required_if:type,route|nullable|string',
            'page_id' => 'required_if:type,page|nullable|exists:pages,id',
            'url' => 'required_if:type,external|nullable|url',
            'open_in_new_tab' => 'nullable|boolean',
        ]);

        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');

        // Bersihkan field yang tidak relevan sesuai tipe supaya data konsisten
        if ($data['type'] !== 'route') {
            $data['route_name'] = null;
        }
        if ($data['type'] !== 'page') {
            $data['page_id'] = null;
        }
        if ($data['type'] !== 'external') {
            $data['url'] = null;
        }

        return $data;
    }
}

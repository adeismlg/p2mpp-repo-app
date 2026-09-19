<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::orderBy('order')->orderByDesc('created_at')->paginate(15);

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, true);
        $data['image'] = $request->file('image')->store('sliders', 'public');
        $data['is_active'] = $request->boolean('is_active');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('status', 'Slider berhasil dibuat.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image);
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('status', 'Slider berhasil diperbarui.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        Storage::disk('public')->delete($slider->image);
        $slider->delete();

        return back()->with('status', 'Slider berhasil dihapus.');
    }

    private function validated(Request $request, bool $imageRequired = false): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:1000'],
            'subtitle_en' => ['nullable', 'string', 'max:1000'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'link_url' => ['nullable', 'url', 'max:2048'],
            'button_label' => ['nullable', 'string', 'max:100'],
            'button_label_en' => ['nullable', 'string', 'max:100'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}

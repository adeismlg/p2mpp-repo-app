<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('name')->paginate(15);

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'facilities' => 'nullable|string',
            'facilities_en' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:4096',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create([
            'name' => $data['name'],
            'name_en' => $data['name_en'] ?? null,
            'description' => $data['description'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'facilities' => $this->parseFacilities($data['facilities'] ?? ''),
            'facilities_en' => $this->parseFacilities($data['facilities_en'] ?? ''),
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('admin.courses.index')->with('status', 'Pelatihan berhasil ditambahkan.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'facilities' => 'nullable|string',
            'facilities_en' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:4096',
        ]);

        $course->name = $data['name'];
        $course->name_en = $data['name_en'] ?? null;
        $course->description = $data['description'] ?? null;
        $course->description_en = $data['description_en'] ?? null;
        $course->facilities = $this->parseFacilities($data['facilities'] ?? '');
        $course->facilities_en = $this->parseFacilities($data['facilities_en'] ?? '');

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->thumbnail = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->save();

        return redirect()->route('admin.courses.index')->with('status', 'Pelatihan berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        $course->delete();

        return back()->with('status', 'Pelatihan berhasil dihapus.');
    }

    private function parseFacilities(string $raw): array
    {
        return collect(explode("\n", $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}

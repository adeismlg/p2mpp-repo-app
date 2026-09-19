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
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:4096',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'facilities' => $this->parseFacilities($data['facilities'] ?? ''),
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
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:4096',
        ]);

        $course->name = $data['name'];
        $course->description = $data['description'] ?? null;
        $course->facilities = $this->parseFacilities($data['facilities'] ?? '');

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

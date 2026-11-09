<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SafeImageStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorImageController extends Controller
{
    public function store(Request $request, SafeImageStorage $imageStorage): JsonResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'dimensions:max_width=10000,max_height=10000',
                'max:'.config('uploads.max_size_kilobytes'),
            ],
        ]);

        $path = $imageStorage->store($validated['file'], 'editor-images', inputName: 'file');

        return response()->json([
            'location' => Storage::disk('public')->url($path),
        ]);
    }
}

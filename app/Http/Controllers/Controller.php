<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Controller
{
    protected function downloadFilename(string $title, string $storedPath): string
    {
        $filename = Str::slug($title) ?: 'file';
        $extension = Str::lower(pathinfo($storedPath, PATHINFO_EXTENSION));

        if (preg_match('/\A[a-z0-9]{1,10}\z/', $extension) === 1) {
            $filename .= '.'.$extension;
        }

        return $filename;
    }

    protected function respondAfterUpload(Request $request, string $routeName, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            $request->session()->flash('status', $message);

            return response()->json([
                'redirect' => route($routeName),
                'message' => $message,
            ]);
        }

        return redirect()->route($routeName)->with('status', $message);
    }
}

<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class SafeImageStorage
{
    private const MAX_DIMENSION = 10000;

    private const MAX_PIXELS = 25000000;

    public function store(
        UploadedFile $upload,
        string $directory,
        string $diskName = 'public',
        string $inputName = 'image',
    ): string {
        $contents = $upload->get();
        $imageInfo = getimagesizefromstring($contents);

        if (
            $imageInfo === false
            || ! isset($imageInfo[0], $imageInfo[1], $imageInfo[2])
            || $imageInfo[0] > self::MAX_DIMENSION
            || $imageInfo[1] > self::MAX_DIMENSION
            || $imageInfo[0] * $imageInfo[1] > self::MAX_PIXELS
        ) {
            $this->reject($inputName, 'Gambar tidak dapat diproses atau dimensinya terlalu besar.');
        }

        $format = match ($imageInfo[2]) {
            IMAGETYPE_JPEG => ['jpg', 'jpeg'],
            IMAGETYPE_PNG => ['png', 'png'],
            IMAGETYPE_WEBP => ['webp', 'webp'],
            default => $this->reject($inputName, 'Format gambar tidak didukung.'),
        };

        $image = imagecreatefromstring($contents);

        if (! $image instanceof \GdImage) {
            $this->reject($inputName, 'Gambar gagal dibaca.');
        }

        if ($format[0] === 'webp' && ! function_exists('imagewebp')) {
            imagedestroy($image);

            throw new RuntimeException('Server tidak mendukung pemrosesan gambar WebP.');
        }

        ob_start();
        $encoded = match ($format[0]) {
            'jpg' => imagejpeg($image, null, 85),
            'png' => imagepng($image, null, 6),
            'webp' => imagewebp($image, null, 85),
        };
        $safeContents = ob_get_clean();
        imagedestroy($image);

        if (! $encoded || ! is_string($safeContents) || $safeContents === '') {
            throw new RuntimeException('Gambar gagal dinormalisasi.');
        }

        $path = $directory.'/'.Str::random(40).'.'.$format[0];

        if (! Storage::disk($diskName)->put($path, $safeContents)) {
            throw new RuntimeException('Gambar gagal disimpan.');
        }

        return $path;
    }

    private function reject(string $inputName, string $message): never
    {
        throw ValidationException::withMessages([
            $inputName => $message,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $extension = fake()->randomElement(['pdf', 'docx', 'xlsx', 'pptx']);
        $title = fake()->sentence(4);
        $fileName = Str::slug($title).'-'.fake()->unique()->numerify('####').'.'.$extension;

        return [
            'document_category_id' => DocumentCategory::inRandomOrder()->value('id')
                ?? DocumentCategory::factory(),
            'uploaded_by' => null,
            'title' => rtrim($title, '.'),
            'description' => fake()->sentence(15),
            'file_path' => $fileName,
            'file_name' => $fileName,
            'file_type' => $extension,
            'file_size' => fake()->numberBetween(80_000, 4_500_000),
            'downloads_count' => fake()->numberBetween(0, 800),
            'is_public' => fake()->boolean(90),
        ];
    }

    /**
     * Buat file dummy asli di disk 'documents' supaya tombol
     * unduh benar-benar berfungsi saat data demo dipakai.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Document $document) {
            if (! Storage::disk('documents')->exists($document->file_path)) {
                Storage::disk('documents')->put(
                    $document->file_path,
                    "Ini adalah dokumen contoh (dummy) untuk keperluan demo.\n\n".
                    "Judul: {$document->title}\n".
                    "Dibuat otomatis oleh DocumentFactory.\n"
                );
            }
        });
    }
}

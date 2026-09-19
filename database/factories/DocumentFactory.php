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
        $title = fake()->randomElement([
            'Panduan Pengajuan Dokumen',
            'Formulir Evaluasi Pembelajaran',
            'Laporan Audit Mutu Internal',
            'Pedoman Pelaksanaan Kegiatan',
            'Template Rencana Kerja',
            'Instrumen Survei Kepuasan',
        ]);
        $titleEn = [
            'Panduan Pengajuan Dokumen' => 'Document Submission Guide',
            'Formulir Evaluasi Pembelajaran' => 'Learning Evaluation Form',
            'Laporan Audit Mutu Internal' => 'Internal Quality Audit Report',
            'Pedoman Pelaksanaan Kegiatan' => 'Activity Implementation Guide',
            'Template Rencana Kerja' => 'Work Plan Template',
            'Instrumen Survei Kepuasan' => 'Satisfaction Survey Instrument',
        ][$title];
        $fileName = Str::slug($title).'-'.fake()->unique()->numerify('####').'.'.$extension;

        return [
            'document_category_id' => DocumentCategory::inRandomOrder()->value('id')
                ?? DocumentCategory::factory(),
            'uploaded_by' => null,
            'title' => rtrim($title, '.'),
            'title_en' => $titleEn,
            'description' => fake()->randomElement([
                'Dokumen resmi untuk mendukung pelaksanaan kegiatan dan layanan P2MPP.',
                'Panduan dan formulir yang dapat digunakan oleh civitas akademika.',
                'Dokumen pendukung sistem penjaminan mutu internal.',
            ]),
            'description_en' => fake()->randomElement([
                'An official document supporting P2MPP activities and services.',
                'A guide and form for use by the academic community.',
                'A supporting document for the internal quality assurance system.',
            ]),
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
                    "English title: {$document->title_en}\n".
                    "Dibuat otomatis oleh DocumentFactory.\n"
                );
            }
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentCategoryFactory extends Factory
{
    protected $model = DocumentCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Formulir Akademik',
            'Panduan Mahasiswa',
            'Sertifikasi Bahasa',
            'Silabus & RPS',
            'Surat Keterangan',
            'Materi Kursus',
            'Pengumuman Resmi',
            'Template Dokumen',
        ]);

        return [
            'name' => $name,
            'description' => fake()->sentence(10),
        ];
    }
}

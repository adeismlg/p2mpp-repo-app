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
            'Standar Mutu Internal',
            'Borang Akreditasi',
            'Formulir Monev',
            'Panduan Audit Mutu Internal',
            'RPS & Silabus',
            'Pedoman SPMI',
            'Laporan Kepuasan Pelanggan',
            'Dokumen Kebijakan Mutu',
        ]);

        return [
            'name' => $name,
            'description' => fake()->sentence(10),
        ];
    }
}

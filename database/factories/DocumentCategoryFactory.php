<?php

namespace Database\Factories;

use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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

        $englishNames = [
            'Standar Mutu Internal' => 'Internal Quality Standards',
            'Borang Akreditasi' => 'Accreditation Forms',
            'Formulir Monev' => 'Monitoring and Evaluation Forms',
            'Panduan Audit Mutu Internal' => 'Internal Quality Audit Guide',
            'RPS & Silabus' => 'Lesson Plans and Syllabi',
            'Pedoman SPMI' => 'Internal Quality Assurance Guidelines',
            'Laporan Kepuasan Pelanggan' => 'Customer Satisfaction Reports',
            'Dokumen Kebijakan Mutu' => 'Quality Policy Documents',
        ];

        return [
            'name' => $name,
            'name_en' => $englishNames[$name],
            'slug' => Str::slug($name),
            'description' => fake()->sentence(10),
            'description_en' => fake()->sentence(10),
        ];
    }
}

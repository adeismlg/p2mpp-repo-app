<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Bahasa Inggris',
            'Bahasa Jepang',
            'Bahasa Mandarin',
            'Bahasa Prancis',
            'Bahasa Indonesia untuk Penutur Asing (BIPA)',
            'Bahasa Korea',
        ]);

        return [
            'name' => $name,
            'description' => fake()->paragraph(3),
            'facilities' => fake()->randomElements([
                'Modul belajar digital',
                'Simulasi ujian',
                'Sertifikat kelulusan',
                'Kelas tatap muka',
                'Konsultasi pengajar',
                'Akses perpustakaan bahasa',
            ], fake()->numberBetween(2, 4)),
        ];
    }
}

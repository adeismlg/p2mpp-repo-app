<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Pekerti',
            'Applied Approach (AA)',
            'Perangkat dan Media Pembelajaran bagi Dosen',
            'Penyusunan Kurikulum',
            'Pelatihan Auditor Mutu Internal',
            'Metodologi Pembelajaran Praktik Vokasi',
        ]);

        $englishNames = [
            'Pekerti' => 'Pekerti',
            'Applied Approach (AA)' => 'Applied Approach (AA)',
            'Perangkat dan Media Pembelajaran bagi Dosen' => 'Instructional Tools and Media for Lecturers',
            'Penyusunan Kurikulum' => 'Curriculum Development',
            'Pelatihan Auditor Mutu Internal' => 'Internal Quality Auditor Training',
            'Metodologi Pembelajaran Praktik Vokasi' => 'Vocational Practical Learning Methodology',
        ];

        return [
            'name' => $name,
            'name_en' => $englishNames[$name],
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(3),
            'description_en' => fake()->paragraph(3),
            'facilities' => fake()->randomElements([
                'Materi & modul pelatihan',
                'Sertifikat pelatihan',
                'Narasumber berpengalaman',
                'Sesi praktik/simulasi',
                'Pendampingan pasca-pelatihan',
            ], fake()->numberBetween(2, 4)),
            'facilities_en' => [
                'Training materials and modules',
                'Training certificate',
                'Experienced speakers',
                'Practice/simulation sessions',
                'Post-training assistance',
            ],
        ];
    }
}

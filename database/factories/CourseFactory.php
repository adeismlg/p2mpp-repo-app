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
            'Pekerti',
            'Applied Approach (AA)',
            'Perangkat dan Media Pembelajaran bagi Dosen',
            'Penyusunan Kurikulum',
            'Pelatihan Auditor Mutu Internal',
            'Metodologi Pembelajaran Praktik Vokasi',
        ]);

        return [
            'name' => $name,
            'description' => fake()->paragraph(3),
            'facilities' => fake()->randomElements([
                'Materi & modul pelatihan',
                'Sertifikat pelatihan',
                'Narasumber berpengalaman',
                'Sesi praktik/simulasi',
                'Pendampingan pasca-pelatihan',
            ], fake()->numberBetween(2, 4)),
        ];
    }
}

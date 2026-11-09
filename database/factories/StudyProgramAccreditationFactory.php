<?php

namespace Database\Factories;

use App\Models\StudyProgramAccreditation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyProgramAccreditation>
 */
class StudyProgramAccreditationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'study_level' => fake()->randomElement(['D-II', 'D-III', 'D-IV', 'S2']),
            'decree_number' => fake()->bothify('###/SK/BAN-PT/???/####'),
            'name' => fake()->words(3, true),
            'name_en' => fake()->words(3, true),
            'rating' => fake()->randomElement(['Baik', 'Baik Sekali', 'Unggul']),
            'valid_until' => fake()->dateTimeBetween('today', '+5 years')->format('Y-m-d'),
        ];
    }
}

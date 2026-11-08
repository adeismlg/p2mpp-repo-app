<?php

namespace Database\Factories;

use App\Models\AccreditationEvidence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccreditationEvidence>
 */
class AccreditationEvidenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'instrument' => 'Instrumen akreditasi',
            'scope' => 'institution',
            'assessment_entity' => null,
            'criterion_code' => fake()->unique()->numerify('K##'),
            'criterion_name' => fake()->sentence(),
            'evidence_title' => fake()->sentence(4),
            'evidence_path' => null,
            'owner_id' => null,
            'status' => 'draft',
            'due_date' => null,
            'notes' => null,
        ];
    }
}

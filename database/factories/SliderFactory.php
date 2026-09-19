<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slider>
 */
class SliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'title_en' => fake()->sentence(3),
            'subtitle' => fake()->sentence(8),
            'subtitle_en' => fake()->sentence(8),
            'image' => 'sliders/'.fake()->uuid().'.jpg',
            'link_url' => 'https://example.com',
            'button_label' => 'Selengkapnya',
            'button_label_en' => 'Read more',
            'order' => 0,
            'is_active' => true,
        ];
    }
}

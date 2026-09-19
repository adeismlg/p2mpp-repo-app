<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'excerpt' => fake()->sentence(15),
            'content' => implode("\n\n", fake()->paragraphs(5)),
            'published_at' => fake()->boolean(85)
                ? fake()->dateTimeBetween('-6 months', 'now')
                : null,
        ];
    }
}

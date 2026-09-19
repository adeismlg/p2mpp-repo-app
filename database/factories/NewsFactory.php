<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'title_en' => fake()->sentence(6),
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('#####'),
            'excerpt' => fake()->sentence(15),
            'excerpt_en' => fake()->sentence(15),
            'content' => implode("\n\n", fake()->paragraphs(5)),
            'content_en' => implode("\n\n", fake()->paragraphs(5)),
            'published_at' => fake()->boolean(85)
                ? fake()->dateTimeBetween('-6 months', 'now')
                : null,
        ];
    }
}

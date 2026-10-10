<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'subtitle' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'image_path' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=1000&q=80',
            'link_url' => '/shop',
            'button_text' => 'Lihat Koleksi',
            'secondary_button_text' => 'Rangkai Sendiri',
            'secondary_button_url' => '/custom-bouquet',
            'badge_text' => fake()->sentence(),
            'badge_author' => fake()->name(),
            'sort_order' => 1,
            'status' => 'active',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Term>
 */
class TermFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'name' => fake()->unique()->word(),
            'icon' => '🌱',
            'summary' => fake()->sentence(),
            'explanation' => fake()->paragraph(),
            'analogy' => fake()->sentence(),
            'points' => [fake()->sentence(), fake()->sentence()],
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }
}

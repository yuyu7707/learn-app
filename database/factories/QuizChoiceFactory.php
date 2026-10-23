<?php

namespace Database\Factories;

use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizChoice>
 */
class QuizChoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_question_id' => QuizQuestion::factory(),
            'body' => fake()->sentence(3),
            'feedback' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 3),
        ];
    }
}

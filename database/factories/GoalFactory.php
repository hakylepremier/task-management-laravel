<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Testing\Fakes\Fake;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->realText(15),
            'description' => fake()->realText(200),
            'image' => null,
            'category_id' => null,
            'stage_id' => Stage::firstOrCreate(['title' => 'Processing'])->id,
            'end_date' => fake()->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}

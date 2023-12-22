<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->realText(30),
            'description' => fake()->realTextBetween(100, 200),
            'state_id' => State::firstOrCreate([
                'title' => 'To do'
            ])
        ];
    }
}

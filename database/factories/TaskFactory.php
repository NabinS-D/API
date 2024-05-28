<?php

namespace Database\Factories;

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
            'task_id' => $this->faker->unique()->numberBetween(1, 200),
            "task"=> $this->faker->name,
            "description"=> $this->faker->text,
            'priority' => $this->faker->randomElement(['Yes','No']),
            'created_at'=> $this->faker->dateTimeThisYear(),
            'updated_at'=> $this->faker->dateTimeThisYear(),
        ];
    }
}

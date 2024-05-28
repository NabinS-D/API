<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => $this->faker->unique()->numberBetween(1, 200),
            "project_name"=> $this->faker->name,
            "description"=> $this->faker->text,
            'created_at'=> $this->faker->dateTimeThisYear(),
            'updated_at'=> $this->faker->dateTimeThisYear(),
        
        ];
    }
}

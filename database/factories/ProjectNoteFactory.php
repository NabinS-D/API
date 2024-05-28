<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectNote>
 */
class ProjectNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
        'description'=> $this->faker->text,
        'created_at'=> $this->faker->dateTimeThisYear(),
        'updated_at'=> $this->faker->dateTimeThisYear(),
    ];
    }
}

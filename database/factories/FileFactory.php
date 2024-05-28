<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
           "file_name"=> $this->faker->name .'jpg',
           'file_loc'=> $this->faker->text,
           'created_at' => $this->faker->dateTimeBetween(),
           'updated_at'=> $this->faker->dateTimeBetween(),
        ];
    }
}

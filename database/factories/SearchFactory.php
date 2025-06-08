<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SearchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => $this->faker->name(),
            'email'      => $this->faker->unique()->safeEmail(),
            'questions'  => $this->faker->numberBetween(1, 50),
            'type'       => $this->faker->randomElement([null, 'multiple', 'boolean']),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
        ];
    }
}

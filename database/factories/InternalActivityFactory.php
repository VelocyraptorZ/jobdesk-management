<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternalActivity>
 */
class InternalActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Michael Day 2025 Commitee',
                'ATMIFEST 2025 Commitee',
                'Manrev 2025 Commitee',
                'Insternship Program Commitee',
            ]),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}

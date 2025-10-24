<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Training;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    protected $model = Training::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'CNC Machine Operation',
                'Industrial Safety Certification',
                '3D Printing Workshop',
                'PLC Programming',
                'Hydraulic Systems Training'
            ]),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}

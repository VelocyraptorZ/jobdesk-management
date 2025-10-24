<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Production;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Production>
 */
class ProductionFactory extends Factory
{
    protected $model = Production::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Prototype Fabrication',
                'Student Project Manufacturing',
                'Tool Maintenance',
                'Lab Equipment Assembly'
            ]),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['practical', 'theoretical']);
        $baseNames = $type === 'practical'
            ? ['Workshop Practice', 'CAD Lab', 'Machining Lab', 'Welding Practice']
            : ['Engineering Mechanics', 'Heat Transfer', 'Dynamics', 'Control Systems'];

        return [
            'name' => $this->faker->randomElement($baseNames) . ' ' . $this->faker->numerify('###'),
            'type' => $type,
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}

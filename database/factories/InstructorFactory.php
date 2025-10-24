<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Instructor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    protected $model = Instructor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'name' => $this->faker->name(),
            'employee_id' => 'DOS-' . $this->faker->unique()->numberBetween(1000, 9999),
            'field_of_expertise' => $this->faker->randomElement([
                'Thermodynamics',
                'Fluid Mechanics',
                'Machine Design',
                'Manufacturing Processes',
                'Automotive Systems',
                'Robotics & Automation',
                'Materials Science',
                'HVAC Systems'
            ]),
            'is_active' => $this->faker->boolean(90), // 90% active
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Instructor;
use App\Models\Jobdesk;
use App\Models\Course;
use App\Models\Production;
use App\Models\Training;
use App\Models\InternalActivity;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jobdesk>
 */
class JobdeskFactory extends Factory
{
    protected $model = Jobdesk::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['practical', 'theoretical', 'production', 'training', 'internal']);
        $descriptions = [
            'practical' => 'Conducted lab session on mechanical assembly.',
            'theoretical' => 'Delivered lecture on thermodynamic cycles.',
            'production' => 'Supervised student prototype fabrication.',
            'training' => 'Facilitated CNC safety and operation training.',
            'internal' => 'Organized internal workshop on team collaboration.'
        ];

        if (Instructor::count() === 0) {
            Instructor::factory()->create();
        }

        return [
            'instructor_id' => Instructor::inRandomOrder()->first()->id,
            'activity_date' => now()->subDays(rand(0, 30)),
            'start_time' => '08:00:00',
            'activity_type' => $type,
            'description' => $this->faker->sentence,
            'course_id' => in_array($type, ['practical', 'theoretical']) ? Course::factory() : null,
            'production_id' => $type === 'production' ? Production::factory() : null,
            'training_id' => $type === 'training' ? Training::factory() : null,
            'internal_activity_id' => $type === 'internal' ? \App\Models\InternalActivity::factory() : null,
        ];
    }
}

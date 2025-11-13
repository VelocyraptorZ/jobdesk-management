<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Production;
use App\Models\Training;
use App\Models\InternalActivity;
use App\Models\Jobdesk;


class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        User::factory()->create(['email' => 'super@example.com', 'role' => 'superadmin']);
        User::factory()->create(['email' => 'admin@example.com', 'role' => 'admin']);
        User::factory()->create(['email' => 'director@example.com', 'role' => 'user']);

        // Master data
        Instructor::factory(8)->create();
        Course::factory(8)->create();
        Production::factory(4)->create();
        Training::factory(5)->create();
        InternalActivity::factory(4)->create();

        // Jobdesks (will auto-create instructors if needed, but we already have them)
        // To link to existing instructors only:
        Jobdesk::factory(10)->create();
    }
}

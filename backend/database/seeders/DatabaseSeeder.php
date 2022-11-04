<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Design;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
    $user = User::factory(5)
    ->has(Project::factory()->count(3))
    ->create();

    $user = User::factory(5)
    ->has(Design::factory()->count(3))
    ->create();

    $project = project::factory(5)
    ->has(Design::factory()->count(3))
    ->create();



        }
}

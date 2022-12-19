<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\Design;
class ProjectDesignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        //
        'project_id'=>Project::factory(),
        'design_id'=>Design::factory(),
        'class'=>rand(1,9)
        ];
    }
}

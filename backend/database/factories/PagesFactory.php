<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\User;
use App\Models\Design;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id'=>Project::Factory(),
            'number'=>rand(0,50),
            'user_id'=>User::Factory(),
            'design_id'=>Design::Factory(),
            'title'=>Str::random(10),
            'contents'=>Str::random(20)

        ];
    }
}

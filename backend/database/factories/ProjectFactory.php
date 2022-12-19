<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => Str::random(10),
            'ui' => [
                'name' => Str::random(10),
                'kana' => Str::random(10),
                'address' => Str::random(10),
                'email' => Str::random(10)
            ]
        ];
    }
}

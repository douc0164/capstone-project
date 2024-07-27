<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskList>
 */
class TaskListFactory extends Factory
{

    public function definition(): array
    {
        return [
            'list_name' => $this->faker->words(3, true),
            'user_id' => \App\Models\User::factory(), // Reference the User factory
        ];
    }
}

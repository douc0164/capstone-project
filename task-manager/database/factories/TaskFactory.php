<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{

    public function definition(): array
    {
        return [
            'task_name' => $this->faker->sentence(3),
            'due_date' => $this->faker->optional()->date,
            'is_completed' => $this->faker->boolean,
            'priority_id' => $this->faker->optional()->randomDigitNotNull,
            'list_id' => \App\Models\TaskList::factory(), // Reference the TaskList factory
        ];
    }
}

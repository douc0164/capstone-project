<?php

namespace Database\Factories;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskList>
 */
class TaskListFactory extends Factory
{
    protected $model = TaskList::class;

    public function definition()
    {
        return [
            'list_name' => $this->faker->word,
            'user_id' => User::factory(), // Assuming you have a User model factory
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

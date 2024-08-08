<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $priorities = ['URGENT', 'SOON', 'LOW'];

        return [
            'task_name' => $this->faker->sentence,
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'is_completed' => $this->faker->boolean,
            'priority_id' => Arr::random($priorities), // Select random priority
            'list_id' => TaskList::factory(), // Assuming you have a TaskList model factory
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

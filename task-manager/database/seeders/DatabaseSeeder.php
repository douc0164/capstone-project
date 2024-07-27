<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TaskList;
use App\Models\Task;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);

        User::factory(9)->create();

        // Define and insert priorities
        $priorities = ['URGENT', 'SOON', 'LOW'];

        foreach ($priorities as $priorityName) {
            DB::table('priorities')->updateOrInsert(
                ['priority' => $priorityName],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $priorityIds = DB::table('priorities')->pluck('id')->toArray();

        $taskLists = [
            'Before Vacation' => [
                'Pack suitcase',
                'Book travel insurance',
                'Buy travel adapters',
                'Check passport validity',
                'Prepare itinerary'
            ],
            'For Work Meeting' => [
                'Prepare presentation',
                'Review meeting agenda',
                'Send reminder email'
            ],
            'Shopping' => [
                'Buy groceries'
            ],
            'Birthday Party' => [
                'Order cake',
                'Send invitations',
                'Decorate venue',
                'Prepare party favors'
            ],
            'School Work' => [
                'Complete math homework',
                'Read history chapter'
            ]
        ];

        $taskListIds = [];

        foreach ($taskLists as $listName => $tasks) {
            $taskList = TaskList::factory()->create([
                'list_name' => $listName,
                'user_id' => User::first()->id
            ]);

            $taskListIds[] = $taskList->id;

            foreach ($tasks as $index => $taskName) {
                // Generate a due date for each task
                $dueDate = now()->addDays(rand(1, 30)); // Due date within the next 30 days

                Task::factory()->create([
                    'task_name' => $taskName,
                    'list_id' => $taskList->id,
                    'priority_id' => Arr::random($priorityIds),
                    'due_date' => $dueDate
                ]);
            }
        }
    }
}

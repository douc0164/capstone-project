<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TaskList $list)
    {
        $tasks = $list->tasks;
        return response()->json($tasks, 200);
    }

    public function show(TaskList $list, $id)
    {
        $task = $list->tasks()->findOrFail($id);
        return response()->json($task, 200);
    }

    public function store(TaskList $list, Request $request)
    {
        $task = new Task();
        $task->task_name = $request->input('task_name');
        $task->due_date = $request->input('due_date');
        $task->priority_id = $request->input('priority_id');
        $task->list_id = $list->id;
        $task->save();

        return response()->json($task, 201);
    }

    public function update(TaskList $list, Request $request, $id)
    {
        $task = $list->tasks()->findOrFail($id);
        $task->task_name = $request->input('task_name');
        $task->due_date = $request->input('due_date');
        $task->priority_id = $request->input('priority_id');
        $task->save();

        return response()->json($task, 200);
    }

    public function destroy(TaskList $list, $id)
    {
        $task = $list->tasks()->findOrFail($id);
        $task->delete();

        return response()->json($task, 200);
    }

    public function tasksByList(TaskList $list)
    {
        $tasks = $list->tasks;
        return response()->json($tasks, 200);
    }
}

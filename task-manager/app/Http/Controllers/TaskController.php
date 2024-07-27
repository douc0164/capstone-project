<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Models\User;

class TaskController extends Controller
{
    public function index(User $user, TaskList $list){
        $tasks = $list->tasks;
        return response()->json($tasks, 200);
    }

    public function show(User $user, TaskList $list, $id){
        $task = $list->tasks()->findOrFail($id);
        return response()->json($task, 200);
    }

    // create task
    public function store(User $user, TaskList $list, Request $request){
        $request->validate([
            'task_name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority_id' => 'nullable|exists:priorities,id',
        ]);

        $task = new Task();
        $task->task_name = $request->input('task_name');
        $task->due_date = $request->input('due_date');
        $task->priority_id = $request->input('priority_id');
        $task->list_id = $list->id;
        $task->save();

        return response()->json($task, 201);
    }

    // update task
    public function update(User $user, TaskList $list, Request $request, $id){
        $request->validate([
            'task_name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority_id' => 'nullable|exists:priorities,id',
        ]);

        $task = $list->tasks()->findOrFail($id);
        $task->task_name = $request->input('task_name');
        $task->due_date = $request->input('due_date');
        $task->priority_id = $request->input('priority_id');
        $task->save();

        return response()->json($task, 200);
    }

    // delete task
    public function destroy(User $user, TaskList $list, $id){
        $task = $list->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}

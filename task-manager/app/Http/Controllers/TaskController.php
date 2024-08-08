<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'due_date' => 'required',
            'priority_id' => 'nullable|exists:priorities,id',
            'is_completed' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        info($request->all());

        $task = Task::create([
            'list_id' => intval($request->list_id),
            'task_name' => $request->task_name,
            'due_date' => $request->due_date,
            'priority_id' => $request->priority_id ?? intval($request->priority_id),
            'is_completed' => $request->is_completed ?? false,
        ]);

        return response()->json(['task' => $task], 201);
    }

    public function update(Request $request, $list, $task)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'due_date' => 'required',
            'priority_id' => 'nullable|exists:priorities,id',
            'is_completed' => 'boolean',
        ]);

        info($request->all());

        if ($validator->fails()) {
            info($validator->errors());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::where('id', $task)->where('list_id', $list)->firstOrFail();
        $task->update($request->all());

        return response()->json(['task' => $task], 200);
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

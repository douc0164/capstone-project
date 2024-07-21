<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;

// INDIVIDUAL TASK
class TaskController extends Controller
{
    public function index(){
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    public function show ($id){
        $task = Task::find($id);
        return response()->json($task, 200);
    }

    public function tasksByList(TaskList $list)
    {
        $tasks = $list->tasks;
        return response()->json($tasks, 200);
    }
}

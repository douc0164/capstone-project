<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Auth;

// THIS IS FOR THE LIST
class TaskListController extends Controller
{
    public function index(User $user){
        return response()->json($user->lists, 200);
    }

    public function show (TaskList $list){
        if (Auth::id() !== $list->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($list, 200);
    }

    // create list
    public function store(){
        $list = new TaskList();
        $list->list_name = request('list_name');
        $list->user_id = Auth::id();
        $list->save(); //save to database
    
        return response()->json($list, 201);
    }
    
    // update
    public function update(Request $request, TaskList $list){
        if (Auth::id() !== $list->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $list->list_name = request('list_name');
        $list->save(); //save to database
    
        return response()->json($list, 200);
    }
    
    // delete list
    public function destroy(TaskList $list){
        if (Auth::id() !== $list->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $list->delete();

        return response()->json($list, 200);
    }
}

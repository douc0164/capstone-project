<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request){
        $request->validate([
            'list_name' => 'required|string|max:50',
        ]);

        $list = new TaskList();
        $list->list_name = $request->input('list_name');
        $list->user_id = Auth::id();
        $list->save();

        return response()->json($list, 201);
    }
    
    // update
    public function update(Request $request, User $user, TaskList $list){
        if (Auth::id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $list->list_name = $request->input('list_name');
        $list->save();
    
        return response()->json($list, 200);
    }
    
    // delete list
    public function destroy(User $user, TaskList $list){
        if (Auth::id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($list->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $list->delete();
        
        return response()->json(['message' => 'Task list deleted successfully'], 200);
    }
}

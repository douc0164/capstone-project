<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

// THIS IS FOR THE LIST
class TaskListController extends Controller
{
    public function index(Request $request)
    {
        $lists = TaskList::where('user_id', $request->user_id)->get();
        return response()->json($lists, 200);
    }

    public function show($id)
    {
        $list = TaskList::find($id);
        return response()->json($list, 200);
    }

    // create list
    public function store(Request $request)
    {

        $validated = $request->validate([
            'list_name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $taskList = TaskList::create([
            'list_name' => $validated['list_name'],
            'user_id' => $validated['user_id'],
        ]);

        return response()->json($taskList, 201);
    }

    // update
    public function update($id)
    {
        $list = TaskList::find($id);
        $list->list_name = request('list_name');
        $list->save(); //save to database

        return response()->json($list, 200);
    }

    // delete list
    public function destroy($id)
    {
        $list = TaskList::find($id);
        $list->delete();

        return response()->json($list, 200);
    }

    // sort lists
    public function sortLists($sortOption, $userId)
    {
        switch ($sortOption) {
            case 'name-desc':
                $sortedLists = TaskList::where('user_id', $userId)->orderBy('list_name', 'desc')->get();
                break;
            case 'name-asc':
                $sortedLists = TaskList::where('user_id', $userId)->orderBy('list_name', 'asc')->get();
                break;
            case 'oldest':
                $sortedLists = TaskList::where('user_id', $userId)->orderBy('created_at', 'asc')->get();
                break;
            case 'newest':
                $sortedLists = TaskList::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
                break;
            default:
                return response()->json(['error' => 'Invalid sort option'], 400);
        }
        return response()->json($sortedLists);
    }
}

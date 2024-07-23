<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

// THIS IS FOR THE LIST
class TaskListController extends Controller
{
    public function index(){
        $lists = TaskList::all();
        return response()->json($lists, 200);
    }

    public function show ($id){
        $list= TaskList::find($id);
        return response()->json($list, 200);
    }

    // create list
    public function store(){
        $list = new TaskList();
        $list->list_name = request('list_name');
        $list->save(); //save to database
    
        return response()->json($list, 201);
    }
    
    // update
    public function update($id){
        $list = TaskList::find($id);
        $list->list_name = request('list_name');
        $list->save(); //save to database
    
        return response()->json($list, 200);
    }
    
    // delete list
    public function destroy($id){
        $list = TaskList::find($id);
        $list->delete();

        return response()->json($list, 200);
    }
}

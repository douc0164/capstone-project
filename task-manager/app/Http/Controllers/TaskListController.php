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
}

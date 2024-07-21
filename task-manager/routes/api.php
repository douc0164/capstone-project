<?php

use App\Http\Controllers\TaskListCOntroller;
use App\Http\Controllers\TaskCOntroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/lists', [TaskListController::class, 'index']); // shows all lists available
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::get('/lists/{list}/tasks', [TaskController::class, 'tasksByList']); // get all tasks for specific list
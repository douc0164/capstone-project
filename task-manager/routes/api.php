<?php

use App\Http\Controllers\TaskListCOntroller;
use App\Http\Controllers\TaskCOntroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/lists', [TaskListController::class, 'index']); // shows all lists available
Route::post('/lists', [TaskListController::class, 'store']);
Route::get('/lists/{id}', [TaskListController::class, 'show']); // getting single list
Route::put('/lists/{id}', [TaskListController::class, 'update']);
Route::delete('/lists/{id}', [TaskListController::class, 'destroy']);

// Route::get('/tasks', [TaskController::class, 'index']);
// Route::post('/tasks', [TaskController::class, 'store']);
// Route::get('/tasks/{id}', [TaskController::class, 'show']);
// Route::put('/tasks/{id}', [TaskController::class, 'update']);
// Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

Route::get('lists/{list}/tasks', [TaskController::class, 'index']);
Route::get('lists/{list}/tasks/{task}', [TaskController::class, 'show']);
Route::post('lists/{list}/tasks', [TaskController::class, 'store']);
Route::put('lists/{list}/tasks/{task}', [TaskController::class, 'update']);
Route::delete('lists/{list}/tasks/{task}', [TaskController::class, 'destroy']);


Route::get('/lists/{list}/tasks', [TaskController::class, 'tasksByList']); // get all tasks for specific list
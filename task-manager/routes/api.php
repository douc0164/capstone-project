<?php

use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
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

Route::get('lists/{list}/tasks', [TaskController::class, 'index']);
Route::get('lists/{list}/tasks/{task}', [TaskController::class, 'show']);
Route::post('lists/{list}/tasks', [TaskController::class, 'store']);
Route::put('lists/{list}/tasks/{task}', [TaskController::class, 'update']);
Route::delete('lists/{list}/tasks/{task}', [TaskController::class, 'destroy']);

Route::get('/lists/{list}/tasks', [TaskController::class, 'tasksByList']); // get all tasks for specific list

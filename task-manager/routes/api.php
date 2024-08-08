<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('priorities', [PriorityController::class, 'index']);

Route::get('/lists/{user_id}', [TaskListController::class, 'index']); // shows all lists available
Route::post('/lists/add', [TaskListController::class, 'store']);
Route::get('/lists/{id}', [TaskListController::class, 'show']); // getting single list
Route::put('/lists/{id}', [TaskListController::class, 'update']);
Route::delete('/lists/{id}', [TaskListController::class, 'destroy']);

Route::get('lists/{list}/tasks', [TaskController::class, 'index']);
Route::get('lists/{list}/tasks/{task}', [TaskController::class, 'show']);
Route::post('lists/tasks/add', [TaskController::class, 'store']);
Route::put('lists/{list}/tasks/{task}', [TaskController::class, 'update']);
Route::delete('lists/{list}/tasks/{task}', [TaskController::class, 'destroy']);

Route::get('/lists/{list}/tasks', [TaskController::class, 'tasksByList']); // get all tasks for specific list

Route::get('lists/sort/{sort}/{userId}', [TaskListController::class, 'sortLists']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware(['auth:sanctum'])->apiResource('users.lists', TaskListController::class);
// Route::middleware(['auth:sanctum'])->apiResource('users.lists.tasks', TaskController::class);

// Route::get('/lists', [TaskListController::class, 'index']); // shows all lists available
// Route::post('/lists', [TaskListController::class, 'store']);
// Route::get('/lists/{id}', [TaskListController::class, 'show']); // getting single list
// Route::put('/lists/{id}', [TaskListController::class, 'update']);
// Route::delete('/lists/{id}', [TaskListController::class, 'destroy']);

// // Route::get('/tasks', [TaskController::class, 'index']);
// // Route::post('/tasks', [TaskController::class, 'store']);
// // Route::get('/tasks/{id}', [TaskController::class, 'show']);
// // Route::put('/tasks/{id}', [TaskController::class, 'update']);
// // Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

// Route::get('lists/{list}/tasks', [TaskController::class, 'index']);
// Route::get('lists/{list}/tasks/{task}', [TaskController::class, 'show']);
// Route::post('lists/{list}/tasks', [TaskController::class, 'store']);
// Route::put('lists/{list}/tasks/{task}', [TaskController::class, 'update']);
// Route::delete('lists/{list}/tasks/{task}', [TaskController::class, 'destroy']);


// Route::get('/lists/{list}/tasks', [TaskController::class, 'tasksByList']); // get all tasks for specific list

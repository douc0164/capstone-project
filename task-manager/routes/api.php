<?php

use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('users/{user}', [UserController::class, 'show']);

    Route::prefix('users/{user}/lists')->group(function () {
        Route::get('/', [TaskListController::class, 'index']);
        Route::post('/', [TaskListController::class, 'store']);
        Route::get('{list}', [TaskListController::class, 'show']);
        Route::put('{list}', [TaskListController::class, 'update']);
        Route::delete('{list}', [TaskListController::class, 'destroy']);
    });

    Route::prefix('users/{user}/lists/{list}/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        Route::get('{task}', [TaskController::class, 'show']);
        Route::put('{task}', [TaskController::class, 'update']);
        Route::delete('{task}', [TaskController::class, 'destroy']);
    });
});

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

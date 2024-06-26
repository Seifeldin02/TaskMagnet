<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\SubtaskController;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// routes/web.php


Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/{id}/get-time-remaining', [TaskController::class, 'getRemainingTime'])
    ->name('tasks.getRemainingTime');
    Route::put('/tasks/{task}/priorityUp', [TaskController::class, 'priorityUp'])->name('tasks.priorityUp');
    Route::put('/tasks/{task}/priorityDown', [TaskController::class, 'priorityDown'])->name('tasks.priorityDown');
    Route::get('tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit'); 
    Route::patch('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('/tasks/{task}/updateCompletionRate', [TaskController::class, 'updateCompletionRate']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('tasks.comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('/tasks/{task}/share', [TaskController::class, 'share'])->name('tasks.share');
    Route::get('/tasks/{task}/accept', [TaskController::class, 'accept'])->name('tasks.accept');
    Route::delete('/shares/{share}', [ShareController::class, 'destroy'])->name('shares.destroy');
    Route::patch('/tasks/{task}/completion', [TaskController::class, 'updateCompletion'])->name('tasks.updateCompletion');
    Route::delete('/tasks/{task}/shares/{user}', [TaskController::class, 'removeShare'])->name('tasks.removeShare');
    Route::get('/notifications/unreadCount', 'NotificationController@unreadCount')->name('notifications.unreadCount');
    Route::post('/tasks/toggleAutoSort', [TaskController::class, 'toggleAutoSort'])->name('tasks.toggleAutoSort');
    Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::get('/subtasks/{subtask}/edit', [SubtaskController::class, 'edit'])->name('subtasks.edit');
    Route::patch('/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
    Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
    Route::patch('/subtasks/{subtask}/completion', [SubtaskController::class, 'updateCompletion'])->name('subtasks.updateCompletion');
    
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-schedule', function() {
    Artisan::call('schedule:run');
    return 'OK';
})->middleware('ip');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

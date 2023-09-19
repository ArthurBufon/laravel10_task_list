<?php

use App\Http\Controllers\ProfileController;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Welcome page.
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// User authenticated routes.
Route::middleware('auth')->group(function () {

    // Dashboard.
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // All Tasks.
    Route::get('/tasks', function () {
        return view('index', [
            'tasks' => Task::latest()->paginate(6)
        ]);
    })->name('tasks.index');

    // Form create Task.
    Route::view('tasks/create', 'create')->name('tasks.create');

    // Show Task.
    Route::get('/tasks/{task}', function (Task $task) {
        return view('show', ['task' => $task]);
    })->name('tasks.show');

    // Form edit task.
    Route::get('/tasks/{task}/edit', function (Task $task) {
        return view('edit', ['task' => $task]);
    })->name('tasks.edit');

    // New Task.
    Route::post('/tasks', function (TaskRequest $request) {
        $task = Task::create($request->validated());

        return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task created successfully!');
    })->name('tasks.store');

    // Update Task.
    Route::put('/tasks/{task}', function (Task $task, TaskRequest $request) {
        $task->update($request->validated());

        return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task updated successfully!');
    })->name('tasks.update');

    // Delete Task.
    Route::delete('/tasks/{task}', function (Task $task) {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    })->name('tasks.destroy');

    // Complete Task.
    Route::put('tasks/{task}/toggle-complete', function (Task $task) {
        $task->toggleComplete();

        return redirect()->back()->with('success', 'Task updated successfully!');
    })->name('tasks.toggle-complete');

    // Profile Routes.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route not found.
Route::fallback(function () {
    return 'Still got somewhere!';
});

// Auth routes (login/register/etc...)
require __DIR__.'/auth.php';

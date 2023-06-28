<?php

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return redirect()->route('tasks.index');
});

// All Tasks.
Route::get('/tasks', function(){
    return view('index', [
        'tasks' => Task::latest()->where('completed', true)->get()
    ]);
})->name('tasks.index');

// Form create Task.
Route::view('tasks/create', 'create')->name('tasks.create');

// Show Task.
Route::get('/tasks/{task}', function(Task $task){
    return view('show', ['task' => $task]);
})->name('tasks.show');

// Form edit task.
Route::get('/tasks/{task}/edit', function(Task $task){
    return view('edit', ['task' => $task]);
})->name('tasks.edit');

// New Task.
Route::post('/tasks', function(TaskRequest $request){
    $task = Task::create($request->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task created successfully!');
})->name('tasks.store');

// Update Task.
Route::put('/tasks/{task}', function(Task $task, TaskRequest $request){
    $task->update($request->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task updated successfully!');
})->name('tasks.update');

// Delete Task.
Route::delete('/tasks/{task}', function(Task $task) {
    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
})->name('tasks.destroy');

// Route not found.
Route::fallback(function () {
    return 'Still got somewhere!';
});

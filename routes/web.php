<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('todos.index');
});

Route::resource('todos', TodoController::class);
Route::resource('categories', CategoryController::class);

// AJAX routes
Route::patch('todos/{todo}/toggle-complete', [TodoController::class, 'toggleComplete'])->name('todos.toggle-complete');
Route::post('todos/update-positions', [TodoController::class, 'updatePositions'])->name('todos.update-positions');
Route::post('todos/quick-add', [TodoController::class, 'quickAdd'])->name('todos.quick-add');

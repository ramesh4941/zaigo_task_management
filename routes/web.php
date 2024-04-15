<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

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
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/task', [HomeController::class, 'task'])->name('task');
    Route::post('/create_task', [HomeController::class, 'create_task'])->name('create_task');
    Route::get('/delete_task/{id}', [HomeController::class, 'delete_task'])->name('delete_task');
    Route::post('/update_task/{id}', [HomeController::class, 'update_task'])->name('update_task');

    Route::get('/task-details/{id}', [HomeController::class, 'task_details'])->name('task_details');
    Route::post('/user_and_status/{id}', [HomeController::class, 'user_and_status'])->name('user_and_status');

    Route::post('/post_comment/{id}', [HomeController::class, 'post_comment'])->name('post_comment');
    Route::post('/update_comment/{id}', [HomeController::class, 'update_comment'])->name('update_comment');
    Route::get('/delete_comment/{id}', [HomeController::class, 'delete_comment'])->name('delete_comment');
});
?>
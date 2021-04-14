<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/', 'front')->name('front');

Route::get('/movies', [\App\Http\Controllers\MovieController::class, 'index'])->name('movies.index');
Route::group(['middleware'=>'admin'], function()
{
    Route::get('/movies/create', [\App\Http\Controllers\MovieController::class, 'create'])->name('movies.create');
    Route::post('/movies', [\App\Http\Controllers\MovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{movie}/edit', [\App\Http\Controllers\MovieController::class, 'edit']);
    Route::put('/movies/{movie}/update', [\App\Http\Controllers\MovieController::class, 'update']);
    Route::delete('/movies/{movie}/delete', [\App\Http\Controllers\MovieController::class, 'destroy']);
});
Route::get('/movies/{movie}', [\App\Http\Controllers\MovieController::class, 'show'])->name('movies.show');





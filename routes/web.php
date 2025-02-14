<?php

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
    return view('app');
});

Route::resources(['foods' => 'App\Http\Controllers\FoodController']);
Route::resources(['pos' => 'App\Http\Controllers\posController']);
Route::resources(['customers' => 'App\Http\Controllers\CustomerController']);
Route::resources(['transactions' => 'App\Http\Controllers\TransactionController']);

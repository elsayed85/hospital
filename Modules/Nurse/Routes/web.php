<?php

use Modules\Nurse\Http\Controllers\HomeController;
use Modules\Nurse\Http\Controllers\LogoutController;

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

Route::get('/', [HomeController::class, "index"])->name('home');
Route::post('logout', [LogoutController::class, "logout"])->name('logout');

<?php

use App\Models\Hospital;
use App\Models\User;
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

Route::get('/', function () {
    // auth : login , register ..
    // acccount settings
    // features & usage // https://github.com/elsayed85/subscriptions
    return view('welcome');
});

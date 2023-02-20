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
    if (request("run") == 1) {
        Hospital::all()->runForEach(function () {
            App\Models\User::factory(10)->create();
        });
    }
    return view('welcome');
});

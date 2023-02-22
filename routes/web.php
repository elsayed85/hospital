<?php

use App\Models\Doctor;
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
    $user = auth()->user();
    $doctor = auth("doctor")->user();
    $doctors = Doctor::all();
    dd($user, $doctor, $doctors);
});

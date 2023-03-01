<?php

declare(strict_types=1);

use App\Http\Controllers\Hospital\Auth\LoginController;
use App\Http\Controllers\Hospital\MainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Hospital Routes
|--------------------------------------------------------------------------

| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::get("/", [MainController::class, "index"])->name("index");

// Route::redirect('/', "login")->name("index");

Route::get("login/{type?}", [LoginController::class, "showLoginFrom"])->name("login");
Route::post("login", [LoginController::class, "login"])->name("login");

<?php

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Sawirricardo\IcdApi\Connectors\AccessTokenConnector;
use Sawirricardo\IcdApi\Connectors\IcdConnector;
use Sawirricardo\IcdApi\IcdApi;
use Sawirricardo\IcdApi\Requests\CreateAccessTokenRequest;
use Sawirricardo\IcdApi\Requests\ViewEntityRequest;


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

Route::get('/{id}', function ($id) {

});

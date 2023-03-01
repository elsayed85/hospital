<?php

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\ProjectFile;
use App\Models\User;
use App\Services\ICD\ICD;
use App\Services\Locker;
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

Route::get("", function () {
    $db_files = ProjectFile::all(["file", "hash"]);
    $locker = (new Locker())->setSecret("secret")->setDbFiles($db_files);

    $installed_modules = $locker->getInstalledModulesWithHash();

    $folders = [
        // "app/Http/Controllers",
        // "app/Http/Middleware",
        // "app/Http/Requests",
        // "app/Services",
        // "app/Traits",
        // "app/Providers",
        // "config",
        // "database/migrations",
        // "database/seeders",
        "Modules",
        "routes",
    ];

    foreach ($folders as $dir) {
        $check[$dir] = $locker->setDirectory($dir)->compute();
    }



    return [
        "installed_modules" => $installed_modules,
        "check" => $check,
    ];
});

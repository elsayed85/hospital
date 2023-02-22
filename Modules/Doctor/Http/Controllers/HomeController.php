<?php

namespace Modules\Doctor\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // dd(auth_permissions());

        // $role = role("admin");
        // $user = auth()->user();
        // $user->assignRole($role);

        return view('doctor::index');
    }
}

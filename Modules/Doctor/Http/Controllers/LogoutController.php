<?php

namespace Modules\Doctor\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LogoutController extends Controller
{
    public function logout()
    {
        auth()->logout();
        return redirect()->route('hospital.login', ['type' => 'doctor']);
    }
}

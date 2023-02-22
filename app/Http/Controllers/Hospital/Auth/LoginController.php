<?php

namespace App\Http\Controllers\Hospital\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Hash;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class LoginController extends Controller
{
    public function showLoginFrom($type = null)
    {
        $type = $type ?? request('type', 'nurse');
        $pageConfigs = ['myLayout' => 'blank'];
        $types = collect(config('login.types'));

        $logged_in_as = $types->map(function ($el) {
            $guard = $el['guard'];
            return auth($guard)->check();
        });

        $guests = $logged_in_as->filter(function ($el) {
            return $el === false;
        });

        // select first guest if guests count is equal to 1
        if ($guests->count() === 1) {
            $type = $guests->keys()->first();
        }

        // if all are logged in then disable login form
        $disabled = $guests->count() === 0;

        return view('auth.login', [
            'pageConfigs' => $pageConfigs,
            'route_type' => $type,
            'types' => $types,
            'logged_in_as' => $logged_in_as->toArray(),
            'disabled' => $disabled
        ]);
    }

    public function login(LoginRequest $request)
    {
        $type = $request->input('login_type');
        $key = $request->input('login_key');
        $password = $request->input('password');
        $remember = $request->input('remember') === 'on' ? true : false;

        $guard = $type;
        $provider = config("auth.guards.{$guard}.provider");
        $model = config("auth.providers.{$provider}.model");

        $model = new $model;

        $user = $model->where(function ($whereQuery) use ($key) {
            $whereQuery->where('email', $key)
                ->orWhere('nid', $key)
                ->orWhere('username', $key);
        })->first();


        if (!$user) {
            return redirect()->back()->withInput()->withErrors([
                'login_key' => __('auth.failed')
            ]);
        }

        if (!Hash::check($password, $user->password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => __('auth.failed')
            ]);
        }

        auth($guard)->login($user, $remember);

        return redirect()->route($type . '.home');
    }
}

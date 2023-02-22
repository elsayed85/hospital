<?php

namespace App\Http\Middleware\Hospital;

use Closure;
use Illuminate\Http\Request;

class ScopeSessions
{
    public static $tenantIdKey = '_tenant_id';

    public function handle(Request $request, Closure $next)
    {
        if (!tenancy()->initialized) {
            throw new TenancyNotInitializedException('Tenancy needs to be initialized before the session scoping middleware is executed');
        }

        if (!$request->session()->has(static::$tenantIdKey)) {
            $request->session()->put(static::$tenantIdKey, tenant()->getTenantKey());
        } else {
            if ($request->session()->get(static::$tenantIdKey) !== tenant()->getTenantKey()) {
                // refresh session
                $request->session()->flush();
                // get first segment
                $user_type = $request->segment(1);
                // abort(403);
                return redirect(route('hospital.login', ['type' => $user_type]));
            }
        }

        return $next($request);
    }
}

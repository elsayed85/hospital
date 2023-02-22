<?php

namespace Modules\Doctor\Providers;

use App\Http\Middleware\Hospital\ScopeSessions;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Doctor\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware([
            "web",
            "auth:doctor",
            InitializeTenancyByDomainOrSubdomain::class,
            PreventAccessFromCentralDomains::class,
            ScopeSessions::class, // https://tenancyforlaravel.com/docs/v3/session-scoping
        ])
            ->name('doctor.')
            ->prefix("doctor")
            ->namespace($this->moduleNamespace)
            ->group(module_path('Doctor', '/Routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware([
                'api',
                PreventAccessFromCentralDomains::class
            ])
            ->name('doctor.')
            ->prefix("doctor")
            ->namespace($this->moduleNamespace)
            ->group(module_path('Doctor', '/Routes/api.php'));
    }
}

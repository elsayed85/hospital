<?php

namespace Modules\Nurse\Providers;

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
    protected $moduleNamespace = 'Modules\Nurse\Http\Controllers';

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
            "auth:nurse",
            InitializeTenancyByDomainOrSubdomain::class,
            PreventAccessFromCentralDomains::class,
            ScopeSessions::class, // https://tenancyforlaravel.com/docs/v3/session-scoping
        ])
            ->name('nurse.')
            ->prefix('nurse')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Nurse', '/Routes/web.php'));
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
            ->name('nurse.')
            ->prefix('nurse')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Nurse', '/Routes/api.php'));
    }
}

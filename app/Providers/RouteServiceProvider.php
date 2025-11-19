<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->as('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware('web','auth','admin')
                ->prefix('admin')
                ->as('admin.')
                // ->namespace($this->namespace . '\\Admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web','auth'])
                ->prefix('organization')
                ->as('organization.')
                // ->namespace($this->namespace . '\\Admin')
                ->group(base_path('routes/organization.php'));
            Route::middleware('web')
                // ->namespace($this->namespace . '\\Admin')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('wafir')
                ->as('wafir.')
                ->group(base_path('routes/wafir.php'));

            Route::middleware('external_api')
                ->prefix('external')
                ->as('external.')
                ->group(base_path('routes/api_external.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api-client', function(Request $request) {
            $client = $request->attributes->get('api_client');
            $key = $client ? 'api-client:' . $client->id : $request->ip();

            if(in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                return Limit::perMinute(5)->by($key);
            }

            return Limit::perMinute(60)->by($key);
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip() . '|' . $request->path());
        });
    }
}

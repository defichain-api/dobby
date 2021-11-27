<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
		if (app()->environment() !== 'local') {
			resolve(UrlGenerator::class)->forceScheme('https');
		}
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::domain(sprintf('api.%s', config('app.url')))
	            ->name('web_app.')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/web_app.php'));

            Route::middleware('web')
	            ->domain(config('app.url'))
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}

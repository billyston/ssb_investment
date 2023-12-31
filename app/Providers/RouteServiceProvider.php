<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

final class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for(name: 'api', callback: function (Request $request) {
            $key = $request->user() ? $request->user()->id : $request->ip();
            return Limit::perMinute(maxAttempts: 60)->by($key);
        });

        $this->routes(function (): void {
            Route::middleware('api')->group(base_path(path: 'routes/api.php'));
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use App\Libraries\RouteDiscovery\Discovery\Discover;
use Illuminate\Contracts\Foundation\CachesRoutes;

class RouteDiscoveryServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {

        if ($this->app instanceof CachesRoutes && $this->app->routesAreCached()) {
            return;
        }

        $this->registerRoutesForControllers();
        $this->registerRoutesForViews();
    }

    public function registerRoutesForControllers(): void
    {
        collect(config('route-discovery.discover_controllers_in_directory'))
            ->each(
                fn (string $directory) => Discover::controllers()->in($directory)
            );
    }

    public function registerRoutesForViews(): void
    {
        collect(config('route-discovery.discover_views_in_directory'))
            ->each(function (array|string $directories, int|string $prefix) {
                if (is_numeric($prefix)) {
                    $prefix = '';
                }

                $directories = Arr::wrap($directories);

                foreach ($directories as $directory) {
                    Route::prefix($prefix)->group(function () use ($directory) {
                        Discover::views()->in($directory);
                    });
                }
            });
    }
}

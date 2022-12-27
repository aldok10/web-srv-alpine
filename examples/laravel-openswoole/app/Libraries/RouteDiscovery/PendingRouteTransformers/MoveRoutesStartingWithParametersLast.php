<?php

namespace App\Libraries\RouteDiscovery\PendingRouteTransformers;

use Illuminate\Support\Collection;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRoute;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRouteAction;

class MoveRoutesStartingWithParametersLast implements PendingRouteTransformer
{
    /**
     * @param Collection<PendingRoute> $pendingRoutes
     *
     * @return Collection<PendingRoute>
     */
    public function transform(Collection $pendingRoutes): Collection
    {
        return $pendingRoutes->sortBy(function (PendingRoute $pendingRoute) {
            $containsRouteStartingWithUri = $pendingRoute->actions->contains(function (PendingRouteAction $action) {
                return str_starts_with($action->uri, '{');
            });

            if (! $containsRouteStartingWithUri) {
                return 0;
            }

            return $pendingRoute->actions->max(function (PendingRouteAction $action) {
                if (! str_starts_with($action->uri, '{')) {
                    return PHP_INT_MAX;
                }

                return PHP_INT_MAX - count(explode('/', $action->uri));
            });
        })
            ->values();
    }
}

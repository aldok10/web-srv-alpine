<?php

namespace App\Libraries\RouteDiscovery\PendingRouteTransformers;

use Illuminate\Support\Collection;
use App\Libraries\RouteDiscovery\Attributes\DoNotDiscover;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRoute;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRouteAction;

class HandleDoNotDiscoverAttribute implements PendingRouteTransformer
{
    /**
     * @param Collection<PendingRoute> $pendingRoutes
     *
     * @return Collection<PendingRoute>
     */
    public function transform(Collection $pendingRoutes): Collection
    {
        return $pendingRoutes
            ->reject(fn (PendingRoute $pendingRoute) => $pendingRoute->getAttribute(DoNotDiscover::class))
            ->each(function (PendingRoute $pendingRoute) {
                $pendingRoute->actions = $pendingRoute
                    ->actions
                    ->reject(fn (PendingRouteAction $action) => $action->getAttribute(DoNotDiscover::class));
            });
    }
}

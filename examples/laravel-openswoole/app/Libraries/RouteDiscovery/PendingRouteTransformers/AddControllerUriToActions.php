<?php

namespace App\Libraries\RouteDiscovery\PendingRouteTransformers;

use Illuminate\Support\Collection;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRoute;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRouteAction;

class AddControllerUriToActions implements PendingRouteTransformer
{
    /**
     * @param Collection<PendingRoute> $pendingRoutes
     *
     * @return Collection<PendingRoute>
     */
    public function transform(Collection $pendingRoutes): Collection
    {
        $pendingRoutes->each(function (PendingRoute $pendingRoute) {
            $pendingRoute->actions->each(function (PendingRouteAction $action) use ($pendingRoute) {
                $originalActionUri = $action->uri;

                $action->uri = $pendingRoute->uri;
                if($prefixPendingRoute = $pendingRoute->getPrefixAttribute()) {
                    $action->uri = $prefixPendingRoute->prefix;
                }

                $isHasCustomUri = $action->getRouteAttribute();
                if ($originalActionUri && !$isHasCustomUri) {
                    $action->uri .= "/{$originalActionUri}";
                }
            });
        });

        return $pendingRoutes;
    }
}

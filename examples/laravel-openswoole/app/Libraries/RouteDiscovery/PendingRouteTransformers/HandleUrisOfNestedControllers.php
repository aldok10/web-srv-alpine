<?php

namespace App\Libraries\RouteDiscovery\PendingRouteTransformers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRoute;
use App\Libraries\RouteDiscovery\PendingRoutes\PendingRouteAction;

class HandleUrisOfNestedControllers implements PendingRouteTransformer
{
    /**
     * @param Collection<PendingRoute> $pendingRoutes
     *
     * @return Collection<PendingRoute>
     */
    public function transform(Collection $pendingRoutes): Collection
    {
        $pendingRoutes->each(function (PendingRoute $parentPendingRoute) use ($pendingRoutes) {
            $childNode = $this->findChild($pendingRoutes, $parentPendingRoute);

            if (! $childNode) {
                return;
            }

            /** @var PendingRouteAction|null $parentAction */
            $parentAction = $parentPendingRoute->actions->first(function (PendingRouteAction $action) {
                return in_array($action->method->name, ['show', 'edit', 'update', 'destroy', 'delete']);
            });

            if (is_null($parentAction)) {
                return;
            }

            $childNode->actions->each(function (PendingRouteAction $action) use ($parentPendingRoute, $parentAction) {
                $result = Str::replace($parentPendingRoute->uri, $parentAction->uri, $action->uri);

                $action->uri = $result;
            });
        });

        return $pendingRoutes;
    }

    protected function findChild(Collection $pendingRoutes, PendingRoute $parentRouteAction): ?PendingRoute
    {
        $childNamespace = $parentRouteAction->childNamespace();

        return $pendingRoutes->first(
            fn (PendingRoute $potentialChildRoute) => $potentialChildRoute->namespace() === $childNamespace
        );
    }
}

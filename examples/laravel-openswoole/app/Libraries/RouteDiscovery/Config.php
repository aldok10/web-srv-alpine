<?php

namespace App\Libraries\RouteDiscovery;

use App\Libraries\RouteDiscovery\PendingRouteTransformers\AddControllerUriToActions;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\AddDefaultRouteName;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleDomainAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleDoNotDiscoverAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleFullUriAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleHttpMethodsAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleMiddlewareAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleRouteNameAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleUriAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleUrisOfNestedControllers;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\HandleWheresAttribute;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\MoveRoutesStartingWithParametersLast;
use App\Libraries\RouteDiscovery\PendingRouteTransformers\RejectDefaultControllerMethodRoutes;

class Config
{
    /**
     * @return array<class-string>
     */
    public static function defaultRouteTransformers(): array
    {
        return [
            RejectDefaultControllerMethodRoutes::class,
            HandleDoNotDiscoverAttribute::class,
            AddControllerUriToActions::class,
            HandleUrisOfNestedControllers::class,
            HandleRouteNameAttribute::class,
            HandleMiddlewareAttribute::class,
            HandleHttpMethodsAttribute::class,
            HandleUriAttribute::class,
            HandleFullUriAttribute::class,
            HandleWheresAttribute::class,
            AddDefaultRouteName::class,
            HandleDomainAttribute::class,
            MoveRoutesStartingWithParametersLast::class,
        ];
    }
}

<?php

namespace App\Libraries\RouteDiscovery\Discovery;

class Discover
{
    public static function controllers(): DiscoverControllers
    {
        return new DiscoverControllers();
    }

    public static function views(): DiscoverViews
    {
        return new DiscoverViews();
    }
}

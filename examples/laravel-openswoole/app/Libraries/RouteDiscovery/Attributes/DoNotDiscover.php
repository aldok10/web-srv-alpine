<?php

namespace App\Libraries\RouteDiscovery\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class DoNotDiscover implements DiscoveryAttribute
{
}

<?php

namespace App\Libraries\RouteDiscovery\PendingRoutes;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use App\Libraries\RouteDiscovery\Attributes\DiscoveryAttribute;
use App\Libraries\RouteDiscovery\Attributes\Prefix;
use App\Libraries\RouteDiscovery\Attributes\Route;
use SplFileInfo;

class PendingRoute
{
    /**
     * @param SplFileInfo $fileInfo
     * @param ReflectionClass $class
     * @param string $uri
     * @param string $fullyQualifiedClassName
     * @param Collection<PendingRouteAction> $actions
     */
    public function __construct(
        public SplFileInfo $fileInfo,
        public ReflectionClass $class,
        public string $uri,
        public string $fullyQualifiedClassName,
        public Collection $actions,
    ) {
    }

    public function namespace(): string
    {
        return Str::beforeLast($this->fullyQualifiedClassName, '\\');
    }

    public function shortControllerName(): string
    {
        return Str::of($this->fullyQualifiedClassName)
            ->afterLast('\\')
            ->beforeLast('Controller');
    }

    public function childNamespace(): string
    {
        return $this->namespace() . '\\' . $this->shortControllerName();
    }

    public function getRouteAttribute(): ?Route
    {
        return $this->getAttribute(Route::class);
    }

    public function getPrefixAttribute(): ?Prefix
    {
        return $this->getAttribute(Prefix::class);
    }

    /**
     * @template TDiscoveryAttribute of DiscoveryAttribute
     *
     * @param ?class-string<TDiscoveryAttribute> $attributeClass
     *
     * @return ?TDiscoveryAttribute
     */
    public function getAttribute(?string $attributeClass): ?DiscoveryAttribute
    {
        $attributes = $this->class->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        if (! count($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}

<?php

namespace App\Libraries\RouteDiscovery\Discovery;

use App\Libraries\RouteDiscovery\RouteRegistrar;

class DiscoverControllers
{
    protected string $basePath = '';

    protected string $rootNamespace;

    public function __construct()
    {
        $this->rootNamespace = '';

        $this->basePath = base_path();
    }

    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function useBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function in(string $directory): void
    {
        /** @phpstan-ignore-next-line */
        $router = app()->router;

        app(RouteRegistrar::class, [$router])
            ->useRootNamespace($this->rootNamespace)
            ->useBasePath($this->basePath)
            ->registerDirectory($directory);
    }
}

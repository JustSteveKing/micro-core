<?php

declare(strict_types=1);

namespace JustSteveKing\Micro;

use JustSteveKing\Micro\Contracts\KernelContract;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;

class Kernel implements KernelContract
{
    private App $app;

    private function __construct(
        private string $basePath,
        private ContainerInterface $container,
        private null|CallableResolverInterface $callableResolver,
        private null|RouteCollectorInterface $routeCollector,
        private null|RouteResolverInterface $routeResolver,
    ) {}

    public static function boot(
        string $basePath,
        ContainerInterface $container,
        null|CallableResolverInterface $callableResolver = null,
        null|RouteCollectorInterface $routeCollector = null,
        null|RouteResolverInterface $routeResolver = null,
    ): KernelContract {
        $kernel =  new Kernel(
            basePath: $basePath,
            container: $container,
            callableResolver: $callableResolver,
            routeCollector: $routeCollector,
            routeResolver: $routeResolver,
        );

        $kernel->buildSlim();

        return $kernel;
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }

    public function app(): App
    {
        return $this->app;
    }

    public function start(null|ServerRequestInterface $request = null): void
    {
        $this->app->run(
            request: $request,
        );
    }

    private function buildSlim(): void
    {
        AppFactory::setContainer(
            container: $this->container(),
        );

        $this->app = AppFactory::create(
            callableResolver: $this->callableResolver,
            routeCollector: $this->routeCollector,
            routeResolver: $this->routeResolver,
        );
    }
}

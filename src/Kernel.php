<?php

declare(strict_types=1);

namespace JustSteveKing\Micro;

use JustSteveKing\Micro\Contracts\KernelContract;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;

class Kernel implements KernelContract
{
    private App $app;

    private function __construct(
        private string $basePath,
        private ContainerInterface $container,
    ) {}

    public static function boot(
        string $basePath,
        ContainerInterface $container,
    ): KernelContract {
        $kernel =  new Kernel(
            basePath: $basePath,
            container: $container,
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
        // TODO: Implement start() method.
    }

    private function buildSlim(): void
    {
        AppFactory::setContainer(
            container: $this->container(),
        );

        $this->app = AppFactory::create();
    }
}

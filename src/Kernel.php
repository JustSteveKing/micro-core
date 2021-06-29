<?php

declare(strict_types=1);

namespace JustSteveKing\Micro;

use JustSteveKing\Micro\Contracts\KernelContract;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel implements KernelContract
{
    private function __construct(
        private string $basePath,
        private ContainerInterface $container,
    ) {}

    public static function boot(
        string $basePath,
        ContainerInterface $container,
    ): KernelContract {
        return new Kernel(
            basePath: $basePath,
            container: $container,
        );
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }

    public function start(null|ServerRequestInterface $request = null): void
    {
        // TODO: Implement start() method.
    }
}

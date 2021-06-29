<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Contracts;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

interface KernelContract
{
    /**
     * Boot our Kernel.
     *
     * @param string $basePath
     * @param ContainerInterface $container
     *
     * @return KernelContract
     */
    public static function boot(string $basePath, ContainerInterface $container): KernelContract;

    /**
     * Return the base path of the application.
     *
     * @return string
     */
    public function basePath(): string;

    /**
     * Return an instance of the underlying Slim Application.
     *
     * @return App
     */
    public function app(): App;

    /**
     * Get an instance of the built container.
     *
     * @return ContainerInterface
     */
    public function container(): ContainerInterface;

    /**
     * Start the application, passing in an optional request.
     *
     * @param ServerRequestInterface|null $request
     *
     * @return void
     */
    public function start(null|ServerRequestInterface $request = null): void;
}

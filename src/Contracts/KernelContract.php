<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Contracts;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

interface KernelContract
{
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

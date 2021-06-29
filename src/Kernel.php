<?php

declare(strict_types=1);

namespace JustSteveKing\Micro;

use JustSteveKing\Micro\Contracts\KernelContract;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel implements KernelContract
{

    public function container(): ContainerInterface
    {
        // TODO: Implement container() method.
    }

    public function start(null|ServerRequestInterface $request = null): void
    {
        // TODO: Implement start() method.
    }
}

<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DisableFlocMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle(
            request: $request,
        );

        if ($response->hasHeader(name: 'Permissions-Policy')) {
            return $response;
        }

        return $response->withHeader(
            name: 'Permissions-Policy',
            value: 'interest-cohort=()',
        );
    }
}

<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Routing;

use Slim\Interfaces\RouteCollectorInterface;
use Slim\Routing\RouteParser as SlimRouteParser;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteParser extends SlimRouteParser
{
    public function __construct(
        private RouteCollectorInterface $routeCollector
    ) {}

    protected function getRouteCollection(): RouteCollection
    {
        $routes = new RouteCollection();

        foreach ($this->routeCollector->getRoutes() as $route) {
            $routes->add(
                name: $route->getIdentifier(),
                route: (new Route(
                    path: $route->getPattern(),
                ))->setMethods(
                    methods: $route->getMethods(),
                ),
            );
        }

        return $routes;
    }

    public function relativeUrlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        $requestContext = new RequestContext();
        $routes = $this->getRouteCollection();

        $generator = new UrlGenerator(
            routes: $routes,
            context: $requestContext,
        );
        $url = $generator->generate(
            name: $routeName,
            parameters: $data,
        );

        if ($queryParams) {
            $url .= '?' . http_build_query($queryParams);
        }

        return $url;
    }
}
